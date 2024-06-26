<?php
#App\Plugins\Plugin_Code\Plugin_Key\Admin\AdminController.php

namespace App\Plugins\Plugin_Code\Plugin_Key\Admin;

use Vncore\Core\Admin\Controllers\RootAdminController;
use App\Plugins\Plugin_Code\Plugin_Key\AppConfig;

class AdminController extends RootAdminController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }
    public function index()
    {
        return view($this->plugin->pathPlugin.'::Admin',
            [
                
            ]
        );
    }
}
