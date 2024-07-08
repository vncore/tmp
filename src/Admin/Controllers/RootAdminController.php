<?php
namespace Vncore\Core\Admin\Controllers;

use App\Http\Controllers\Controller;

class RootAdminController extends Controller
{
    public $vncore_templatePathAdmin;
    public function __construct()
    {
        $this->vncore_templatePathAdmin = config('admin.path_view');
    }

}
