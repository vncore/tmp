<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\ShopBrand;
use Vncore\Core\Admin\Models\ShopCustomField;
use Validator;

class AdminBrandController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'title' => vncore_language_render('admin.brand.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . vncore_language_render('admin.brand.add_new_title'),
            'subTitle' => '',
            'urlDeleteItem' => vncore_route_admin('admin_brand.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'css' => '',
            'js' => '',
            'url_action' => vncore_route_admin('admin_brand.create'),
            'customFields'      => (new ShopCustomField)->getCustomField($type = 'shop_brand'),
        ];

        $listTh = [
            'name' => vncore_language_render('admin.brand.name'),
            'image' => vncore_language_render('admin.brand.image'),
            'status' => vncore_language_render('admin.brand.status'),
        ];

        if (vncore_check_multi_shop_installed() && session('adminStoreId') == VNCORE_ID_ROOT) {
            // Only show store info if store is root
            $listTh['shop_store'] = vncore_language_render('front.store_list');
        }
        $listTh['action'] = vncore_language_render('action.title');

        $obj = new ShopBrand;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        if (vncore_check_multi_shop_installed() && session('adminStoreId') == VNCORE_ID_ROOT) {
            $arrId = $dataTmp->pluck('id')->toArray();
            // Only show store info if store is root
            if (function_exists('vncore_get_list_store_of_brand')) {
                $dataStores = vncore_get_list_store_of_brand($arrId);
            } else {
                $dataStores = [];
            }
        }

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataMap = [
                'name' => $row['name'],
                'image' => vncore_image_render($row->getThumb(), '50px', '', $row['name']),
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
            ];
            if (vncore_check_multi_shop_installed() && session('adminStoreId') == VNCORE_ID_ROOT) {
                // Only show store info if store is root
                if (!empty($dataStores[$row['id']])) {
                    $storeTmp = $dataStores[$row['id']]->pluck('code', 'id')->toArray();
                    $storeTmp = array_map(function ($code) {
                        return '<a target=_new href="'.vncore_get_domain_from_code($code).'">'.$code.'</a>';
                    }, $storeTmp);
                    $dataMap['shop_store'] = '<i class="nav-icon fab fa-shopify"></i> '.implode('<br><i class="nav-icon fab fa-shopify"></i> ', $storeTmp);
                } else {
                    $dataMap['shop_store'] = '';
                }
            }
            $dataMap['action'] = '<a href="' . vncore_route_admin('admin_brand.edit', ['id' => $row['id'] ? $row['id'] : 'not-found-id']) . '"><span title="' . vncore_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
                                <span onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vncore_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                                ';
            $dataTr[$row['id']] = $dataMap;
        }



        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vncore_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vncore_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'index';
        return view($this->vncore_templatePathAdmin.'screen.brand')
            ->with($data);
    }


    /**
     * Post create new item in admin
     * @return [type] [description]
     */
    public function postCreate()
    {
        $data = request()->all();

        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['name'];
        $data['alias'] = vncore_word_format_url($data['alias']);
        $data['alias'] = vncore_word_limit($data['alias'], 100);
        $arrValidation = [
            'name'  => 'required|string|max:100',
            'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:"'.ShopBrand::class.'",alias|string|max:100',
            'image' => 'required',
            'sort'  => 'numeric|min:0',
            'url'   => 'url|nullable',
        ];
        //Custom fields
        $customFields = (new ShopCustomField)->getCustomField($type = 'shop_brand');
        if ($customFields) {
            foreach ($customFields as $field) {
                if ($field->required) {
                    $arrValidation['fields.'.$field->code] = 'required';
                }
            }
        }

        $validator = Validator::make($data, $arrValidation, [
            'name.required' => vncore_language_render('validation.required', ['attribute' => vncore_language_render('admin.brand.name')]),
            'alias.regex' => vncore_language_render('admin.brand.alias_validate'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        $dataCreate = [
            'image' => $data['image'],
            'name' => $data['name'],
            'alias' => $data['alias'],
            'url' => $data['url'],
            'sort' => (int) $data['sort'],
            'status' => (!empty($data['status']) ? 1 : 0),
        ];
        $dataCreate = vncore_clean($dataCreate, [], true);
        $brand = ShopBrand::create($dataCreate);

        $AdminStore        = $data['shop_store'] ?? [session('adminStoreId')];
        $brand->stores()->detach();
        if ($AdminStore) {
            $brand->stores()->attach($AdminStore);
        }

        //Insert custom fields
        $fields = $data['fields'] ?? [];
        vncore_update_custom_field($fields, $brand->id, 'shop_brand');

        return redirect()->route('admin_brand.index')->with('success', vncore_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $brand = ShopBrand::find($id);
        if (!$brand) {
            return 'No data';
        }
        $data = [
        'title' => vncore_language_render('admin.brand.list'),
        'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . vncore_language_render('action.edit'),
        'subTitle' => '',
        'icon' => 'fa fa-tasks',
        'urlDeleteItem' => vncore_route_admin('admin_brand.delete'),
        'removeList' => 0, // 1 - Enable function delete list item
        'buttonRefresh' => 0, // 1 - Enable button refresh
        'css' => '',
        'js' => '',
        'url_action' => vncore_route_admin('admin_brand.edit', ['id' => $brand['id']]),
        'brand' => $brand,
        'id' => $id,
        'customFields'      => (new ShopCustomField)->getCustomField($type = 'shop_brand'),
    ];

        $listTh = [
        'name' => vncore_language_render('admin.brand.name'),
        'image' => vncore_language_render('admin.brand.image'),
        'sort' => vncore_language_render('admin.brand.sort'),
        'status' => vncore_language_render('admin.brand.status'),
        'action' => vncore_language_render('action.title'),
    ];
        $obj = new ShopBrand;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[$row['id']] = [
            'name' => $row['name'],
            'image' => vncore_image_render($row->getThumb(), '50px', '', $row['name']),
            'sort' => $row['sort'],
            'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
            'action' => '
                <a href="' . vncore_route_admin('admin_brand.edit', ['id' => $row['id'] ? $row['id'] : 'not-found-id']) . '"><span title="' . vncore_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

              <span onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vncore_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
              ',
        ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vncore_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vncore_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'edit';
        return view($this->vncore_templatePathAdmin.'screen.brand')
        ->with($data);
    }


    /**
     * update status
     */
    public function postEdit($id)
    {
        $brand = ShopBrand::find($id);
        $data = request()->all();
        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['name'];
        $data['alias'] = vncore_word_format_url($data['alias']);
        $data['alias'] = vncore_word_limit($data['alias'], 100);
        $arrValidation = [
            'name'  => 'required|string|max:100',
            'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:"'.ShopBrand::class.'",alias,' . $brand->id . ',id|string|max:100',
            'image' => 'required',
            'sort'  => 'numeric|min:0',
        ];
        //Custom fields
        $customFields = (new ShopCustomField)->getCustomField($type = 'shop_brand');
        if ($customFields) {
            foreach ($customFields as $field) {
                if ($field->required) {
                    $arrValidation['fields.'.$field->code] = 'required';
                }
            }
        }
        $validator = Validator::make($data, $arrValidation, [
            'name.required' => vncore_language_render('validation.required', ['attribute' => vncore_language_render('admin.brand.name')]),
            'alias.regex' => vncore_language_render('admin.brand.alias_validate'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        //Edit

        $dataUpdate = [
            'image' => $data['image'],
            'name' => $data['name'],
            'alias' => $data['alias'],
            'url' => $data['url'],
            'sort' => (int) $data['sort'],
            'status' => (!empty($data['status']) ? 1 : 0),

        ];
        $dataUpdate = vncore_clean($dataUpdate, [], true);
        $brand->update($dataUpdate);

        $AdminStore        = $data['shop_store'] ?? [session('adminStoreId')];
        $brand->stores()->detach();
        if ($AdminStore) {
            $brand->stores()->attach($AdminStore);
        }

        //Insert custom fields
        $fields = $data['fields'] ?? [];
        vncore_update_custom_field($fields, $brand->id, 'shop_brand');

        return redirect()->back()->with('success', vncore_language_render('action.edit_success'));
    }

    /*
    Delete list item
    Need mothod destroy to boot deleting in model
    */
    public function deleteList()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => vncore_language_render('admin.method_not_allow')]);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            ShopBrand::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}
