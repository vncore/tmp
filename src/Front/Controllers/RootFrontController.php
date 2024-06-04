<?php
namespace Vncore\Core\Front\Controllers;

use App\Http\Controllers\Controller;

class RootFrontController extends Controller
{
    public $templatePath;
    public $templateFile;
    public function __construct()
    {
        $this->templatePath = 'templates.' . vc_store('template');
        $this->templateFile = 'templates/' . vc_store('template');
    }


    /**
     * Default page not found
     *
     * @return  [type]  [return description]
     */
    public function pageNotFound()
    {
        vc_check_view( $this->templatePath . '.notfound');
        return view(
             $this->templatePath . '.notfound',
            [
            'title' => vc_language_render('front.page_not_found_title'),
            'msg' => vc_language_render('front.page_not_found'),
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
        vc_check_view( $this->templatePath . '.notfound');
        return view(
             $this->templatePath . '.notfound',
            [
                'title' => vc_language_render('front.data_not_found_title'),
                'msg' => vc_language_render('front.data_not_found'),
                'description' => '',
                'keyword' => '',
            ]
        );
    }
}
