<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminConfig;

class AdminCacheConfigController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'title' => vncore_language_render('admin.cache.title'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',        ];
        $configs = AdminConfig::getListConfigByCode(['code' => 'cache']);
        $data['configs'] = $configs;
        $data['urlUpdateConfigGlobal'] = sc_route_admin('admin_config_global.update');
        return view($this->templatePathAdmin.'screen.cache_config')
            ->with($data);
    }

    /**
     * Clear cache
     *
     * @return  json
     */
    public function clearCache()
    {
        $action = request('action');
        $response = vncore_cache_clear($action);
        return response()->json(
            $response
        );
    }
}
