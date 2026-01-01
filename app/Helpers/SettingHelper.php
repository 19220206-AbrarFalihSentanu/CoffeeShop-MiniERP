<?php

if (!function_exists('setting')) {
    /**
     * Get setting value by key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set setting value
     * 
     * @param string $key
     * @param mixed $value
     * @return \App\Models\Setting
     */
    function set_setting($key, $value)
    {
        return \App\Models\Setting::set($key, $value);
    }
}
