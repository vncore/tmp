<?php
namespace Vncore\Core\Admin\Controllers;

use App\Http\Controllers\Controller;

class RootAdminController extends Controller
{
    public $vc_templatePathAdmin;
    public function __construct()
    {
        $this->vc_templatePathAdmin = config('admin.path_view');
    }

}
