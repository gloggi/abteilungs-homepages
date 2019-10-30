<?php

namespace App;

use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Traits\ForwardsCalls;

class Settings {
    use ForwardsCalls;

    public $settings;

    public function __construct() {
        $this->settings = $this->getDefaultSettings();

        if (Schema::hasTable('settings')) {
            Setting::all()->each(function ($dbSetting) {
                $this->settings[$dbSetting->key] = tap($this->settings[$dbSetting->key], function(&$s) use ($dbSetting) {
                    $s['value'] = $dbSetting->value;
                });
            });
        }

        $this->writeToConfig();
    }

    /**
     * Saves the given new settings values, removing any unnecessary entries from the database
     * and creating new ones where needed.
     * @return $this updated settings
     */
    public function update($request) {
        $defaultSettings = $this->getDefaultSettings();
        collect($request)->each(function($value, $key) use($defaultSettings) {
            if ($value == $defaultSettings[$key]['value']) {
                Setting::where('key', $key)->delete();
            } else {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        });
        $this->writeToConfig();
        return $this;
    }

    /**
     * Parse the setting fields defined in config/settings.php
     * @return Collection
     */
    protected function getDefaultSettings() {
        return collect(config('settings.fields'))->map(function ($setting) {
            if (!isset($setting['default'])) $setting['default'] = '';
            if (is_string($setting['default'])) {
                $setting['default'] = trans($setting['default']);
            }
            $setting['value'] = $setting['default'];

            if (isset($setting['hint'])) {
                $setting['hint'] = trans($setting['hint']);
            }

            $setting['disk'] = 'public';

            return $setting;
        });
    }

    /**
     * Bind the settings to the Laravel config, so you can call them like
     * Config::get('settings.contact_email')
     */
    protected function writeToConfig() {
        $configOverrides = [];
        $this->settings->each(function ($setting, $key) use (&$configOverrides) {
            Config::set('settings.' . $key, $setting['value']);

            // If specified, also override a given Laravel configuration
            if (isset($setting['override'])) {
                $configOverrides[$setting['override']] = $setting['value'];
            }
        });
        config($configOverrides);
    }

    /**
     * Handle dynamic method calls into the object.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters) {
        return $this->forwardCallTo($this->settings, $method, $parameters);
    }
}
