<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Front\Models\ShopApiConnection;
use Validator;

class AdminApiConnectionController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'title' => vc_language_render('admin.api_connection.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . vc_language_render('admin.api_connection.create'),
            'subTitle' => '',
            'urlDeleteItem' => vc_route_admin('admin_api_connection.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'css' => '',
            'js' => '',
            'url_action' => vc_route_admin('admin_api_connection.create'),
            'layout' => 'index',
        ];

        $listTh = [
            'id' => 'ID',
            'description' => vc_language_render('admin.api_connection.description'),
            'apiconnection' => vc_language_render('admin.api_connection.connection'),
            'apikey' => vc_language_render('admin.api_connection.apikey'),
            'expire' => vc_language_render('admin.api_connection.expire'),
            'last_active' => vc_language_render('admin.api_connection.last_active'),
            'status' => vc_language_render('admin.api_connection.status'),
            'action' => vc_language_render('action.title'),
        ];

        $obj = new ShopApiConnection;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[$row['id']] = [
                'id' => $row['id'],
                'description' => $row['description'],
                'apiconnection' => $row['apiconnection'],
                'apikey' => $row['apikey'],
                'expire' => $row['expire'],
                'last_active' => $row['last_active'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'action' => '
                    <a href="' . vc_route_admin('admin_api_connection.edit', ['id' => $row['id'] ? $row['id'] : 'not-found-id']) . '"><span title="' . vc_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                  <span onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vc_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                  ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vc_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vc_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);
    
        return view($this->vc_templatePathAdmin.'screen.api_connection')
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
            'description' => 'string|required',
            'apiconnection' => 'string|required|regex:/(^([0-9a-z\-_]+)$)/|unique:"'.ShopApiConnection::class.'",apiconnection',
            'apikey' => 'string|regex:/(^([0-9a-z\-_]+)$)/',
        ], [
            'apiconnection.regex' => vc_language_render('admin.api_connection.validate_regex'),
            'apikey.regex' => vc_language_render('admin.api_connection.validate_regex'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dataCreate = [
            'description' => $data['description'],
            'apiconnection' => $data['apiconnection'],
            'apikey' => $data['apikey'],
            'expire' => $data['expire'],
            'status' => empty($data['status']) ? 0 : 1,
        ];
        $dataCreate = vc_clean($dataCreate, [], true);
        ShopApiConnection::create($dataCreate);

        return redirect()->route('admin_api_connection.index')->with('success', vc_language_render('action.create_success'));
    }

    /**
     * Form edit
     */

    public function edit($id)
    {
        $api_connection = ShopApiConnection::find($id);
        if ($api_connection === null) {
            return 'no data';
        }
        $data = [
        'title' => vc_language_render('admin.api_connection.list'),
        'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . vc_language_render('admin.api_connection.edit'),
        'subTitle' => '',
        'icon' => 'fa fa-tasks',
        'urlDeleteItem' => vc_route_admin('admin_api_connection.delete'),
        'removeList' => 0, // 1 - Enable function delete list item
        'buttonRefresh' => 0, // 1 - Enable button refresh
        'buttonSort' => 0, // 1 - Enable button sort
        'css' => '',
        'js' => '',
        'api_connection' => $api_connection,
        'url_action' => vc_route_admin('admin_api_connection.edit', ['id' => $api_connection['id']]),
        'layout' => 'edit',
        'id' => $id,
    ];

        $listTh = [
        'id' => 'ID',
        'description' => vc_language_render('admin.api_connection.description'),
        'apiconnection' => vc_language_render('admin.api_connection.apikey'),
        'apikey' => vc_language_render('admin.api_connection.apikey'),
        'expire' => vc_language_render('admin.api_connection.expire'),
        'last_active' => vc_language_render('admin.api_connection.last_active'),
        'status' => vc_language_render('admin.api_connection.status'),
        'action' => vc_language_render('action.title'),
    ];

        $obj = new ShopApiConnection;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[$row['id']] = [
            'id' => $row['id'],
            'description' => $row['description'],
            'apiconnection' => $row['apiconnection'],
            'apikey' => $row['apikey'],
            'expire' => $row['expire'],
            'last_active' => $row['last_active'],
            'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
            'action' => '
                <a href="' . vc_route_admin('admin_api_connection.edit', ['id' => $row['id'] ? $row['id'] : 'not-found-id']) . '"><span title="' . vc_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

              <span onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vc_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
              ',
        ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vc_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vc_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);
    
        $data['rightContentMain'] = '<input class="switch-data-config" data-store=0 name="api_connection_required" type="checkbox"  '.(vc_config_global('api_connection_required')?'checked':'').'><br> '.vc_language_render('admin.api_connection.api_connection_required_help');

        $optionSort = '';
        $data['urlSort'] = vc_route_admin('admin_api_connection.index', request()->except(['_token', '_pjax', 'sort_order']));
        $data['optionSort'] = $optionSort;
        return view($this->vc_templatePathAdmin.'screen.api_connection')
        ->with($data);
    }


    /**
     * update status
     */
    public function postEdit($id)
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $obj = ShopApiConnection::find($id);
        $validator = Validator::make($dataOrigin, [
            'description' => 'string|required',
            'apiconnection' => 'string|required|regex:/(^([0-9a-z\-_]+)$)/|unique:"'.ShopApiConnection::class.'",apiconnection,' . $obj->id . ',id',
            'apikey' => 'string|regex:/(^([0-9a-z\-_]+)$)/',
        ], [
            'apiconnection.regex' => vc_language_render('admin.api_connection.validate_regex'),
            'apikey.regex' => vc_language_render('admin.api_connection.validate_regex'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit

        $dataUpdate = [
            'description' => $data['description'],
            'apiconnection' => $data['apiconnection'],
            'apikey' => $data['apikey'],
            'expire' => $data['expire'],
            'status' => empty($data['status']) ? 0 : 1,
        ];
        $dataUpdate = vc_clean($dataUpdate, [], true);
        $obj->update($dataUpdate);

//
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
            ShopApiConnection::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    public function generateKey()
    {
        return response()->json(['data' => md5(time())]);
    }
}
