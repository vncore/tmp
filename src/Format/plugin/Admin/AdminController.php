<?php
#App\Vncore\Plugins\Plugin_Key\Admin\AdminController.php

namespace Vncore\Plugins\Plugin_Key\Admin;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Plugins\Plugin_Key\AppConfig;

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
