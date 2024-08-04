<?php
namespace Vncore\Core\Front\Controllers;

use Vncore\Core\Front\Controllers\RootFrontController;

class ShopContentController extends RootFrontController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Home page
     * @return [view]
     */
    public function index()
    {
        $viewHome = $this->templatePath . '.screen.home';
        $layoutPage = 'home';
        vncore_check_view($viewHome);
        return view(
            $viewHome,
            array(
                'title'       => vncore_store('title'),
                'keyword'     => vncore_store('keyword'),
                'description' => vncore_store('description'),
                'storeId'     => config('app.storeId'),
                'layout_page' => $layoutPage,
            )
        );
    }
}
