<?php
use Vncore\Core\Admin\Models\AdminConfig;
use Vncore\Core\Admin\Models\AdminStore;
use Vncore\Core\Front\Models\ShopStoreBlockContent;
use Vncore\Core\Front\Models\ShopLink;
use Vncore\Core\Front\Models\ShopStoreCss;
use Illuminate\Support\Arr;

if (!function_exists('sc_admin_can_config')) {
    /**
     * Check user can change config value
     *
     * @return  [type]          [return description]
     */
    function sc_admin_can_config()
    {
        return \Vncore\Core\Admin\Admin::user()->checkPermissionConfig();
    }
}

if (!function_exists('sc_config') && !in_array('sc_config', config('helper_except', []))) {
    /**
     * Get value config from table sc_config
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [string|array]  $key      [$key description]
     * @param   [int|null]  $storeId  [$storeId description]
     * @param   [string|null]  $default  [$default description]
     *
     * @return  [type]            [return description]
     */
    function sc_config($key = "", $storeId = null, $default = null)
    {
        $storeId = ($storeId === null) ? config('app.storeId') : $storeId;
        if (!is_string($key)) {
            return;
        }

        $allConfig = AdminConfig::getAllConfigOfStore($storeId);

        if ($key === "") {
            return $allConfig;
        }
        return array_key_exists($key, $allConfig) ? $allConfig[$key] : 
            (array_key_exists($key, sc_config_global()) ? sc_config_global()[$key] : $default);
    }
}


if (!function_exists('sc_config_admin') && !in_array('sc_config_admin', config('helper_except', []))) {
    /**
     * Get config value in adin with session store id
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [type]$key  [$key description]
     * @param   null        [ description]
     *
     * @return  [type]      [return description]
     */
    function sc_config_admin($key = "", $default = null)
    {
        return sc_config($key, session('adminStoreId'), $default);
    }
}


if (!function_exists('sc_config_global') && !in_array('sc_config_global', config('helper_except', []))) {
    /**
     * Get value config from table sc_config for store_id 0
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [string|array] $key      [$key description]
     * @param   [string|null]  $default  [$default description]
     *
     * @return  [type]          [return description]
     */
    function sc_config_global($key = "", $default = null)
    {
        if (!is_string($key)) {
            return;
        }
        $allConfig = [];
        try {
            $allConfig = AdminConfig::getAllGlobal();
        } catch (\Throwable $e) {
            //
        }
        if ($key === "") {
            return $allConfig;
        }
        if (!array_key_exists($key, $allConfig)) {
            return $default;
        } else {
            return trim($allConfig[$key]);
        }
    }
}

if (!function_exists('sc_config_group') && !in_array('sc_config_group', config('helper_except', []))) {
    /*
    Group Config info
     */
    function sc_config_group($group = null, $suffix = null)
    {
        $groupData = AdminConfig::getGroup($group, $suffix);
        return $groupData;
    }
}


if (!function_exists('sc_store') && !in_array('sc_store', config('helper_except', []))) {
    /**
     * Get info store_id, table admin_store
     *
     * @param   [string] $key      [$key description]
     * @param   [null|int]  $store_id    store id
     *
     * @return  [mix]
     */
    function sc_store($key = null, $store_id = null, $default = null)
    {
        $store_id = ($store_id == null) ? config('app.storeId') : $store_id;

        //Update store info
        if (is_array($key)) {
            if (count($key) == 1) {
                foreach ($key as $k => $v) {
                    return AdminStore::where('id', $store_id)->update([$k => $v]);
                }
            } else {
                return false;
            }
        }
        //End update

        $allStoreInfo = [];
        try {
            $allStoreInfo = AdminStore::getListAll()[$store_id]->toArray() ?? [];
        } catch (\Throwable $e) {
            //
        }

        $lang = app()->getLocale();
        $descriptions = $allStoreInfo['descriptions'] ?? [];
        foreach ($descriptions as $row) {
            if ($lang == $row['lang']) {
                $allStoreInfo += $row;
            }
        }
        if ($key == null) {
            return $allStoreInfo;
        }
        return $allStoreInfo[$key] ?? $default;
    }
}

if (!function_exists('sc_store_active') && !in_array('sc_store_active', config('helper_except', []))) {
    function sc_store_active($field = null)
    {
        switch ($field) {
            case 'code':
                return AdminStore::getCodeActive();
                break;

            case 'domain':
                return AdminStore::getStoreActive();
                break;

            default:
                return AdminStore::getListAllActive();
                break;
        }
    }
}


/*
Get all layouts
 */
if (!function_exists('sc_store_block') && !in_array('sc_store_block', config('helper_except', []))) {
    function sc_store_block()
    {
        return ShopStoreBlockContent::getLayout();
    }
}

/**
 * Get css template
 */
if (!function_exists('sc_store_css')) {
    function sc_store_css()
    {
        $template = sc_store('template', config('app.storeId'));
        if (\Schema::connection(SC_CONNECTION)->hasColumn((new ShopStoreCss)->getTable(), 'template')) {
            $cssStore =  ShopStoreCss::where('store_id', config('app.storeId'))
            ->where('template', $template)->first();
        } else {
            $cssStore =  ShopStoreCss::where('store_id', config('app.storeId'))->first();
        }
        if ($cssStore) {
            return $cssStore->css;
        }
    }
}



if (!function_exists('sc_link') && !in_array('sc_link', config('helper_except', []))) {
    function sc_link()
    {
        return ShopLink::getGroup();
    }
}


