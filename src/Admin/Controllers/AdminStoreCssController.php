<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminStoreCss;
use Vncore\Core\Admin\Models\AdminTemplate;
use Vncore\Core\Admin\Models\AdminStore;
class AdminStoreCssController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Form edit
     */
    public function index()
    {
        $storeId = request('store_id', session('adminStoreId'));
        $store     = AdminStore::find($storeId);
        $templates = (new AdminTemplate)->getListTemplate();
        $template = $store->template;
        if (!key_exists($template, $templates)) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $cssContent = AdminStoreCss::where('store_id', $storeId)
            ->where('template', $template)
            ->first();

        if (!$cssContent) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $data = [
            'title' => vncore_language_render('store.admin.css').' #'.$storeId,
            'subTitle' => '',
            'title_description' => '',
            'template' => $template,
            'templates' => $templates,
            'storeId' => $storeId,
            'icon' => 'fa fa-edit',
            'css' => $cssContent->css,
            'url_action' => vncore_route_admin('admin_store_css.index'),
        ];
        return view($this->vncore_templatePathAdmin.'screen.store_css')
            ->with($data);
    }
    
    /**
     * update css template
     */
    public function postEdit()
    {
        $data = request()->all();
        $storeId = $data['storeId'];
        $template = $data['template'];
        $cssContent = AdminStoreCss::where('store_id', $storeId)->where('template', $template)->first();
        $cssContent->css = request('css');
        $cssContent->save();
        return redirect()->route('admin_store_css.index', ['store_id' => $storeId])->with('success', vncore_language_render('action.edit_success'));
    }
}
