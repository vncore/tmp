<?php

use Vncore\Core\Front\Models\ShopLanguage;
use Illuminate\Support\Str;

if (!function_exists('vc_language_all') && !in_array('vc_language_all', config('helper_except', []))) {
    //Get all language
    function vc_language_all()
    {
        return ShopLanguage::getListActive();
    }
}

if (!function_exists('vc_languages') && !in_array('vc_languages', config('helper_except', []))) {
    /*
    Render language
    WARNING: Dont call this function (or functions that call it) in __construct or midleware, it may cause the display language to be incorrect
     */
    function vc_languages($locale)
    {
        $languages = \Vncore\Core\Front\Models\Languages::getListAll($locale);
        return $languages;
    }
}

if (!function_exists('vc_language_replace') && !in_array('vc_language_replace', config('helper_except', []))) {
    /*
    Replace language
     */
    function vc_language_replace(string $line, array $replace)
    {
        foreach ($replace as $key => $value) {
            $line = str_replace(
                [':'.$key, ':'.Str::upper($key), ':'.Str::ucfirst($key)],
                [$value, Str::upper($value), Str::ucfirst($value)],
                $line
            );
        }
        return $line;
    }
}


if (!function_exists('vc_language_render') && !in_array('vc_language_render', config('helper_except', []))) {
    /*
    Render language
    WARNING: Dont call this function (or functions that call it) in __construct or midleware, it may cause the display language to be incorrect
     */
    function vc_language_render($string, array $replace = [], $locale = null)
    {
        if (!is_string($string)) {
            return null;
        }
        $locale = $locale ? $locale : vc_get_locale();
        $languages = vc_languages($locale);
        return !empty($languages[$string]) ? vc_language_replace($languages[$string], $replace): trans($string, $replace);
    }
}


if (!function_exists('vc_language_quickly') && !in_array('vc_language_quickly', config('helper_except', []))) {
    /*
    Language quickly
     */
    function vc_language_quickly($string, $default = null)
    {
        $locale = vc_get_locale();
        $languages = vc_languages($locale);
        return !empty($languages[$string]) ? $languages[$string] : (\Lang::has($string) ? trans($string) : $default);
    }
}

if (!function_exists('vc_get_locale') && !in_array('vc_get_locale', config('helper_except', []))) {
    /*
    Get locale
    */
    function vc_get_locale()
    {
        return app()->getLocale();
    }
}


if (!function_exists('vc_lang_switch') && !in_array('vc_lang_switch', config('helper_except', []))) {
    /**
     * Switch language
     *
     * @param   [string]  $lang
     *
     * @return  [mix]
     */
    function vc_lang_switch($lang = null)
    {
        if (!$lang) {
            return ;
        }

        $languages = vc_language_all()->keys()->all();
        if (in_array($lang, $languages)) {
            app()->setLocale($lang);
            session(['locale' => $lang]);
        } else {
            return abort(404);
        }
    }
}
