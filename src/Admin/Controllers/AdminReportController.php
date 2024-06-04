<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Front\Models\ShopAttributeGroup;
use Vncore\Core\Front\Models\ShopProductProperty;
use Vncore\Core\Front\Models\ShopLanguage;
use Vncore\Core\Admin\Models\AdminProduct;

class AdminReportController extends RootAdminController
{
    public $languages;
    public $kinds;
    public $properties;
    public $attributeGroup;

    public function __construct()
    {
        parent::__construct();
        $this->languages = ShopLanguage::getListActive();
        $this->attributeGroup = ShopAttributeGroup::getListAll();
        $this->kinds = [
            SC_PRODUCT_SINGLE => vc_language_render('product.kind_single'),
            SC_PRODUCT_BUILD => vc_language_render('product.kind_bundle'),
            SC_PRODUCT_GROUP => vc_language_render('product.kind_group'),
        ];
        $this->properties = (new ShopProductProperty)->pluck('name', 'code')->toArray();
    }

    public function product()
    {
        $data = [
            'title' => vc_language_render('product.admin.list'),
            'subTitle' => '',
            'urlDeleteItem' => '',
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'css' => '',
            'js' => '',
        ];
        //Process add content
        $data['menuRight'] = vc_config_group('menuRight', \Request::route()->getName());
        $data['menuLeft'] = vc_config_group('menuLeft', \Request::route()->getName());
        $data['topMenuRight'] = vc_config_group('topMenuRight', \Request::route()->getName());
        $data['topMenuLeft'] = vc_config_group('topMenuLeft', \Request::route()->getName());
        $data['blockBottom'] = vc_config_group('blockBottom', \Request::route()->getName());

        $listTh = [
            'image' => vc_language_render('product.image'),
            'sku' => vc_language_render('product.sku'),
            'name' => vc_language_render('product.name'),
            'price' => vc_language_render('product.price'),
            'stock' => vc_language_render('product.stock'),
            'sold' => vc_language_render('product.sold'),
            'view' => vc_language_render('product.view'),
            'kind' => vc_language_render('product.kind'),
            'status' => vc_language_render('product.status'),
        ];
        $sort_order = vc_clean(request('sort_order') ?? 'id_desc');
        $keyword    = vc_clean(request('keyword') ?? '');
        $arrSort = [
            'id__desc' => vc_language_render('filter_sort.id_desc'),
            'id__asc' => vc_language_render('filter_sort.id_asc'),
            'name__desc' => vc_language_render('filter_sort.name_desc'),
            'name__asc' => vc_language_render('filter_sort.name_asc'),
            'sold__desc' => vc_language_render('filter_sort.value_desc'),
            'sold__asc' => vc_language_render('filter_sort.sold_asc'),
            'view__desc' => vc_language_render('filter_sort.view_desc'),
            'view__asc' => vc_language_render('filter_sort.view_asc'),
        ];
        $dataSearch = [
            'keyword'    => $keyword,
            'sort_order' => $sort_order,
            'arrSort'    => $arrSort,
        ];

        $dataTmp = (new AdminProduct)->getProductListAdmin($dataSearch);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $kind = $this->kinds[$row['kind']] ?? $row['kind'];
            if ($row['kind'] == SC_PRODUCT_BUILD) {
                $kind = '<span class="badge badge-success">' . $kind . '</span>';
            } elseif ($row['kind'] == SC_PRODUCT_GROUP) {
                $kind = '<span class="badge badge-danger">' . $kind . '</span>';
            }

            $dataTr[$row['id']] = [
                'image' => vc_image_render($row['image'], '50px', '', $row['name']),
                'sku' => $row['sku'],
                'name' => $row['name'],
                'price' => $row['price'],
                'stock' => $row['stock'],
                'sold' => $row['sold'],
                'view' => $row['view'],
                'kind' => $kind,
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = vc_language_render('product.admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        //menu_left
        $data['menu_left'] = '<div class="pull-left">

                    <a class="btn   btn-flat btn-primary grid-refresh" title="Refresh"><i class="fas fa-sync-alt"></i><span class="hidden-xs"> ' . vc_language_render('action.refresh') . '</span></a> &nbsp;</div>
                    ';
        //=menu_left

        //menuSearch
        $optionSort = '';
        foreach ($arrSort as $key => $status) {
            $optionSort .= '<option  ' . (($sort_order == $key) ? "selected" : "") . ' value="' . $key . '">' . $status . '</option>';
        }
        //=menuSort

        //menuSearch
        $data['topMenuRight'][] = '
                <form action="' . vc_route_admin('admin_report.product') . '" id="button_search">
                <div class="input-group input-group" style="width: 350px;">
                    <select class="form-control rounded-0 select2" name="sort_order" id="sort_order">
                    '.$optionSort.'
                    </select> &nbsp;
                    <input type="text" name="keyword" class="form-control rounded-0 float-right" placeholder="' . vc_language_render('product.admin.search_place') . '" value="' . $keyword . '">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                </form>';
        //=menuSearch

        return view($this->templatePathAdmin.'screen.list')
            ->with($data);
    }
}