if (!function_exists('sc_link_collection') && !in_array('sc_link_collection', config('helper_except', []))) {
    function sc_link_collection()
    {
        return ShopLink::getLinksCollection();
    }
}


if (!function_exists('sc_get_all_template') && !in_array('sc_get_all_template', config('helper_except', []))) {
    /*
    Get all template
    */
    function sc_get_all_template():array
    {
        $arrTemplates = [];
        foreach (glob(resource_path() . "/views/templates/*") as $template) {
            if (is_dir($template)) {
                $infoTemlate['code'] = explode('templates/', $template)[1];
                $config = ['name' => '', 'auth' => '', 'email' => '', 'website' => ''];
                if (file_exists($template . '/config.json')) {
                    $config = json_decode(file_get_contents($template . '/config.json'), true);
                }
                $infoTemlate['config'] = $config;
                $arrTemplates[$infoTemlate['code']] = $infoTemlate;
            }
        }
        return $arrTemplates;
    }
}


if (!function_exists('sc_route') && !in_array('sc_route', config('helper_except', []))) {
    /**
     * Render route
     *
     * @param   [string]  $name
     * @param   [array]  $param
     *
     * @return  [type]         [return description]
     */
    function sc_route($name, $param = [])
    {
        if (!config('app.seoLang')) {
            $param = Arr::except($param, ['lang']);
        } else {
            $arrRouteExcludeLanguage = ['home','locale', 'currency', 'banner.click'];
            if (!key_exists('lang', $param) && !in_array($name, $arrRouteExcludeLanguage)) {
                $param['lang'] = app()->getLocale();
            }
        }
        
        if (Route::has($name)) {
            try {
                $route = route($name, $param);
            } catch (\Throwable $th) {
                $route = url('#'.$name.'#'.implode(',', $param));
            }
            return $route;
        } else {
            return url('#'.$name);
        }
    }
}


if (!function_exists('sc_route_admin') && !in_array('sc_route_admin', config('helper_except', []))) {
    /**
     * Render route admin
     *
     * @param   [string]  $name
     * @param   [array]  $param
     *
     * @return  [type]         [return description]
     */
    function sc_route_admin($name, $param = [])
    {
        if (Route::has($name)) {
            try {
                $route = route($name, $param);
            } catch (\Throwable $th) {
                $route = url('#'.$name.'#'.implode(',', $param));
            }
            return $route;
        } else {
            return url('#'.$name);
        }
    }
}

if (!function_exists('sc_uuid') && !in_array('sc_uuid', config('helper_except', []))) {
    /**
     * Generate UUID
     *
     * @param   [string]  $name
     * @param   [array]  $param
     *
     * @return  [type]         [return description]
     */
    function sc_uuid()
    {
        return (string)\Illuminate\Support\Str::orderedUuid();
    }
}

if (!function_exists('sc_generate_id') && !in_array('sc_generate_id', config('helper_except', []))) {
    /**
     * Generate ID
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function sc_generate_id($type = null)
    {
        switch ($type) {
            case 'shop_store':
                return 'S-'.sc_token(5).'-'.sc_token(5);
                break;
            case 'shop_order':
                return 'O-'.sc_token(5).'-'.sc_token(5);
                break;
            case 'admin_user':
                return 'AU-'.sc_token(5).'-'.sc_token(5);
                break;
            
            default:
                return sc_uuid();
                break;
        }
    }
}


if (!function_exists('sc_config_update') && !in_array('sc_config_update', config('helper_except', []))) {

    /**
     * Update key config
     *
     * @param   array  $dataUpdate  [$dataUpdate description]
     * @param   [type] $storeId     [$storeId description]
     *
     * @return  [type]              [return description]
     */
    function sc_config_update($dataUpdate = null, $storeId = null)
    {
        $storeId = ($storeId === null) ? config('app.storeId') : $storeId;
        //Update config
        if (is_array($dataUpdate)) {
            if (count($dataUpdate) == 1) {
                foreach ($dataUpdate as $k => $v) {
                    return AdminConfig::where('store_id', $storeId)
                        ->where('key', $k)
                        ->update(['value' => $v]);
                }
            } else {
                return false;
            }
        }
        //End update
    }
}

if (!function_exists('sc_config_exist') && !in_array('sc_config_exist', config('helper_except', []))) {

    /**
     * Check key config exist
     *
     * @param   [type]  $key      [$key description]
     * @param   [type]  $storeId  [$storeId description]
     *
     * @return  [type]            [return description]
     */
    function sc_config_exist($key = "", $storeId = null)
    {
        if(!is_string($key)) {
            return false;
        }
        $storeId = ($storeId === null) ? config('app.storeId') : $storeId;
        $checkConfig = AdminConfig::where('store_id', $storeId)->where('key', $key)->first();
        if ($checkConfig) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('sc_config_global_update') && !in_array('sc_config_global_update', config('helper_except', []))) {
    /**
     * [sc_config_global_update description]
     *
     * @param   [type]  $arrayData  [$arrayData description]
     *
     * @return  []                  [return description]
     */
    function sc_config_global_update($arrayData = [])
    {
        //Update config
        if (is_array($arrayData)) {
            if (count($arrayData) == 1) {
                foreach ($arrayData as $k => $v) {
                    return AdminConfig::where('store_id', SC_ID_GLOBAL)
                        ->where('key', $k)
                        ->update(['value' => $v]);
                }
            } else {
                return false;
            }
        } else {
            return;
        }
        //End update
    }
}