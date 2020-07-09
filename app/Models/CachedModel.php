<?php

    namespace App\Models;

    use App\Libs\Helpers;
    use App\Libs\Images;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Http\Exception\HttpResponseException;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Config;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\DB;

    /**
     * Class CachedModel
     * @package App\Models
     * @method static find(integer $id)
     */

    class CachedModel extends Model {
        public static $cache = false;
        protected static $localCache = [];
        protected static $ttl = 60;

        public static $rules = [];
        protected static $imageFields = [];
        protected static $dataFields = [];
        protected $dataFieldsCached = array();
        protected $publishedStatusChanged = false;

        public static function GetRules($fields, $userId = false) {
            $result = array();

            foreach (static::$rules as $field => $rules) {
                if ($fields === true || in_array($field, $fields)) {
                    $result[$field] = str_replace(':id:', $userId, $rules);
                }
            }

            return $result;
        }

        public static function __callStatic($method, $params) {
            if ($method == 'find') {
                $instance = new static;

                return $instance->findCached($params[0], empty($params[1]) ? false : $params[1], empty($params[2]) ? false : $params[2]);

            } else {
                return parent::__callStatic($method, $params);
            }
        }


        public function pagedQuery($request) {
            $this->take(15)->skip(0);

            $data = [];

            foreach ($this->get() as $q) {
                $data[] = $q->ajax_data;
            }

            return $data;
        }


        public function StoreImage($field, $value, &$output = array()) {
            if (empty($value)) {

                return;
            }

            $params = static::$imageFields[$field];

            list ($dimensions, $url) = array_pad(explode(';', $value, 2), 2, false);
            $offset = false;

            if (empty($url)) {
                $url = $dimensions;
                $dimensions = false;

            } elseif (preg_match('#^[0-9]+x[0-9]+\+[0-9]+x[0-9]+(:.*)?$#', $dimensions)) {
                list ($dimensions, $offset) = array_pad(explode(':', $dimensions, 2), 2, false);
                $dimensions = preg_split('#[^0-9]+#', $dimensions);
                $output['offset'] = $offset;

            } else {
                $dimensions = false;
            }


            $storageKey = $params['key'];
            $hash = md5(uniqid('video-key'));
            $storageKey = str_replace(':hash:', $hash, $storageKey);
            if ($this->id) {
                $storageKey = str_replace(':id:', $this->id, $storageKey);
                $storageKey = str_replace(':id-dir:', sprintf('%02d/%d', $this->id % 100, $this->id), $storageKey);
                $storageKey = str_replace(':id-dir-hash:', sprintf('%02d/%d_%s', $this->id % 100, $this->id, $hash), $storageKey);
            }
            $hash = substr($hash, 0, 2) . '/' . $hash;
            $storageKey = str_replace(':hash-dir:', $hash, $storageKey);


            $processor = new Images();
            $res = $processor->ResizeAndStore($url, $storageKey, $params['dimensions'], $dimensions, $params['storage'], !empty($params['storeAsIs']));

            if ($res) {
                if (empty($params['noStore'])) {
                    $this->attributes[$field] = $res;
                    if ($offset !== false) {
                        $this->attributes[$field . '_offset'] = $offset;
                    }
                }
            }

            $storage = Config::get('filesystems.disks');

            return $storage[$params['storage']]['url'] . $storageKey;
        }


        public function StoreDataField($key, $value) {
            $desc = static::$dataFields[$key];
            $field = empty($desc['field']) ? 'data' : $desc['field'];

            if (empty($this->attributes[$field])) {
                $data = array();
            } else {
                $data = json_decode($this->attributes[$field], true);
                if (empty($data)) {
                    $data = array();
                }
            }

            $data[$key] = $value;
            $this->attributes[$field] = json_encode($data);
            unset($this->dataFieldsCached[$field]);
        }


        public function SetDefaultFields($user) {
            $this->created = Helpers::Now();
            if (Schema::hasColumn($this->getTable(), 'user_id')) {
                $this->user_id = $user->id;
            }
        }


        public static function FindProtected($id, $user, $failureRedirect = false) {
            if (empty($id)) {
                $item = new static();
                $item->SetDefaultFields($user);
                return $item;

            } else {
                $item = static::find($id);
                if (!$item) {
                    if ($failureRedirect) {
                        throw new \Exception($failureRedirect, 301);
                    }
                    throw new HttpResponseException(new JsonResponse(['reason' => 'Объект не найден'], 404));

                }

                if ($item->user_id != $user->id) {
                    if ($failureRedirect) {
                        throw new \Exception($failureRedirect, 301);
                    }
                    throw new HttpResponseException(new JsonResponse(['reason' => 'Вы не можете редактировать этот объект'], 403));
                }

                return $item;
            }
        }


        public function findCached($itemId, $userId = false, $resetCache = false) {
            $id = $itemId . ($userId ? '-' . $userId : '');

            if (static::$cache) {
                $cacheKey = static::GetModelKey($id);

                if (isset(static::$localCache[$cacheKey])) {
                    return static::$localCache[$cacheKey];
                }

                if (!$resetCache && Cache::has($cacheKey)) { // FIXME
                    $stored = Cache::get($cacheKey);
                    static::$localCache[$id] = $stored == '-' ? false : $stored;
                    return $stored == '-' ? false : $stored;  // ugly, yes, but what's up with cache refusing to store false? =)
                }

                $result = $this->findStorageMapped($id); //  parent::find($id, $columns);

                if ($result) {
                    Cache::put($cacheKey, $result, static::$ttl > 10 ? static::$ttl + rand(0, 20) / 10 : static::$ttl); // so not all keys are invalidated at the same time
                    static::$localCache[$cacheKey] = $result;

                } else {
                    Cache::put($cacheKey, '-', 3); // cache absense for 3 minutes - if anything happens :)
                }

            } else {
                $result = $this->findStorageMapped($id, $userId); // parent::find($id, $columns);
            }

            return $result;
        }


        public function findStorageMapped($itemId, $userId = false) {
            if (!$userId) {
                list ($itemId, $userId) = array_pad(explode('-', $itemId), 2, false);
            }

            return parent::find($itemId, ['*']);
        }



        public function BeforeSave() {
            if (empty($this->attributes['created'])) {
                $this->attributes['created'] = Helpers::Now();
            }

            return true;
        }


        public function CanPublishStreamItem() {
            return true;
        }

        public function AfterSave() {
            if ($this->publishedStatusChanged) {
                if ($this->CanPublishStreamItem()) {
                    $stream = new Userstream();
                    if ($this->published == 'on') {
                        $stream->AddStreamItem($this);

                    } else {
                        $stream->RemoveStreamItem($this);
                    }
                }
            }

            return true;
        }



        public function save(array $options = []) {
            if (!$this->BeforeSave()) {
                return false;
            }
            $result = parent::save($options);

            if ($result) {
                Cache::forget(static::GetModelKey($this->{ $this->primaryKey }));
                $this->AfterSave();

                if ($this->published == 'on') {
                    $rating = new Rating();
                    $rating->RegisterAction('added', ['item_type' => $this->general_type, 'item_id' => $this->id], $this->user_id);
                }
            }

            return $result;
        }


        public function BeforeDelete() {
            return true;
        }

        public function AfterDelete() {
            if ($this->published == 'on') {
                $rating = new Rating();
                $rating->UnregisterAction('added', ['item_type' => $this->general_type, 'item_id' => $this->id], $this->user_id);
            }

            return true;
        }

        public function delete() {
            if (!$this->BeforeDelete()) {
                return false;
            }

            return DB::transaction(function() {
                $result = parent::delete();

                if ($result) {
                    $this->AfterDelete();
                }

                return $result;
            });
        }



        public static function GetModelKey($id) {
            return 'model_' . get_called_class() . '_' . $id;
        }


        protected $cachedMutators = [];
        protected $cachedMutatorsValues = [];

        public function hasGetMutator($key) {
            if (method_exists($this, 'get'.studly_case($key).'Attribute')) {
                return true;

            } else if (isset($this->cachedMutators[$key])) {
                return true;

            } else if (method_exists($this, 'get'.studly_case($key).'AttributeCached')) {
                $this->cachedMutators[$key] = true;
                return true;

            } else if (isset(static::$dataFields[$key])) {
                return true;
            }

            return false;
        }

        /**
         * Get the value of an attribute using its mutator.
         *
         * @param  string  $key
         * @param  mixed   $value
         * @return mixed
         */
        protected function mutateAttribute($key, $value) {
            if (isset($this->cachedMutators[$key])) {
                if (!isset($this->cachedMutatorsValues[$key])) {
                    $this->cachedMutatorsValues[$key] = $this->{'get' . studly_case($key) . 'AttributeCached'}($value);
                }

                return $this->cachedMutatorsValues[$key];

            } else if (isset(static::$dataFields[$key])) {
                $desc = static::$dataFields[$key];
                $field = empty($desc['field']) ? 'data' : $desc['field'];

                if (!isset($this->dataFieldsCached[$field])) {
                    if (empty($this->attributes[$field])) {
                        $data = array();
                    } else {
                        $data = json_decode($this->attributes[$field], true);
                        if (empty($data)) {
                            $data = array();
                        }
                    }

                    $this->dataFieldsCached[$field] = $data;
                }

                return isset($this->dataFieldsCached[$field][$key]) ? $this->dataFieldsCached[$field][$key] : false;

            } else {
                return $this->{'get'.studly_case($key).'Attribute'}($value);
            }
        }



        /**
         * Set a given attribute on the model.
         *
         * @param  string  $key
         * @param  mixed   $value
         * @return void
         */
        public function setAttribute($key, $value)
        {
            // First we will check for the presence of a mutator for the set operation
            // which simply lets the developers tweak the attribute as it is set on
            // the model, such as "json_encoding" an listing of data for storage.
            if ($this->hasSetMutator($key)) {
                $method = 'set'.Str::studly($key).'Attribute';

                return $this->{$method}($value);
            }

            if (isset(static::$imageFields[$key])) {
                return $this->StoreImage($key, $value);
            }

            if (isset(static::$dataFields[$key])) {
                return $this->StoreDataField($key, $value);
            }

            // If an attribute is listed as a "date", we'll convert it from a DateTime
            // instance into a form proper for storage on the database tables using
            // the connection grammar's date format. We will auto set the values.
            elseif (in_array($key, $this->getDates()) && $value) {
                $value = $this->fromDateTime($value);
            }

            if ($this->isJsonCastable($key) && ! is_null($value)) {
                $value = json_encode($value);
            }

            $this->attributes[$key] = $value;
        }


        public function setPublishedAttribute($value) {
            if (($this->attributes['published'] ?? '') != $value) {
                $this->publishedStatusChanged = true;
            }

            $this->attributes['published'] = $value;
        }
    }
