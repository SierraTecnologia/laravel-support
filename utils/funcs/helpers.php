<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

if (! function_exists('extract_title')) {
    /**
     * Extract page title from breadcrumbs.
     *
     * @return string
     */
    function extract_title(HtmlString $breadcrumbs, string $separator = ' » ')
    {
        return strip_tags(str_replace_last($separator, '', str_replace('</li>', $separator, $breadcrumbs)));
    }
}

if (! function_exists('domain')) {
    /**
     * Return domain host.
     *
     * @return string
     */
    function domain()
    {
        return parse_url(\Illuminate\Support\Facades\Config::get('app.url'))['host'];
    }
}

if (! function_exists('intend')) {
    /**
     * Return redirect response.
     *
     * @param array $arguments
     * @param int   $status
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    function intend(array $arguments, int $status = 302)
    {
        $redirect = redirect(array_pull($arguments, 'url'), $status);

        if (request()->expectsJson()) {
            $response = collect($arguments['withErrors'] ?? $arguments['with']);

            return response()->json([$response->flatten()->first() ?? 'OK']);
        }

        foreach ($arguments as $key => $value) {
            $redirect = in_array($key, ['home', 'back']) ? $redirect->{$key}() : $redirect->{$key}($value);
        }

        return $redirect;
    }
}

if (! function_exists('lower_case')) {
    /**
     * Convert the given string to lower-case.
     *
     * @param string $value
     *
     * @return string
     */
    function lower_case($value)
    {
        return Str::lower($value);
    }
}

if (! function_exists('upper_case')) {
    /**
     * Convert the given string to upper-case.
     *
     * @param string $value
     *
     * @return string
     */
    function upper_case($value)
    {
        return Str::upper($value);
    }
}

if (! function_exists('mimetypes')) {
    /**
     * Get valid mime types.
     *
     * @see https://github.com/symfony/http-foundation/blob/3.0/File/MimeType/MimeTypeExtensionGuesser.php
     * @see http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
     *
     * @return array
     */
    function mimetypes()
    {
        return json_decode(file_get_contents(__DIR__.'/../../resources/data/mimetypes.json'), true);
    }
}

if (! function_exists('timezones')) {
    /**
     * Get valid timezones.
     * This list is based upon the timezone database version 2017.2.
     *
     * @see http://php.net/manual/en/timezones.php
     *
     * @return array
     */
    function timezones()
    {
        return json_decode(file_get_contents(__DIR__.'/../../resources/data/timezones.json'), true);
    }
}

if (! function_exists('array_search_recursive')) {
    /**
     * Recursively searches the array for a given value and returns the corresponding key if successful.
     *
     * @param mixed $needle
     * @param array $haystack
     *
     * @return mixed
     */
    function array_search_recursive($needle, $haystack)
    {
        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if ($needle === $value || (is_array($value) && array_search_recursive($needle, $value) !== false)) {
                return $current_key;
            }
        }

        return false;
    }
}

if (! function_exists('array_trim_recursive')) {
    /**
     * Recursively trim elements of the given array.
     *
     * @param mixed  $values
     * @param string $charlist
     *
     * @return mixed
     */
    function array_trim_recursive($values, $charlist = " \t\n\r\0\x0B")
    {
        if (is_array($values)) {
            return array_map('array_trim_recursive', $values);
        }

        return is_string($values) ? trim($values, $charlist) : $values;
    }
}

if (! function_exists('array_filter_recursive')) {
    /**
     * Recursively filter empty strings and null elements of the given array.
     *
     * @param array $values
     * @param bool  $strOnly
     *
     * @return mixed
     */
    function array_filter_recursive($values, $strOnly = true)
    {
        foreach ($values as &$value) {
            if (is_array($value)) {
                $value = array_filter_recursive($value);
            }
        }

        return ! $strOnly ? array_filter($values) : array_filter(
            $values, function ($item) {
                return ! is_null($item) && ! ((is_string($item) || is_array($item)) && empty($item));
            }
        );
    }
}

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

if (!function_exists('active_class')) {
    /**
     * Get the active class if the condition is not falsy
     *
     * @param        $condition
     * @param string $activeClass
     * @param string $inactiveClass
     *
     * @return string
     */
    function active_class($condition, $activeClass = 'active', $inactiveClass = '')
    {
        return app('active')->getClassIf($condition, $activeClass, $inactiveClass);
    }
}

if (!function_exists('if_uri')) {
    /**
     * Check if the URI of the current request matches one of the specific URIs
     *
     * @param array|string $uris
     *
     * @return bool
     */
    function if_uri($uris)
    {
        return app('active')->checkUri($uris);
    }
}

if (!function_exists('if_uri_pattern')) {
    /**
     * Check if the current URI matches one of specific patterns (using `Str::is`)
     *
     * @param array|string $patterns
     *
     * @return bool
     */
    function if_uri_pattern($patterns)
    {
        return app('active')->checkUriPattern($patterns);
    }
}

