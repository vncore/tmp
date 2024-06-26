<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Front\Models\ShopSupplier;
use Vncore\Core\Front\Models\ShopCustomField;
use Validator;

class AdminSupplierController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $data = [
            'title' => vc_language_render('admin.supplier.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . vc_language_render('admin.supplier.add_new_title'),
            'subTitle' => '',
            'urlDeleteItem' => vc_route_admin('admin_supplier.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'css' => '',
            'js' => '',
            'url_action' => vc_route_admin('admin_supplier.create'),
            'customFields'      => (new ShopCustomField)->getCustomField($type = 'shop_supplier'),
        ];

        $listTh = [
            'name' => vc_language_render('admin.supplier.name'),
            'image' => vc_language_render('admin.supplier.image'),
            'email' => vc_language_render('admin.supplier.email'),
            'sort' => vc_language_render('admin.supplier.sort'),
            'action' => vc_language_render('action.title'),
        ];
        $obj = new ShopSupplier;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[$row['id']] = [
                'name' => $row['name'],
                'image' => vc_image_render($row->getThumb(), '50px', '50px', $row['name']),
                'email' => $row['email'],
                'sort' => $row['sort'],
                'action' => '
                    <a href="' . vc_route_admin('admin_supplier.edit', ['id' => $row['id'] ? $row['id'] : 'not-found-id']) . '"><span title="' . vc_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                  <span onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vc_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                  ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vc_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vc_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'index';
        return view($this->vc_templatePathAdmin.'screen.supplier')
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
        $data['alias'] = vc_word_format_url($data['alias']);
        $data['alias'] = vc_word_limit($data['alias'], 100);
        $arrValidation = [
            'image' => 'required',
            'sort' => 'numeric|min:0',
            'name' => 'required|string|max:100',
            'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:"'.ShopSupplier::class.'",alias|string|max:100',
            'url' => 'url|nullable',
            'email' => 'email|nullable',
        ];
        //Custom fields
        $customFields = (new ShopCustomField)->getCustomField($type = 'shop_supplier');
        if ($customFields) {
            foreach ($customFields as $field) {
                if ($field->required) {
                    $arrValidation['fields.'.$field->code] = 'required';
                }
            }
        }
        $validator = Validator::make($data, $arrValidation, [
            'name.required' => vc_language_render('validation.required', ['attribute' => vc_language_render('admin.supplier.name')]),
            'alias.regex' => vc_language_render('admin.supplier.alias_validate'),
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
            'email' => $data['email'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'sort' => (int) $data['sort'],
        ];
        $dataCreate = vc_clean($dataCreate, [], true);
        $supplier = ShopSupplier::create($dataCreate);

        //Insert custom fields
        $fields = $data['fields'] ?? [];
        vc_update_custom_field($fields, $supplier->id, 'shop_supplier');

        return redirect()->route('admin_supplier.index')->with('success', vc_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $supplier = ShopSupplier::find($id);
        if (!$supplier) {
            return 'No data';
        }
        $data = [
        'title' => vc_language_render('admin.supplier.list'),
        'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . vc_language_render('action.edit'),
        'subTitle' => '',
        'icon' => 'fa fa-tasks',
        'urlDeleteItem' => vc_route_admin('admin_supplier.delete'),
        'removeList' => 0, // 1 - Enable function delete list item
        'buttonRefresh' => 0, // 1 - Enable button refresh
        'css' => '',
        'js' => '',
        'url_action' => vc_route_admin('admin_supplier.edit', ['id' => $supplier['id']]),
        'supplier' => $supplier,
        'id' => $id,
        'customFields'      => (new ShopCustomField)->getCustomField($type = 'shop_supplier'),
    ];

        $listTh = [
        'name' => vc_language_render('admin.supplier.name'),
        'image' => vc_language_render('admin.supplier.image'),
        'email' => vc_language_render('admin.supplier.email'),
        'sort' => vc_language_render('admin.supplier.sort'),
        'action' => vc_language_render('action.title'),
    ];

        $obj = new ShopSupplier;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[$row['id']] = [
            'name' => $row['name'],
            'image' => vc_image_render($row->getThumb(), '50px', '50px', $row['name']),
            'email' => $row['email'],
            'sort' => $row['sort'],
            'action' => '
                <a href="' . vc_route_admin('admin_supplier.edit', ['id' => $row['id'] ? $row['id'] : 'not-found-id']) . '"><span title="' . vc_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                <span onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vc_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                ',
        ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vc_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vc_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'edit';
        return view($this->vc_templatePathAdmin.'screen.supplier')
        ->with($data);
    }

    /**
     * update supplier
     */
    public function postEdit($id)
    {
        $supplier = ShopSupplier::find($id);
        $data = request()->all();

        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['name'];
        $data['alias'] = vc_word_format_url($data['alias']);
        $data['alias'] = vc_word_limit($data['alias'], 100);
        $arrValidation = [
            'image' => 'required',
            'sort' => 'numeric|min:0',
            'name' => 'required|string|max:100',
            'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:"'.ShopSupplier::class.'",alias,' . $supplier->id . ',id|string|max:100',
            'url' => 'url|nullable',
            'email' => 'email|nullable',
        ];
        //Custom fields
        $customFields = (new ShopCustomField)->getCustomField($type = 'shop_supplier');
        if ($customFields) {
            foreach ($customFields as $field) {
                if ($field->required) {
                    $arrValidation['fields.'.$field->code] = 'required';
                }
            }
        }
        $validator = Validator::make($data, $arrValidation, [
            'name.required' => vc_language_render('validation.required', ['attribute' => vc_language_render('admin.supplier.name')]),
            'alias.regex' => vc_language_render('admin.supplier.alias_validate'),
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
            'email' => $data['email'],
            'phone' => $data['phone'],
            'url' => $data['url'],
            'address' => $data['address'],
            'sort' => (int) $data['sort'],

        ];
        $dataUpdate = vc_clean($dataUpdate, [], true);
        $supplier->update($dataUpdate);

        //Insert custom fields
        $fields = $data['fields'] ?? [];
        vc_update_custom_field($fields, $supplier->id, 'shop_supplier');

        return redirect()->back()->with('success', vc_language_render('action.edit_success'));
    }

    /*
    Delete list item
    Need mothod destroy to boot deleting in model
     */
    public function deleteList()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => vc_language_render('admin.method_not_allow')]);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            ShopSupplier::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}
