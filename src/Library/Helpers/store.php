<?php
use Illuminate\Support\Str;
/**
 * Get list store
 */
if (!function_exists('vncore_get_list_code_store') && !in_array('vncore_get_list_code_store', config('helper_except', []))) {
    function vncore_get_list_code_store()
    {
        return \Vncore\Core\Admin\Models\AdminStore::getListStoreCode();
    }
}


/**
 * Get domain from code
 */
if (!function_exists('vncore_get_domain_from_code') && !in_array('vncore_get_domain_from_code', config('helper_except', []))) {
    function vncore_get_domain_from_code(string $code = ""):string
    {
        $domainList = \Vncore\Core\Admin\Models\AdminStore::getStoreDomainByCode();
        if (!empty($domainList[$code])) {
            return 'http://'.$domainList[$code];
        } else {
            return url('/');
        }
    }
}

/**
 * Get domain root
 */
if (!function_exists('vncore_get_domain_root') && !in_array('vncore_get_domain_root', config('helper_except', []))) {
    function vncore_get_domain_root():string
    {
        $store = \Vncore\Core\Admin\Models\AdminStore::find(VNCORE_ID_ROOT);
        return $store->domain;
    }
}

/**
 * Check store is partner
 */
if (!function_exists('vncore_store_is_partner') && !in_array('vncore_store_is_partner', config('helper_except', []))) {
    function vncore_store_is_partner(string $storeId):bool
    {
        $store = \Vncore\Core\Admin\Models\AdminStore::find($storeId);
        if (!$store) {
            return false;
        }
        return $store->partner || $storeId == VNCORE_ID_ROOT;
    }
}

/**
 * Check store is root
 */
if (!function_exists('vncore_store_is_root') && !in_array('vncore_store_is_root', config('helper_except', []))) {
    function vncore_store_is_root(string $storeId):bool
    {
        return  $storeId == VNCORE_ID_ROOT;
    }
}


//======== store info============

/**
 * Get list store of product detail
 */
if (!function_exists('vncore_get_list_store_of_product_detail') && !in_array('vncore_get_list_store_of_product_detail', config('helper_except', []))) {
    function vncore_get_list_store_of_product_detail($pId):array
    {
        return \Vncore\Core\Front\Models\ShopProductStore::where('product_id', $pId)
        ->pluck('store_id')
        ->toArray();
    }
}


/**
 * Get list store of discount detail
 */
if (!function_exists('vncore_get_list_store_of_discount_detail') && !in_array('vncore_get_list_store_of_discount_detail', config('helper_except', []))) {
    function vncore_get_list_store_of_discount_detail($dId):array
    {
        return \App\Plugins\Total\Discount\Models\ShopDiscountStore::where('discount_id', $dId)
            ->pluck('store_id')
            ->toArray();
    }
}


/**
 * Get store list of brands
 */
if (!function_exists('vncore_get_list_store_of_brand') && !in_array('vncore_get_list_store_of_brand', config('helper_except', []))) {
    function vncore_get_list_store_of_brand(array $arrBrandId)
    {
        $tableStore = (new \Vncore\Core\Admin\Models\AdminStore)->getTable();
        $tableBrandStore = (new \Vncore\Core\Front\Models\ShopBrandStore)->getTable();
        return \Vncore\Core\Front\Models\ShopBrandStore::select($tableStore.'.code', $tableStore.'.id', 'brand_id')
            ->leftJoin($tableStore, $tableStore.'.id', $tableBrandStore.'.store_id')
            ->whereIn('brand_id', $arrBrandId)
            ->get()
            ->groupBy('brand_id');
    }
}


/**
 * Get list store of brand detail
 */
if (!function_exists('vncore_get_list_store_of_brand_detail') && !in_array('vncore_get_list_store_of_brand_detail', config('helper_except', []))) {
    function vncore_get_list_store_of_brand_detail($cId):array
    {
        return \Vncore\Core\Front\Models\ShopBrandStore::where('brand_id', $cId)
            ->pluck('store_id')
            ->toArray();
    }
}

/**
 * Get store list of banners
 */
if (!function_exists('vncore_get_list_store_of_banner') && !in_array('vncore_get_list_store_of_banner', config('helper_except', []))) {
    function vncore_get_list_store_of_banner(array $arrBannerId)
    {
        $tableStore = (new \Vncore\Core\Admin\Models\AdminStore)->getTable();
        $tableBannerStore = (new \Vncore\Core\Front\Models\ShopBannerStore)->getTable();
        return \Vncore\Core\Front\Models\ShopBannerStore::select($tableStore.'.code', $tableStore.'.id', 'banner_id')
            ->leftJoin($tableStore, $tableStore.'.id', $tableBannerStore.'.store_id')
            ->whereIn('banner_id', $arrBannerId)
            ->get()
            ->groupBy('banner_id');
    }
}

