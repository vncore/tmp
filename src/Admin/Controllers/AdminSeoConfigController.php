<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;

class AdminSeoConfigController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $data = [
            'title'    => sc_language_render('admin.seo.config'),
            'subTitle' => '',
            'icon'     => 'fa fa-indent',
        ];
        $data['urlUpdateConfigGlobal'] = sc_route_admin('admin_config_global.update');
        return view($this->templatePathAdmin.'screen.seo_config')
            ->with($data);
    }
}
