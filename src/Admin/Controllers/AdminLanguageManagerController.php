<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Front\Models\Languages;
use Vncore\Core\Front\Models\ShopLanguage;
use Validator;

class AdminLanguageManagerController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $lang = request('lang');
        $position = request('position');
        $keyword = request('keyword');
        $languages = ShopLanguage::getListAll();
        $positionLang = Languages::getPosition();
        $languagesPosition = Languages::getLanguagesPosition($lang, $position, $keyword);
        
        $codeLanguages = ShopLanguage::getCodeAll();
        if (!in_array($lang, array_keys($codeLanguages))) {
            $languagesPositionEL =   [];
        } else {
            $languagesPositionEL = Languages::getLanguagesPosition('en', $position, $keyword);
        }
        $arrayKeyLanguagesPosition = array_keys($languagesPosition);
        $arrayKeyLanguagesPositionEL = array_keys($languagesPositionEL);
        $arrayKeyDiff = array_diff($arrayKeyLanguagesPositionEL, $arrayKeyLanguagesPosition);
        $urlUpdateData = vc_route_admin('admin_language_manager.update');
        $data = [
            'languages' => $languages,
            'lang' => $lang,
            'positionLang' => $positionLang,
            'position' => $position,
            'keyword' => $keyword,
            'languagesPosition' => $languagesPosition,
            'languagesPositionEL' => $languagesPositionEL,
            'arrayKeyDiff' => $arrayKeyDiff,
            'urlUpdateData' => $urlUpdateData,
            'title' => vc_language_render('admin.language_manager.title'),
            'subTitle' => '',
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'css' => '',
            'js' => '',
            'layout' => 'index',
        ];


        return view($this->vc_templatePathAdmin.'screen.language_manager')
            ->with($data);
    }

    /**
     * Update data
     *
     * @return void
     */
    public function postUpdate()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => vc_language_render('admin.method_not_allow')]);
        } else {
            $data = request()->all();
            $lang = vc_clean($data['lang']);
            $name = vc_clean($data['name']);
            $value = vc_clean($data['value']);
            $position = vc_clean($data['pk']);
            $languages = ShopLanguage::getCodeAll();
            if (!in_array($lang, array_keys($languages))) {
                return response()->json(['error' => 1, 'msg' => vc_language_render('admin.method_not_allow')]);
            }
            if ($position) {
                Languages::updateOrCreate(
                    ['location' => $lang, 'code' => $name],
                    ['text' => $value, 'position' => $position],
                );
            } else {
                Languages::updateOrCreate(
                    ['location' => $lang, 'code' => $name],
                    ['text' => $value],
                );
            }

            return response()->json(['error' => 0, 'msg' => vc_language_render('action.update_success')]);
        }
    }

    /**
     * Screen add new record language
     *
     * @return void
     */
    public function add()
    {
        $languages = ShopLanguage::getListAll();
        $positionLang = Languages::getPosition();
        $data = [
            'title' => vc_language_render('admin.language_manager.add'),
            'positionLang' => $positionLang,
            'languages' => $languages,
        ];
        return view($this->vc_templatePathAdmin.'screen.language_manager_add')
            ->with($data);
    }

    /**
     * Add new record for language
     *
     * @return void
     */
    public function postAdd()
    {
        $data = request()->all();
        $validator = Validator::make(
            $data,
            [
                'text'         => 'required',
                'position' => 'required_without:position_new',
                'code'         => 'required|unique:"'.Languages::class.'",code|string|max:100',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }

        $dataCreate = [
            'code' => trim($data['code']),
            'text' => trim($data['text']),
            'position' => trim(empty($data['position_new']) ? $data['position'] : $data['position_new']),
            'location' => 'en',
        ];
        $dataCreate = vc_clean($dataCreate, ['text'], true);
        Languages::insert($dataCreate);

        return redirect(vc_route_admin('admin_language_manager.index'))->with('success', vc_language_render('action.create_success'));
    }
}
