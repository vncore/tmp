<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Front\Models\ShopLinkGroup;
use Validator;

class AdminStoreLinkGroupController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        $data = [
            'title' => vc_language_render('admin.link_group.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . vc_language_render('admin.link_group.add_new_title'),
            'subTitle' => '',
            'urlDeleteItem' => vc_route_admin('admin_store_link_group.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'css' => '',
            'js' => '',
            'url_action' => vc_route_admin('admin_store_link_group.create'),
        ];

        $listTh = [
            'code' => vc_language_render('admin.link_group.code'),
            'name' => vc_language_render('admin.link_group.name'),
            'action' => vc_language_render('action.title'),
        ];
        $obj = new ShopLinkGroup;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[$row['id']] = [
                'code' => $row['code'] ?? 'N/A',
                'name' => $row['name'] ?? 'N/A',
                'action' => '
                    <a href="' . vc_route_admin('admin_store_link_group.edit', ['id' => $row['id'] ? $row['id'] : 'not-found-id']) . '"><span title="' . vc_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                  <span onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vc_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                  ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vc_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vc_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'index';
        return view($this->vc_templatePathAdmin.'screen.store_link_group')
            ->with($data);
    }

    /**
     * Post create new item in admin
     * @return [type] [description]
     */
    public function postCreate()
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name' => 'required',
            'code' => 'required|unique:"'.ShopLinkGroup::class.'",code',
        ], [
            'name.required' => vc_language_render('validation.required'),
        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data['code'] = vc_word_format_url($data['code']);
        $data['code'] = vc_word_limit($data['code'], 100);
        $dataCreate = [
            'code' => $data['code'],
            'name' => $data['name'],
        ];
        $dataCreate = vc_clean($dataCreate, [], true);
        ShopLinkGroup::create($dataCreate);

        return redirect()->route('admin_store_link_group.index')->with('success', vc_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $banner_type = ShopLinkGroup::find($id);
        if (!$banner_type) {
            return 'No data';
        }
        $data = [
        'title' => vc_language_render('admin.link_group.list'),
        'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . vc_language_render('action.edit'),
        'subTitle' => '',
        'icon' => 'fa fa-tasks',
        'urlDeleteItem' => vc_route_admin('admin_store_link_group.delete'),
        'removeList' => 0, // 1 - Enable function delete list item
        'buttonRefresh' => 0, // 1 - Enable button refresh
        'buttonSort' => 0, // 1 - Enable button sort
        'css' => '',
        'js' => '',
        'url_action' => vc_route_admin('admin_store_link_group.edit', ['id' => $banner_type['id']]),
        'banner_type' => $banner_type,
        'id' => $id,
    ];

        $listTh = [
        'code' => vc_language_render('admin.link_group.code'),
        'name' => vc_language_render('admin.link_group.name'),
        'action' => vc_language_render('action.title'),
    ];
        $obj = new ShopLinkGroup;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[$row['id']] = [
            'code' => $row['code'] ?? 'N/A',
            'name' => $row['name'] ?? 'N/A',
            'action' => '
                <a href="' . vc_route_admin('admin_store_link_group.edit', ['id' => $row['id'] ? $row['id'] : 'not-found-id']) . '"><span title="' . vc_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

              <span onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vc_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
              ',
        ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vc_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vc_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'edit';
        return view($this->vc_templatePathAdmin.'screen.store_link_group')
        ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $obj = ShopLinkGroup::find($id);
        $validator = Validator::make($dataOrigin, [
            'code' => 'required|unique:"'.ShopLinkGroup::class.'",code,' . $obj->id . ',id',
            'name' => 'required',
        ], [
            'name.required' => vc_language_render('validation.required'),
        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit
        $data['code'] = vc_word_format_url($data['code']);
        $data['code'] = vc_word_limit($data['code'], 100);
        $dataUpdate = [
            'code' => $data['code'],
            'name' => $data['name'],
        ];
        $dataUpdate = vc_clean($dataUpdate, [], true);
        $obj->update($dataUpdate);

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
            ShopLinkGroup::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}
