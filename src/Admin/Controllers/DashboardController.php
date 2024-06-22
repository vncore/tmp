<?php

namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Illuminate\Http\Request;

class DashboardController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Request $request)
    {
        //Check user allow view dasdboard
        if (!\Admin::user()->checkUrlAllowAccess(route('admin.home'))) {
            $data['title'] = vc_language_render('admin.dashboard');
            return view($this->vc_templatePathAdmin.'default', $data);
        }

        $data                   = [];
        $data['title']          = vc_language_render('admin.dashboard');
        return view($this->vc_templatePathAdmin.'dashboard', $data);
    }

    /**
     * Page not found
     *
     * @return  [type]  [return description]
     */
    public function dataNotFound()
    {
        $data = [
            'title' => vc_language_render('admin.data_not_found'),
            'icon' => '',
            'url' => session('url'),
        ];
        return view($this->vc_templatePathAdmin.'data_not_found', $data);
    }


    /**
     * Page deny
     *
     * @return  [type]  [return description]
     */
    public function deny()
    {
        $data = [
            'title' => vc_language_render('admin.deny'),
            'icon' => '',
            'method' => session('method'),
            'url' => session('url'),
        ];
        return view($this->vc_templatePathAdmin.'deny', $data);
    }

    /**
     * [denySingle description]
     *
     * @return  [type]  [return description]
     */
    public function denySingle()
    {
        $data = [
            'method' => session('method'),
            'url' => session('url'),
        ];
        return view($this->vc_templatePathAdmin.'deny_single', $data);
    }
}
