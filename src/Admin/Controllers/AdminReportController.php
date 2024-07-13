<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\ShopAttributeGroup;
use Vncore\Core\Admin\Models\ShopProductProperty;
use Vncore\Core\Admin\Models\AdminLanguage;
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
        $this->languages = AdminLanguage::getListActive();
        $this->attributeGroup = ShopAttributeGroup::getListAll();
        $this->kinds = [
            VNCORE_PRODUCT_SINGLE => vncore_language_render('product.kind_single'),
            VNCORE_PRODUCT_BUILD => vncore_language_render('product.kind_bundle'),
            VNCORE_PRODUCT_GROUP => vncore_language_render('product.kind_group'),
        ];
        $this->properties = (new ShopProductProperty)->pluck('name', 'code')->toArray();
    }

    public function product()
    {
        $data = [
            'title' => vncore_language_render('product.admin.list'),
            'subTitle' => '',
            'urlDeleteItem' => '',
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'css' => '',
            'js' => '',
        ];
        //Process add content
        $data['menuRight'] = vncore_config_group('menuRight', \Request::route()->getName());
        $data['menuLeft'] = vncore_config_group('menuLeft', \Request::route()->getName());
        $data['topMenuRight'] = vncore_config_group('topMenuRight', \Request::route()->getName());
        $data['topMenuLeft'] = vncore_config_group('topMenuLeft', \Request::route()->getName());
        $data['blockBottom'] = vncore_config_group('blockBottom', \Request::route()->getName());

        $listTh = [
            'image' => vncore_language_render('product.image'),
            'sku' => vncore_language_render('product.sku'),
            'name' => vncore_language_render('product.name'),
            'price' => vncore_language_render('product.price'),
            'stock' => vncore_language_render('product.stock'),
            'sold' => vncore_language_render('product.sold'),
            'view' => vncore_language_render('product.view'),
            'kind' => vncore_language_render('product.kind'),
            'status' => vncore_language_render('product.status'),
        ];
        $sort_order = vncore_clean(request('sort_order') ?? 'id_desc');
        $keyword    = vncore_clean(request('keyword') ?? '');
        $arrSort = [
            'id__desc' => vncore_language_render('filter_sort.id_desc'),
            'id__asc' => vncore_language_render('filter_sort.id_asc'),
            'name__desc' => vncore_language_render('filter_sort.name_desc'),
            'name__asc' => vncore_language_render('filter_sort.name_asc'),
            'sold__desc' => vncore_language_render('filter_sort.value_desc'),
            'sold__asc' => vncore_language_render('filter_sort.sold_asc'),
            'view__desc' => vncore_language_render('filter_sort.view_desc'),
            'view__asc' => vncore_language_render('filter_sort.view_asc'),
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
            if ($row['kind'] == VNCORE_PRODUCT_BUILD) {
                $kind = '<span class="badge badge-success">' . $kind . '</span>';
            } elseif ($row['kind'] == VNCORE_PRODUCT_GROUP) {
                $kind = '<span class="badge badge-danger">' . $kind . '</span>';
            }

            $dataTr[$row['id']] = [
                'image' => vncore_image_render($row['image'], '50px', '', $row['name']),
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
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vncore_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vncore_language_render('product.admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        //menu_left
        $data['menu_left'] = '<div class="pull-left">

                    <a class="btn   btn-flat btn-primary grid-refresh" title="Refresh"><i class="fas fa-sync-alt"></i><span class="hidden-xs"> ' . vncore_language_render('action.refresh') . '</span></a> &nbsp;</div>
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
                <form action="' . vncore_route_admin('admin_report.product') . '" id="button_search">
                <div class="input-group input-group" style="width: 350px;">
                    <select class="form-control rounded-0 select2" name="sort_order" id="sort_order">
                    '.$optionSort.'
                    </select> &nbsp;
                    <input type="text" name="keyword" class="form-control rounded-0 float-right" placeholder="' . vncore_language_render('product.admin.search_place') . '" value="' . $keyword . '">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                </form>';
        //=menuSearch

        return view($this->vncore_templatePathAdmin.'screen.list')
            ->with($data);
    }
}
