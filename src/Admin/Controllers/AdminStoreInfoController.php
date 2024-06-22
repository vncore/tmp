<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminStore;
use Vncore\Core\Admin\Models\AdminTemplate;
use Vncore\Core\Front\Models\ShopLanguage;

class AdminStoreInfoController extends RootAdminController
{
    public $templates;
    public $languages;

    public function __construct()
    {
        parent::__construct();
        $this->templates = (new AdminTemplate)->getListTemplateActive();
        $this->languages = ShopLanguage::getListActive();
    }

    /*
    Update value config
    */
    public function updateInfo()
    {
        $data      = request()->all();
        $data = vc_clean($data, [], true);
        $storeId   = $data['storeId'];
        $fieldName = $data['name'];
        $value     = $data['value'];
        $parseName = explode('__', $fieldName);
        $name      = $parseName[0];
        $lang      = $parseName[1] ?? '';
        $msg       = 'Update success';
        // Check store
        $store     = AdminStore::find($storeId);
        if (!$store) {
            return response()->json(['error' => 1, 'msg' => 'Store not found!']);
        }

        if (!$lang) {
            try {
                if ($name == 'type') {
                    // Can not change type in here
                    $error = 1;
                    $msg = vc_language_render('store.admin.value_cannot_change');
                } elseif ($name == 'domain') {
                    if (
                        $storeId == SC_ID_ROOT 
                        || ((vc_check_multi_vendor_installed()) && vc_store_is_partner($storeId)) 
                        || vc_check_multi_store_installed()
                    ) {
                        // Only store root can edit domain
                        $domain = vc_process_domain_store($value);
                        if (AdminStore::where('domain', $domain)->where('id', '<>', $storeId)->first()) {
                            $error = 1;
                            $msg = vc_language_render('store.admin.domain_exist');
                        } else {
                            AdminStore::where('id', $storeId)->update([$name => $domain]);
                            $error = 0;
                        }
                    } else {
                        $error = 1;
                        $msg = vc_language_render('store.admin.value_cannot_change');
                    }
                } elseif ($name == 'code') {
                    if (AdminStore::where('code', $value)->where('id', '<>', $storeId)->first()) {
                        $error = 1;
                        $msg = vc_language_render('store.admin.code_exist');
                    } else {
                        AdminStore::where('id', $storeId)->update([$name => $value]);
                        $error = 0;
                    }
                } elseif ($name == 'template') {
                    AdminStore::where('id', $storeId)->update([$name => $value]);
                    //Install template for store
                    if (file_exists($fileProcess = resource_path() . '/views/templates/'.$value.'/Provider.php')) {
                        include_once $fileProcess;
                        if (function_exists('vc_template_install_store')) {
                            //Insert only specify store
                            $checkTemplateEnableStore = (new \Vncore\Core\Front\Models\ShopStoreCss)
                                ->where('template', $value)
                                ->where('store_id', $storeId)
                                ->first();
                            if (!$checkTemplateEnableStore) {
                                vc_template_install_store($storeId);
                            }
                        }
                    }
                    $error = 0;
                } else {
                    AdminStore::where('id', $storeId)->update([$name => $value]);
                    $error = 0;
                }
            } catch (\Throwable $e) {
                $error = 1;
                $msg = $e->getMessage();
            }
        } else {
            // Process description
            $dataUpdate = [
                'storeId' => $storeId,
                'lang' => $lang,
                'name' => $name,
                'value' => $value,
            ];
            $dataUpdate = vc_clean($dataUpdate, [], true);
            try {
                AdminStore::updateDescription($dataUpdate);
                $error = 0;
            } catch (\Throwable $e) {
                $error = 1;
                $msg = $e->getMessage();
            }
        }
        return response()->json(['error' => $error, 'msg' => $msg]);
    }

    public function index()
    {
        $id = session('adminStoreId');
        $store = AdminStore::find($id);
        if (!$store) {
            $data = [
                'title' => vc_language_render('store.admin.title'),
                'subTitle' => '',
                'icon' => 'fas fa-cogs',
                'dataNotFound' => 1
            ];
            return view($this->vc_templatePathAdmin.'screen.store_info')
            ->with($data);
        }
        $data = [
            'title' => vc_language_render('store.admin.title'),
            'subTitle' => '',
            'icon' => 'fas fa-cogs',
        ];
        $data['store'] = $store;
        $data['templates'] = $this->templates;
        $data['languages'] = $this->languages;
        $data['storeId'] = $id;

        return view($this->vc_templatePathAdmin.'screen.store_info')
        ->with($data);
    }
}
