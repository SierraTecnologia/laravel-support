<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return Facilitador\Facades\Facilitador::setting($key, $default);
    }
}

if (!function_exists('menu')) {
    function menu($menuName, $type = null, array $options = [])
    {
        return Facilitador\Facades\Facilitador::model('Menu')->display($menuName, $type, $options);
    }
}

if (!function_exists('facilitador_asset')) {
    function facilitador_asset($path, $secure = null)
    {
        return route('facilitador.facilitador_assets').'?path='.urlencode($path);
    }
}

if (!function_exists('get_file_name')) {
    function get_file_name($name)
    {
        preg_match('/(_)([0-9])+$/', $name, $matches);
        if (count($matches) == 3) {
            return Illuminate\Support\Str::replaceLast($matches[0], '', $name).'_'.(intval($matches[2]) + 1);
        } else {
            return $name.'_1';
        }
    }
}
