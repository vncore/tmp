<?php
namespace Vncore\Core\Front\Controllers;

use App\Http\Controllers\Controller;

class RootFrontController extends Controller
{
    public $templatePath;
    public $templateFile;
    public function __construct()
    {
        $this->templatePath = 'templates.' . vncore_store('template');
        $this->templateFile = 'templates/' . vncore_store('template');
    }


    /**
     * Default page not found
     *
     * @return  [type]  [return description]
     */
    public function pageNotFound()
    {
        vncore_check_view( $this->templatePath . '.notfound');
        return view(
             $this->templatePath . '.notfound',
            [
            'title' => vncore_language_render('front.page_not_found_title'),
            'msg' => vncore_language_render('front.page_not_found'),
            'description' => '',
            'keyword' => ''
            ]
        );
    }

    /**
     * Default item not found
     *
     * @return  [view]
     */
    public function itemNotFound()
    {
        vncore_check_view( $this->templatePath . '.notfound');
        return view(
             $this->templatePath . '.notfound',
            [
                'title' => vncore_language_render('front.data_not_found_title'),
                'msg' => vncore_language_render('front.data_not_found'),
                'description' => '',
                'keyword' => '',
            ]
        );
    }
}
