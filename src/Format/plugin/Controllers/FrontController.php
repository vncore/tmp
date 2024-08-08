<?php
#App\Vncore\Plugins\Plugin_Key\Controllers\FrontController.php
namespace Vncore\Plugins\Plugin_Key\Controllers;

use Vncore\Plugins\Plugin_Key\AppConfig;
use Vncore\Core\Front\Controllers\RootFrontController;
class FrontController extends RootFrontController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }

    public function index() {
        return view($this->plugin->pathPlugin.'::Front',
            [
                //
            ]
        );
    }

    public function processOrder(){
        // Function require if plugin is payment method
    }
}
