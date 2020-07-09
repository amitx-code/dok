<?php

    namespace App\Libs;

    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\View;
    use Illuminate\Support\Facades\Config;

    class LinkFetcher {

        public static function Fetch($url) {
            // Cache::forget('linkfetcher:' . md5($url));

            return Cache::remember('linkfetcher:' . md5($url), 60 * 24 * 7, function() use ($url) {
                $fetcher = new static();
                return $fetcher->LoadLink($url);
            });
        }

        public function GetProtectedDomains() {
            return Cache::remember('prohibited_domains', 12, function() {
                $result = array();
                return $result; // TODO

                $query = DB::table('documents')->whereDocumentDeleted(0)->where('document_path', 'like', 'main.data.prohibited_domains.%');
                foreach ($query->get() as $q) {
                    $result[] = $q->document_name;
                }

                return $result;
            });
        }


        public function LoadLink($url) {
            $domain = parse_url($url, PHP_URL_HOST);
            if (empty($domain)) return false;


            $domainParts = explode('.', $domain);
            if (count($domainParts) < 2) return false;

            $domainService = $domainParts[count($domainParts) - 2];
            $methodName = 'Load' . ucfirst($domainService);
            if (method_exists($this, $methodName)) {
                return $this->$methodName($url);
            }


            $domainServiceFull = $domainParts[count($domainParts) - 2] . '.' . $domainParts[count($domainParts) - 1];
            if (in_array($domainServiceFull, $this->GetProtectedDomains())) {
                return false;
            }

            $text = static::LoadResource($url);


            // Попробуем найти oembed
            if (preg_match('#<link [^>]*type=["\'](application|text)/json\+oembed[\'"][^>]*>#si', $text, $matches)) {
                $href = static::Param($matches[0], 'href');
                if ($href) {
                    $data = $this->LoadOEmbed($href);

                    if ($domainService == 'youtube' || $domainService == 'vimeo' || $domainService == 'youtu' || $domainService == 'rutube') {
                        $data['contentType'] = 'video';
                    }

                    return $data;
                }
            }

            if ($domain == 'dj.ru' || $domain == 'clubdj.ru') {
                return $this->LoadLocal($url, $text);
            }


            $linkData = $this->FetchOpenGraph($url, $text);


            return $linkData;
        }


        public function LoadImageFile($url) {
            return array(
                'type' => 'image',
                'contentType' => 'image',
                'html' => '<a href="' . htmlspecialchars($url) . '" class="external-image" target="_blank"><img src="' . $url . '" width="100%"></a>',
                'preview' => $url,
                'url' => $url
            );


            $key = md5(uniqid(time() . 'ye'));
            $documentRoot = Config::get('app.documentRoot');
            $tmpFileName = $documentRoot . '/tmp/previews/source/' . $key . '.jpg';
            $protectedFileName = '/tmp/previews/source/' . $key . '.protected.jpg';
            $previewName = '/tmp/previews/' . $key . '.jpg';
            $data = static::LoadResource($url, false);
            if (!$data) return false;

            file_put_contents($tmpFileName, $data);
            $is = getimagesize($tmpFileName);
            if (!$is || empty($is[0]) || empty($is[1])) {
                unlink($tmpFileName);
                return false;
            }

            $imageProcessor = new Images();
            $imageProcessor->Convert($tmpFileName, $protectedFileName, '500b300');
            $protectedFileName = $documentRoot . $protectedFileName;
            if (!file_exists($protectedFileName)) {
                unlink($tmpFileName);
                return false;
            }

            $imageProcessor->ProtectPicture($protectedFileName, Config::get('app.documentRoot') . $previewName);
            unlink($protectedFileName);
            unlink($tmpFileName);

            return array(
                'type' => 'image',
                'contentType' => 'image',
                'html' => '<a href="' . htmlspecialchars($url) . '" class="external-image" target="_blank"><img src="' . $previewName . '"></a>',
                'preview' => $previewName,
                'url' => $url
            );
        }



        public function FetchOpenGraph($url, $text) {
            $linkData = array();

            if (preg_match_all('#<meta ([^>]+)#si', $text, $matches)) {
                foreach ($matches[1] as $metaBody) {
                    $metaName = static::Param($metaBody, 'name');
                    $metaProperty = static::Param($metaBody, 'property');
                    $metaContent = static::Param($metaBody, 'content');

                    switch ($metaProperty) {
                        case 'og:title':
                            $linkData['title'] = html_entity_decode($metaContent);
                            break;

                        case 'og:image':
                            $linkData['img'] = html_entity_decode($metaContent);
                            break;

                        case 'og:url':
                            $linkData['url'] = html_entity_decode($metaContent);
                            break;

                        case 'og:description':
                            $linkData['intro'] = html_entity_decode($metaContent);
                            break;
                    }

                    switch ($metaName) {
                        case 'og:title':
                            $linkData['title'] = html_entity_decode($metaContent);
                            break;

                        case 'og:image':
                            $linkData['img'] = html_entity_decode($metaContent);
                            break;

                        case 'og:url':
                            $linkData['url'] = html_entity_decode($metaContent);
                            break;

                        case 'og:description':
                            $linkData['intro'] = html_entity_decode($metaContent);
                            break;
                    }

                    if (empty($linkData['title'])) {
                        if (preg_match('#<title[^>]*>(.+?)</title>#si', $text, $matches)) {
                            $linkData['title'] = $matches[1];
                        }
                    }
                }
            }

            if (!empty($linkData)) {
                $linkData['type'] = 'article';
                if (empty($linkData['url'])) {
                    $linkData['url'] = $url;

                }
                $linkData['contentType'] = 'misc';
                // FIXME $linkData['html'] = View::make('stream.attached.article', $linkData)->render();


            } else if (preg_match('#\.(png|jpg|jpeg|gif)#', $url)) {
                $linkData = $this->LoadImageFile($url);
            }

            return $linkData;
        }



        public static function LoadSoundcloud($url) {
            $text = static::LoadResource($url);

            // Попробуем найти oembed
            if (preg_match('#<link [^>]*type=["\'](application|text)/json\+oembed[\'"][^>]*>#si', $text, $matches)) {
                $href = static::Param($matches[0], 'href');
                if ($href) {
                    $data = static::LoadOEmbed($href);
                    if (isset($data['html'])) {
                        $data['html'] = str_replace('&visual=true', '', $data['html']);
                        $data['html'] = preg_replace('#height="?[0-9]+"?#', ' height="166"', $data['html']);
                    }


                    $data['contentType'] = 'music';
                    return $data;
                }
            }
        }


        public static function LoadVbox7($url) {
            if (preg_match('#play:([a-z0-9A-Z]+)#', $url, $m)) {
                return array(
                    'contentType' => 'video',
                    'html' => '<iframe width="542" height="315" src="http://vbox7.com/emb/external.php?vid=' . $m[1] . '" frameborder="0" allowfullscreen></iframe>'
                );
            }

            return false;
        }

        /*
        public static function LoadBeatport($url) {
            if (preg_match('#/([0-9]+)($|/)#', $url, $m)) {
                $playerId = $m[1];

            } else {
                return false;
            }

            return array(
                'type' => 'rich',
                'html' => '<object type="application/x-shockwave-flash" data="https://www.beatport.com/CDN/swf/beatportplayer.swf" height="264" width="442" style="display:block;" align="center">
                <param name="movie" value="https://www.beatport.com/CDN/swf/beatportplayer.swf"/>
                <param name="allownetworking" value="internal"/>
                <param name="allowScriptAccess" value="never"/>
                <param name="enableJSURL" value="false"/>
                <param name="enableHREF" value="false"/>
                <param name="saveEmbedTags" value="true"/>
                <param name="flashvars" value="bpCfgPath=http://www.beatport.com/en-US/xml/gui/swf/configuration/3&playerId=' . $playerId . '&autoplay=0&volume=80"/>
                <param name="loop" value="false"/>
                <param name="menu" value="false"/>
                <param name="quality" value="high"/>
                <param name="salign" value="lt"/>
                <param name="scale" value="noscale"/>
            </object>');
        }
        */

        public static function LoadVk($url) {
            $data = static::LoadResource($url, false);

            if (preg_match('#var vars = (.+?\});#si', $data, $m)) {
                $vars = $m[1];
                if (!preg_match('##u', $vars)) {
                    $vars = mb_convert_encoding($vars, 'utf-8', 'windows-1251');
                }
                $vars = stripslashes($vars);
                $data = json_decode($vars);

                return array(
                    'title' => $data->md_title,
                    'img' => $data->jpg,
                    'contentType' => 'video',
                    'html' => '<iframe src="//vk.com/video_ext.php?oid=' . $data->oid . '&id=' . $data->vid . '&hash=' . $data->hash2 . '&hd=2" width="542" height="305" frameborder="0"></iframe>'
                );

                print $vars;
            }

            return false;
        }


        public static function Param($str, $name) {
            if (preg_match('#' . $name . '=(([\'"])(.*?)\2|([^ ]*))#si', $str, $matches)) {
                return isset($matches[3]) ? $matches[3] : $matches[4];

            } else {
                return false;
            }
        }


        public static function LoadResource($url, $limit = 40000) {
            $ch = curl_init();
            $data = '';

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT x.y; rv:10.0) Gecko/20100101 Firefox/10.0');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

            if ($limit) {
                curl_setopt($ch, CURLOPT_RANGE, '0-40000');
                curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) use (&$data, $limit) {
                    $len = strlen($data) + strlen($chunk);
                    if ($len >= $limit) {
                        $data .= substr($chunk, 0, $limit - strlen($data));
                        return -1;
                    }

                    $data .= $chunk;
                    return strlen($chunk);
                });
            }

            $output = curl_exec($ch);
            curl_close($ch);


            if (!preg_match('##u', mb_substr($data, 0, 12000, 'utf-8'))) {
                $data = mb_convert_encoding($data, 'utf-8', 'windows-1251');
            }

            return $limit ? $data : $output;
        }


        public static function LoadOEmbed($url) {
            $data = file_get_contents($url);
            if (!$data) return false;

            $data = json_decode($data, true);
            if (!$data) return false;

            $data['img'] = $data['thumbnail_url'];

            return $data;
        }


        public function LoadLocal($url, $text) {
            list ($itemType, $itemId) = ItemLinks::ResolveLink($url);

            switch ($itemType) {
                case 'gallery':
                    $gallery = PhotoGallery::find($itemId);

                    if ($gallery) {
                        $html = '<div style="margin-bottom: 20px;"><h2 class="gallery__h"><a href="' . $gallery->url . '">' . $gallery->name . '</a></h2>' .
                                '<span class="gallery__date">' . Helpers::Date($gallery->created, true) . '</span>' .
                                '<a href="' . $gallery->url . '"><img src="' . $gallery->GetSnippet(false, 'bigger') . '" style="height: 118px"></a>';

                        $location = $gallery->location;
                        if ($location) {
                            $club = $location['club'];
                            $html .= '<div class="gallery__map"><span class="icon-img"></span><a href="' . $club->url . '">' . $club->name . '</a><br>' . (isset($club->data->address) ? $club->data->address : '') . '</div>';
                        }

                        $html .= '</div>';

                        return array('type' => 'rich', 'contentType' => 'image', 'html' => $html);
                    }

                    return false;

                case 'music':
                    $track = Track::find($itemId);
                    return array('type' => 'rich', 'contentType' => 'music', 'html' => View::make('snippets.track', ['m' => $track->playerData, 'properTag' => 'div'])->render());

                    return false;

                case 'bill':
                    $bill = Bill::find($itemId);
                    return array('type' => 'rich', 'contentType' => 'music', 'html' => View::make('afisha.list_item', ['b' => $bill, 'properTag' => 'div'])->render());

                    break;

                default:
                    return $this->FetchOpenGraph($url, $text);
                    break;
            }



            return false;
        }


        public static function GetEffectiveUrl($url) {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_FOLLOWLOCATION => TRUE,  // the magic sauce
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_SSL_VERIFYHOST => FALSE, // suppress certain SSL errors
                CURLOPT_SSL_VERIFYPEER => FALSE,
            ));

            if (curl_exec($ch)) {
                return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            }

            return false;
        }

    }