<?php
namespace Vncore\Core\Api\Controllers;

use Vncore\Core\Front\Controllers\RootFrontController;
use Vncore\Core\Admin\Models\ShopBrand;
use Vncore\Core\Admin\Models\ShopCategory;
use Vncore\Core\Admin\Models\ShopProduct;
use Vncore\Core\Admin\Models\ShopSupplier;
use Vncore\Core\Admin\Models\AdminCountry;
use Vncore\Core\Admin\Models\AdminLanguage;

class AdminShopFront extends RootFrontController
{
    /**
     * display list category root (parent = 0)
     * @return [json]
     */
    public function allCategory()
    {
        $itemsList = (new ShopCategory)
            ->with('descriptions')
            ->jsonPaginate();
        return response()->json($itemsList, 200);
    }

    /**
     * Category detail: list category child
     * @param  [int] $id
     * @return [json]
     */
    public function categoryDetail($id)
    {
        $category = (new ShopCategory)
            ->with('descriptions')
            ->find($id);
        if ($category) {
            return response()->json($category, 200);
        } else {
            return response()->json([], 404);
        }
    }

    /**
     * All products
     * @return [json]
     */
    public function allProduct()
    {
        $products = (new ShopProduct)
            ->with('images')
            ->with('descriptions')
            ->with('promotionPrice')
            ->with('attributes')
            ->jsonPaginate();
        return response()->json($products, 200);
    }

    /**
     * product detail
     * @param  [int] $id
     * @return [json]
     */
    public function productDetail($id)
    {
        $product = (new ShopProduct)
        ->with('images')
        ->with('descriptions')
        ->with('promotionPrice')
        ->with('attributes')
        ->find($id);
        if ($product) {
            return response()->json($product, 200);
        } else {
            return response()->json('Product not found', 404);
        }
    }

    public function allBrand()
    {
        $itemsList = (new ShopBrand)
            ->jsonPaginate();
        return response()->json($itemsList, 200);
    }

    public function brandDetail($id)
    {
        $brand = (new ShopBrand)->find($id);
        if ($brand) {
            return response()->json($brand, 200);
        } else {
            return response()->json('Not found', 404);
        }
    }

    public function allSupplier()
    {
        $itemsList = (new ShopSupplier)->jsonPaginate();
        return response()->json($itemsList, 200);
    }

    public function supplierDetail($id)
    {
        $supplier = (new ShopSupplier)->find($id);
        if ($supplier) {
            return response()->json($supplier, 200);
        } else {
            return response()->json('Not found', 404);
        }
    }

    public function allCountry()
    {
        $itemsList = (new AdminCountry)->jsonPaginate();
        return response()->json($itemsList, 200);
    }

    public function countryDetail($id)
    {
        $country = (new AdminCountry)->find($id);
        if ($country) {
            return response()->json($country, 200);
        } else {
            return response()->json('Not found', 404);
        }
    }


    public function allLanguage()
    {
        $itemsList = (new AdminLanguage)->jsonPaginate();
        return response()->json($itemsList, 200);
    }

    public function languageDetail($id)
    {
        $language = (new AdminLanguage)->find($id);
        if ($language) {
            return response()->json($language, 200);
        } else {
            return response()->json('Not found', 404);
        }
    }
}