/**
 * Get list store of banner detail
 */
if (!function_exists('vncore_get_list_store_of_banner_detail') && !in_array('vncore_get_list_store_of_banner_detail', config('helper_except', []))) {
    function vncore_get_list_store_of_banner_detail($bId):array
    {
        return \Vncore\Core\Front\Models\ShopBannerStore::where('banner_id', $bId)
            ->pluck('store_id')
            ->toArray();
    }
}

/**
 * Get store list of news
 */
if (!function_exists('vncore_get_list_store_of_news') && !in_array('vncore_get_list_store_of_news', config('helper_except', []))) {
    function vncore_get_list_store_of_news(array $arrNewsId)
    {
        $tableStore = (new \Vncore\Core\Admin\Models\AdminStore)->getTable();
        $tableNewsStore = (new \Vncore\Core\Front\Models\ShopNewsStore)->getTable();
        return \Vncore\Core\Front\Models\ShopNewsStore::select($tableStore.'.code', $tableStore.'.id', 'news_id')
            ->leftJoin($tableStore, $tableStore.'.id', $tableNewsStore.'.store_id')
            ->whereIn('news_id', $arrNewsId)
            ->get()
            ->groupBy('news_id');
    }
}

/**
 * Get list store of news detail
 */
if (!function_exists('vncore_get_list_store_of_news_detail') && !in_array('vncore_get_list_store_of_news_detail', config('helper_except', []))) {
    function vncore_get_list_store_of_news_detail($nId):array
    {
        return \Vncore\Core\Front\Models\ShopNewsStore::where('news_id', $nId)
            ->pluck('store_id')
            ->toArray();
    }
}

/**
 * Get store list of pages
 */
if (!function_exists('vncore_get_list_store_of_page') && !in_array('vncore_get_list_store_of_page', config('helper_except', []))) {
    function vncore_get_list_store_of_page(array $arrPageId)
    {
        $tableStore = (new \Vncore\Core\Admin\Models\AdminStore)->getTable();
        $tablePageStore = (new \Vncore\Core\Front\Models\ShopPageStore)->getTable();
        return \Vncore\Core\Front\Models\ShopPageStore::select($tableStore.'.code', $tableStore.'.id', 'page_id')
            ->leftJoin($tableStore, $tableStore.'.id', $tablePageStore.'.store_id')
            ->whereIn('page_id', $arrPageId)
            ->get()
            ->groupBy('page_id');
    }
}

/**
 * Get list store of page detail
 */
if (!function_exists('vncore_get_list_store_of_page_detail') && !in_array('vncore_get_list_store_of_page_detail', config('helper_except', []))) {
    function vncore_get_list_store_of_page_detail($pId):array
    {
        return \Vncore\Core\Front\Models\ShopPageStore::where('page_id', $pId)
            ->pluck('store_id')
            ->toArray();
    }
}

/**
 * Get store list of links
 */
if (!function_exists('vncore_get_list_store_of_link') && !in_array('vncore_get_list_store_of_link', config('helper_except', []))) {
    function vncore_get_list_store_of_link($arrLinkId)
    {
        $tableStore = (new \Vncore\Core\Admin\Models\AdminStore)->getTable();
        $tableLinkStore = (new \Vncore\Core\Front\Models\ShopLinkStore)->getTable();
        return \Vncore\Core\Front\Models\ShopLinkStore::select($tableStore.'.code', $tableStore.'.id', 'link_id')
            ->leftJoin($tableStore, $tableStore.'.id', $tableLinkStore.'.store_id')
            ->whereIn('link_id', $arrLinkId)
            ->get()
            ->groupBy('link_id');
    }
}

/**
 * Get list store of link detail
 */
if (!function_exists('vncore_get_list_store_of_link_detail') && !in_array('vncore_get_list_store_of_link_detail', config('helper_except', []))) {
    function vncore_get_list_store_of_link_detail($cId)
    {
        return \Vncore\Core\Front\Models\ShopLinkStore::where('link_id', $cId)
            ->pluck('store_id')
            ->toArray();
    }
}

/**
 * Get store list of orders
 */
if (!function_exists('vncore_get_list_store_of_order') && !in_array('vncore_get_list_store_of_order', config('helper_except', []))) {
    function vncore_get_list_store_of_order(array $arrOrderId)
    {
        $tableStore = (new \Vncore\Core\Admin\Models\AdminStore)->getTable();
        $tableOrder = (new \Vncore\Core\Front\Models\ShopOrder)->getTable();
        return \Vncore\Core\Front\Models\ShopOrder::select($tableStore.'.code', $tableOrder.'.id')
            ->leftJoin($tableStore, $tableStore.'.id', $tableOrder.'.store_id')
            ->whereIn($tableOrder.'.id', $arrOrderId)
            ->get()
            ->groupBy('id');
    }
}

