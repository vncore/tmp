<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminSubscribe;
use Validator;

class AdminSubscribeController extends RootAdminController
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
            'title'         => vc_language_render('subscribe.admin.list'),
            'subTitle'      => '',
            'icon'          => 'fa fa-tasks',
            'urlDeleteItem' => vc_route_admin('admin_subscribe.delete'),
            'removeList'    => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'css'           => '',
            'js'            => '',
        ];
        //Process add content
        $data['menuRight']    = vc_config_group('menuRight', \Request::route()->getName());
        $data['menuLeft']     = vc_config_group('menuLeft', \Request::route()->getName());
        $data['topMenuRight'] = vc_config_group('topMenuRight', \Request::route()->getName());
        $data['topMenuLeft']  = vc_config_group('topMenuLeft', \Request::route()->getName());
        $data['blockBottom']  = vc_config_group('blockBottom', \Request::route()->getName());

        $listTh = [
            'email' => vc_language_render('subscribe.admin.email'),
            'status' => vc_language_render('subscribe.admin.status'),
            'action' => vc_language_render('action.title'),
        ];

        $sort_order = vc_clean(request('sort_order') ?? 'id_desc');
        $keyword    = vc_clean(request('keyword') ?? '');
        $arrSort = [
            'id__desc' => vc_language_render('filter_sort.id_desc'),
            'id__asc' => vc_language_render('filter_sort.id_asc'),
            'email__desc' => vc_language_render('filter_sort.alpha_desc', ['alpha' =>'Email']),
            'email__asc' => vc_language_render('filter_sort.alpha_asc', ['alpha' =>'Email']),
        ];
        $dataSearch = [
            'keyword'    => $keyword,
            'sort_order' => $sort_order,
            'arrSort'    => $arrSort,
        ];
        $dataTmp = AdminSubscribe::getSubscribeListAdmin($dataSearch);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[$row['id']] = [
                'email' => $row['email'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'action' => '
                    <a href="' . vc_route_admin('admin_subscribe.edit', ['id' => $row['id'] ? $row['id'] : 'not-found-id']) . '"><span title="' . vc_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                  <span onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vc_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                  ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vc_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vc_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);



        //menuRight
        $data['menuRight'][] = '<a href="' . vc_route_admin('admin_subscribe.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
        <i class="fa fa-plus" title="'.vc_language_render('action.add').'"></i>
                           </a>';
        //=menuRight

        //menuSort
        $optionSort = '';
        foreach ($arrSort as $key => $status) {
            $optionSort .= '<option  ' . (($sort_order == $key) ? "selected" : "") . ' value="' . $key . '">' . $status . '</option>';
        }
        //=menuSort

        //menuSearch
        $data['topMenuRight'][] = '
                <form action="' . vc_route_admin('admin_subscribe.index') . '" id="button_search">
                <div class="input-group input-group" style="width: 350px;">
                    <select class="form-control rounded-0 select2" name="sort_order" id="sort_order">
                    '.$optionSort.'
                    </select> &nbsp;
                    <input type="text" name="keyword" class="form-control rounded-0 float-right" placeholder="' . vc_language_render('subscribe.admin.search_place') . '" value="' . $keyword . '">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                </form>';
        //=menuSearch

        return view($this->vc_templatePathAdmin.'screen.list')
            ->with($data);
    }

    /**
     * Form create new item in admin
     * @return [type] [description]
     */
    public function create()
    {
        $data = [
            'title' => vc_language_render('subscribe.admin.add_new_title'),
            'subTitle' => '',
            'title_description' => vc_language_render('subscribe.admin.add_new_des'),
            'icon' => 'fa fa-plus',
            'subscribe' => [],
            'url_action' => vc_route_admin('admin_subscribe.create'),
        ];
        return view($this->vc_templatePathAdmin.'screen.subscribe')
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
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dataCreate = [
            'email' => $data['email'],
            'status' => (!empty($data['status']) ? 1 : 0),
            'store_id' => session('adminStoreId'),
        ];
        $dataCreate = vc_clean($dataCreate, [], true);
        AdminSubscribe::createSubscribeAdmin($dataCreate);

        return redirect()->route('admin_subscribe.index')->with('success', vc_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $subscribe = AdminSubscribe::getSubscribeAdmin($id);

        if (!$subscribe) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $data = [
            'title' => vc_language_render('action.edit'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'subscribe' => $subscribe,
            'url_action' => vc_route_admin('admin_subscribe.edit', ['id' => $subscribe['id']]),
        ];
        return view($this->vc_templatePathAdmin.'screen.subscribe')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $subscribe = AdminSubscribe::getSubscribeAdmin($id);
        if (!$subscribe) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }

        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit

        $dataUpdate = [
            'email' => $data['email'],
            'status' => (!empty($data['status']) ? 1 : 0),
            'store_id' => session('adminStoreId'),

        ];
        $dataUpdate = vc_clean($dataUpdate, [], true);
        $subscribe->update($dataUpdate);

        return redirect()->route('admin_subscribe.index')
                ->with('success', vc_language_render('action.edit_success'));
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
            $arrDontPermission = [];
            foreach ($arrID as $key => $id) {
                if (!$this->checkPermisisonItem($id)) {
                    $arrDontPermission[] = $id;
                }
            }
            if (count($arrDontPermission)) {
                return response()->json(['error' => 1, 'msg' => vc_language_render('admin.remove_dont_permisison') . ': ' . json_encode($arrDontPermission)]);
            }
            AdminSubscribe::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    /**
     * Check permisison item
     */
    public function checkPermisisonItem($id)
    {
        return AdminSubscribe::getSubscribeAdmin($id);
    }
}