if (!function_exists('if_query')) {
    /**
     * Check if one of the following condition is true:
     * + the value of $value is `false` and the current querystring contain the key $key
     * + the value of $value is not `false` and the current value of the $key key in the querystring equals to $value
     * + the value of $value is not `false` and the current value of the $key key in the querystring is an array that
     * contains the $value
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    function if_query($key, $value)
    {
        return app('active')->checkQuery($key, $value);
    }
}

if (!function_exists('if_route')) {
    /**
     * Check if the name of the current route matches one of specific values
     *
     * @param array|string $routeNames
     *
     * @return bool
     */
    function if_route($routeNames)
    {
        return app('active')->checkRoute($routeNames);
    }
}

if (!function_exists('if_route_pattern')) {
    /**
     * Check the current route name with one or some patterns
     *
     * @param array|string $patterns
     *
     * @return bool
     */
    function if_route_pattern($patterns)
    {
        return app('active')->checkRoutePattern($patterns);
    }
}

if (!function_exists('if_route_param')) {
    /**
     * Check if the parameter of the current route has the correct value
     *
     * @param $param
     * @param $value
     *
     * @return bool
     */
    function if_route_param($param, $value)
    {
        return app('active')->checkRouteParam($param, $value);
    }
}

if (!function_exists('if_action')) {
    /**
     * Return 'active' class if current route action match one of provided action names
     *
     * @param array|string $actions
     *
     * @return bool
     */
    function if_action($actions)
    {
        return app('active')->checkAction($actions);
    }
}

if (!function_exists('if_controller')) {
    /**
     * Check if the current controller class matches one of specific values
     *
     * @param array|string $controllers
     *
     * @return bool
     */
    function if_controller($controllers)
    {
        return app('active')->checkController($controllers);
    }
}

if (!function_exists('current_controller')) {
    /**
     * Get the current controller class
     *
     * @return string
     */
    function current_controller()
    {
        return app('active')->getController();
    }
}

if (!function_exists('current_method')) {
    /**
     * Get the current controller method
     *
     * @return string
     */
    function current_method()
    {
        return app('active')->getMethod();
    }
}

if (!function_exists('current_action')) {
    /**
     * Get the current action string
     *
     * @return string
     */
    function current_action()
    {
        return app('active')->getAction();
    }
}


/**
 * Get a unique string (based on cryptographically secure pseudo-random function).
 *
 * @param  int    $length
 * @param  string $alphabet
 * @return string
 * @throws InvalidArgumentException
 * @throws Exception
 */
function str_unique(int $length = 40, string $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'): string
{
    if ($length < 1) {
        throw new InvalidArgumentException('Length parameter value should be greater than or equals to 1.');
    }

    $str = '';

    $alphabetLength = \Illuminate\Support\Str::length($alphabet);

    for ($i = 0; $i < $length; $i++) {
        $str .= $alphabet[random_int(0, $alphabetLength - 1)];
    }

    return $str;
}

/**
 * Remove all malicious code from HTML string.
 *
 * @param  mixed $html
 * @return string|null
 */
function html_purify($html): ?string
{
    $value = value($html);

    if (is_null($value)) {
        return null;
    }

    return app()->make('HTMLPurifier')->purify($value);
}

/**
 * Remove keys that missing in the schema array.
 *
 * @example array_filter_structure(['key_0' => 'value', 'key_1' => 'value'], ['key_0']); // ['key_0' => 'value']
 *
 * @param  array $array
 * @param  array $schema
 * @return array
 */
function array_filter_schema(array $array, array $schema): array
{
    $result = [];

    foreach ($array as $key => $value) {
        if (array_key_exists($key, $schema)) {
            $result[$key] = is_array($value) && is_array($schema[$key]) ? array_filter_schema($value, $schema[$key]) : $value;
        } elseif (is_int($key) && array_key_exists('*', $schema)) {
            $result[$key] = is_array($value) && is_array($schema['*']) ? array_filter_schema($value, $schema['*']) : $value;
        } elseif (in_array($key, $schema, true)) {
            $result[$key] = $value;
        }
    }

    return $result;
}

/**
 * Convert a single-dimensional array with keys in "dot" notation into a multi-dimensional array.
 *
 * @example array_remove_dot_notation(['key_0.key_1' => 'value']); // ['key_0' => ['key_1' => 'value']]
 *
 * @param  array $array
 * @return array
 */
function array_remove_dot_notation(array $array): array
{
    $result = [];

    foreach ($array as $key => $value) {
        array_set($result, $key, $value);
    }

    return $result;
}

/**
 * Remove attributes that have no validation rules.
 *
 * @param  array $attributes
 * @param  array $rules
 * @return array
 */
function validator_filter_attributes(array $attributes, array $rules): array
{
    return array_filter_schema($attributes, array_remove_dot_notation(array_flip(array_keys($rules))));
}