/**
 * Get store list of categories
 */
if (!function_exists('vncore_get_list_store_of_category') && !in_array('vncore_get_list_store_of_category', config('helper_except', []))) {
    function vncore_get_list_store_of_category(array $arrCategoryId)
    {
        $tableStore = (new \Vncore\Core\Admin\Models\AdminStore)->getTable();
        $tableCategoryStore = (new \Vncore\Core\Front\Models\ShopCategoryStore)->getTable();
        return \Vncore\Core\Front\Models\ShopCategoryStore::select($tableStore.'.code', $tableStore.'.id', 'category_id')
            ->leftJoin($tableStore, $tableStore.'.id', $tableCategoryStore.'.store_id')
            ->whereIn('category_id', $arrCategoryId)
            ->get()
            ->groupBy('category_id');
    }
}


/**
 * Get list store of category detail
 */
if (!function_exists('vncore_get_list_store_of_category_detail') && !in_array('vncore_get_list_store_of_category_detail', config('helper_except', []))) {
    function vncore_get_list_store_of_category_detail($cId):array
    {
        return \Vncore\Core\Front\Models\ShopCategoryStore::where('category_id', $cId)
            ->pluck('store_id')
            ->toArray();
    }
}

if (!function_exists('vncore_process_domain_store') && !in_array('vncore_process_domain_store', config('helper_except', []))) {
    /**
     * Process domain store
     *
     * @param   [string]  $domain
     *
     * @return  [string]         [$domain]
     */
    function vncore_process_domain_store(string $domain = "")
    {
        $domain = str_replace(['http://', 'https://'], '', $domain);
        $domain = Str::lower($domain);
        $domain = rtrim($domain, '/');
        return $domain;
    }
}

if (!function_exists('vncore_check_multi_shop_installed') && !in_array('vncore_check_multi_shop_installed', config('helper_except', []))) {
/**
 * Check plugin multi shop installed
 *
 * @return
 */
    function vncore_check_multi_shop_installed()
    {
        return 
        vncore_config_global('MultiVendorPro') 
        || vncore_config_global('MultiVendor') 
        || vncore_config_global('B2B') 
        || vncore_config_global('MultiStorePro')
        || vncore_config_global('MultiStore');
    }
}

if (!function_exists('vncore_check_multi_vendor_installed') && !in_array('vncore_check_multi_vendor_installed', config('helper_except', []))) {
    /**
     * Check plugin multi vendor installed
     *
     * @return
     */
        function vncore_check_multi_vendor_installed()
        {
            return vncore_config_global('MultiVendorPro') || vncore_config_global('B2B') || vncore_config_global('MultiVendor');
        }
}

if (!function_exists('vncore_check_multi_store_installed') && !in_array('vncore_check_multi_store_installed', config('helper_except', []))) {
    /**
     * Check plugin multi store installed
     *
     * @return
     */
        function vncore_check_multi_store_installed()
        {
            return vncore_config_global('MultiStorePro');
        }
}

if (!function_exists('vncore_link_vendor') && !in_array('vncore_link_vendor', config('helper_except', []))) {
    /**
     * Link vendor
     *
     * @return
     */
        function vncore_link_vendor(string $code = "")
        {
            $link = vncore_route('home');
            if (vncore_config_global('MultiVendorPro')) {
                $link = vncore_route('MultiVendorPro.detail', ['code' => $code]);
            }
            if (vncore_config_global('MultiVendor')) {
                $link = vncore_route('MultiVendor.detail', ['code' => $code]);
            }
            if (vncore_config_global('B2B')) {
                $link = vncore_route('B2B.detail', ['code' => $code]);
            }
            return $link;
        }
}


if (!function_exists('vncore_path_vendor') && !in_array('vncore_path_vendor', config('helper_except', []))) {
    /**
     * Path vendor
     *
     * @return
     */
        function vncore_path_vendor()
        {
            $path = 'vendor';
            if (vncore_config_global('MultiVendorPro')) {
                $path = config('MultiVendorPro.front_path');
            }
            if (vncore_config_global('MultiVendor')) {
                $path = config('MultiVendor.front_path');
            }
            if (vncore_config_global('B2B')) {
                $path = config('B2B.front_path');
            }
            return $path;
        }
}

/**
 * Get sum amount order
 * From S-Cart 6.9
 */
if (!function_exists('vncore_get_sum_amount_order') && !in_array('vncore_get_sum_amount_order', config('helper_except', []))) {
    function vncore_get_sum_amount_order($storeId = null)
    {
        return  (new \Vncore\Core\Admin\Models\AdminOrder)->getSumAmountOrder($storeId);
    }
}