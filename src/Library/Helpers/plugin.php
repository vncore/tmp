<?php

if (!function_exists('vc_get_all_plugin') && !in_array('vc_get_all_plugin', config('helper_except', []))) {
    /**
     * Get all class plugin
     *
     * @param   [string]  $code  Payment, Shipping
     *
     * @return  [array]
     */
    function vc_get_all_plugin()
    {
        $arrClass = [];
        $dirs = array_filter(glob(app_path() . '/Plugins/*'), 'is_dir');
        if ($dirs) {
            foreach ($dirs as $dir) {
                $tmp = explode('/', $dir);
                $nameSpace = '\App\Plugins\\' . end($tmp);
                if (file_exists($dir . '/AppConfig.php')) {
                    $arrClass[end($tmp)] = $nameSpace;
                }
            }
        }
        return $arrClass;
    }
}

if (!function_exists('vc_get_plugin_installed') && !in_array('vc_get_plugin_installed', config('helper_except', []))) {
    /**
     * Get all class plugin
     *
     * @param   [string]  $code  Payment, Shipping
     *
     */
    function vc_get_plugin_installed($onlyActive = true)
    {
        return \Vncore\Core\Admin\Models\AdminConfig::getPluginCode($onlyActive);
    }
}




if (!function_exists('vc_get_all_plugin_actived') && !in_array('vc_get_all_plugin_actived', config('helper_except', []))) {
    /**
     * Get all class plugin actived
     *
     * @param   [string]  $code  Payment, Shipping
     *
     * @return  [array]
     */
    function vc_get_all_plugin_actived(string $code)
    {
        $code = vc_word_format_class($code);
        
        $pluginsActived = [];
        $allPlugins = vc_get_all_plugin($code);
        if (count($allPlugins)) {
            foreach ($allPlugins as $keyPlugin => $plugin) {
                if (vc_config($keyPlugin) == 1) {
                    $pluginsActived[$keyPlugin] = $plugin;
                }
            }
        }
        return $pluginsActived;
    }
}


    /**
     * Get namespace plugin controller
     *
     * @param   [string]  $code  Shipping, Payment,..
     * @param   [string]  $key  Paypal,..
     *
     * @return  [array]
     */

    if (!function_exists('vc_get_class_plugin_controller') && !in_array('vc_get_class_plugin_controller', config('helper_except', []))) {
        function vc_get_class_plugin_controller(string $key = "")
        {
            if (!$key) {
                return null;
            }
            
            $key = vc_word_format_class($key);

            $nameSpace = vc_get_plugin_namespace($key);
            $nameSpace = $nameSpace . '\Controllers\FrontController';

            return $nameSpace;
        }
    }


    /**
     * Get namespace plugin config
     *
     * @param   [string]  $key  Paypal,..
     *
     * @return  [array]
     */
    if (!function_exists('vc_get_class_plugin_config') && !in_array('vc_get_class_plugin_config', config('helper_except', []))) {
        function vc_get_class_plugin_config(string $key = "")
        {
            $key = vc_word_format_class($key);

            $nameSpace = vc_get_plugin_namespace($key);
            $nameSpace = $nameSpace . '\AppConfig';

            return $nameSpace;
        }
    }

    /**
     * Get namespace module
     *
     * @param   [string]  $code  Block, Cms, Payment, shipping..
     * @param   [string]  $key  Content,Paypal, Cash..
     *
     * @return  [array]
     */
    if (!function_exists('vc_get_plugin_namespace') && !in_array('vc_get_plugin_namespace', config('helper_except', []))) {
        function vc_get_plugin_namespace(string $key = "")
        {
            $key = vc_word_format_class($key);
            $nameSpace = '\App\Plugins\\' . $key;
            return $nameSpace;
        }
    }

    /**
     * Check plugin and template compatibility with S-cart version
     *
     * @param   string  $versionsConfig  [$versionsConfig description]
     *
     * @return  [type]                   [return description]
     */
    if (!function_exists('vc_plugin_compatibility_check') && !in_array('vc_plugin_compatibility_check', config('helper_except', []))) {
        function vc_plugin_compatibility_check(string $versionsConfig) {
            $arrVersionSCart = explode('|', $versionsConfig);
            return in_array(config('vncore.core'), $arrVersionSCart);
        }
    }