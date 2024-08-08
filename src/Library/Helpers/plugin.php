<?php

if (!function_exists('vncore_get_all_plugin') && !in_array('vncore_get_all_plugin', config('vncore_functions_except', []))) {
    /**
     * Get all class plugin
     *
     * @param   [string]  $code  Payment, Shipping
     *
     * @return  [array]
     */
    function vncore_get_all_plugin()
    {
        $arrClass = [];
        $dirs = array_filter(glob(app_path() . '/Vncore/Plugins/*'), 'is_dir');
        if ($dirs) {
            foreach ($dirs as $dir) {
                $tmp = explode('/', $dir);
                $nameSpace = '\App\Vncore\Plugins\\' . end($tmp);
                if (file_exists($dir . '/AppConfig.php')) {
                    $arrClass[end($tmp)] = $nameSpace;
                }
            }
        }
        return $arrClass;
    }
}

if (!function_exists('vncore_get_plugin_installed') && !in_array('vncore_get_plugin_installed', config('vncore_functions_except', []))) {
    /**
     * Get all class plugin
     *
     * @param   [string]  $code  Payment, Shipping
     *
     */
    function vncore_get_plugin_installed($onlyActive = true)
    {
        return \Vncore\Core\Admin\Models\AdminConfig::getPluginCode($onlyActive);
    }
}




if (!function_exists('vncore_get_all_plugin_actived') && !in_array('vncore_get_all_plugin_actived', config('vncore_functions_except', []))) {
    /**
     * Get all class plugin actived
     *
     * @param   [string]  $code  Payment, Shipping
     *
     * @return  [array]
     */
    function vncore_get_all_plugin_actived(string $code)
    {
        $code = vncore_word_format_class($code);
        
        $pluginsActived = [];
        $allPlugins = vncore_get_all_plugin($code);
        if (count($allPlugins)) {
            foreach ($allPlugins as $keyPlugin => $plugin) {
                if (vncore_config($keyPlugin) == 1) {
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

    if (!function_exists('vncore_get_class_plugin_controller') && !in_array('vncore_get_class_plugin_controller', config('vncore_functions_except', []))) {
        function vncore_get_class_plugin_controller(string $key = "")
        {
            if (!$key) {
                return null;
            }
            
            $key = vncore_word_format_class($key);

            $nameSpace = vncore_get_plugin_namespace($key);
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
    if (!function_exists('vncore_get_class_plugin_config') && !in_array('vncore_get_class_plugin_config', config('vncore_functions_except', []))) {
        function vncore_get_class_plugin_config(string $key = "")
        {
            $key = vncore_word_format_class($key);

            $nameSpace = vncore_get_plugin_namespace($key);
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
    if (!function_exists('vncore_get_plugin_namespace') && !in_array('vncore_get_plugin_namespace', config('vncore_functions_except', []))) {
        function vncore_get_plugin_namespace(string $key = "")
        {
            $key = vncore_word_format_class($key);
            $nameSpace = '\App\Vncore\Plugins\\' . $key;
            return $nameSpace;
        }
    }

    /**
     * Check plugin and template compatibility with Vncore version
     *
     * @param   string  $versionsConfig  [$versionsConfig description]
     *
     * @return  [type]                   [return description]
     */
    if (!function_exists('vncore_plugin_compatibility_check') && !in_array('vncore_plugin_compatibility_check', config('vncore_functions_except', []))) {
        function vncore_plugin_compatibility_check(string $versionsConfig) {
            $arrVersionVncore = explode('|', $versionsConfig);
            return in_array(config('vncore.core'), $arrVersionVncore);
        }
    }