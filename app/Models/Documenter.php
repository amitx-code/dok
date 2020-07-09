<?php

namespace App\Models;

class Documenter extends CachedModel {
	protected $table = 'documenter_list';
	public $timestamps = false;
	protected $guarded = array('id');


/*
        public static function getMenu($userId) {
            $submenu = [];

		// статичные пункты меню жестко заданные раз и навсегда
		$submenu[] = (object) [
			'name' => 'Все документы', 
			'url' => '/admin/documenter/list', 
			'icon' => 'icon-folder',
		];
		$submenu[] = (object) [
			'name' => 'Настройки',
			'url' => '/admin/documenter/settings',
			'icon' => 'icon-settings',
		];
		$submenu[] = (object) [
			'name' => 'Помощь',
			'url' => '/admin/documenter/help', 
			'icon' => 'icon-question',
		]; 

            return $submenu;
        }
*/





/*
// ОТ ДРУГОГО ПРОЕКТА, НО ПОКА ОСТАВИЛ - МОЖЕТ ПРИГОДИТСЯ КАК ПРИМЕР

        public function SetDefaultFields($user) {
            parent::SetDefaultFields($user);

            $this->hash = md5(uniqid(time()));
        }


        public function getServicesAttribute() {
            $services = [];
            foreach (['callback', 'form', 'social', 'toolbar'] as $key) {
                if ($this->{'is_' . $key . '_active'} == 'on') {
                    $services[] = $key;
                }
            }

            // return ['callback', 'form', 'social', 'toolbar']; // fixme
            return $services;
        }


        public function getSocialAttribute() {
            $data = (object) [];

            if (!empty($this->attributes['social'])) {
                $social = json_decode($this->attributes['social']);
                if ($social) {
                    $data = $social;
                }
            }

            return $data;
        }


        public function setSocialAttribute(array $data) {
            $this->attributes['social'] = json_encode($data);
        }


        public function getToolbarAttribute() {
            $data = null; // (object) [];

            if (!empty($this->attributes['toolbar'])) {
                $social = json_decode($this->attributes['toolbar']);
                if ($social) {
                    $data = $social;
                }
            }

            if ($data === null) {
                // выдаем значения по умолчанию
                $data = Toolbar::getDefaultSettings();
            }

            return $data;
        }

        public function getCallbackAttribute() {
            $data = null; // (object) [];

            if (!empty($this->attributes['callback'])) {
                $form = json_decode($this->attributes['callback']);
                if ($form) {
                    $data = $form;
                }
            }

            return $data;
        }

        public function setCallbackAttribute($data) {
            $this->attributes['callback'] = json_encode($data);
        }


        public function getFormAttribute() {
            $data = (object) [];

            if (!empty($this->attributes['form'])) {
                $form = json_decode($this->attributes['form']);
                if ($form) {
                    $data = $form;
                }
            }

            return $data;
        }


        public function setFormAttribute(array $data) {
            $this->attributes['form'] = json_encode($data);
        }


        public function setToolbarAttribute($data) {
            $this->attributes['toolbar'] = json_encode($data);
        }


        public function getToolbarColorsAttribute() {
            $toolbar = $this->toolbar;

            $colors = ['icon-color' => 'golden', 'text-color' => 'white', 'toolbar-color' => 'dark'];
            foreach (Toolbar::getColorFields() as $colorKey) {
                if (!empty($toolbar->$colorKey)) {
                    $colors[$colorKey] = $toolbar->$colorKey;
                }
            }


            return (object) $colors;
        }


        public function getToolbarDataAttribute() {
            if ($this->is_toolbar_active === 'on') {
                return $this->toolbar;
            }

            return null;
        }


        public function getSocialDataAttribute() {
            if ($this->is_social_active === 'on') {
                return $this->social;
            }

            return null;
        }

        public function getCallbackDataAttribute() {
            if ($this->is_callback_active === 'on') {
                $settings = $this->callback;

                if ($settings) {
                    $data = [
                        'active' => true,
                        'autoshow' => (!empty($settings->autoshow) && $settings->autoshow_time) ? +$settings->autoshow_time : 0,
                    ];

                    return $data;
                }
            }

            return null;
        }

        public function getFormDataAttribute() {
            if ($this->is_form_active === 'on') {
                $data = [];
                $query = Form::where('site_id', $this->id)->where('is_active', 'on')->orderBy('name');

                foreach ($query->get() as $q) {
                    $data[] = (object) [
                        'id' => $q->hash,
                        'name' => $q->name,
                        'fields' => $q->fields,
                    ];
                }

                return ['forms' => $data];
            }

            return null;
        }

        public function getUserAttribute() {
            return User::find($this->user_id);
        }

        public function getCallbackPhoneAttribute() {
            return $this->callback->phone ?? $this->user->phone ?? false;
        }


        public function getCallbackEmailAttribute() {
            return $this->callback->email ?? $this->user->email ?? false;
        }

        public function isWorkingHour() {
            $workFrom = $this->callback->work_from ?? 0;
            $workTo = $this->callback->work_to ?? 24;

            $hour = date('H') - 0;
            return ($hour >= $workFrom && $hour <= $workTo);
        }


        public function getWorkFromAttribute() {
            return $this->callback->work_from ?? 0;
        }

        public function getWorkToAttribute() {
            return $this->callback->work_to ?? 24;
        }

*/

}