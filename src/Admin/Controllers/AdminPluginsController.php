<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Illuminate\Support\Facades\File;

class AdminPluginsController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $action = request('action');
        $pluginKey = request('pluginKey');
        if ($action == 'config' && $pluginKey != '') {
            $namespace = vncore_get_class_plugin_config($pluginKey);
            $body = (new $namespace)->config();
        } else {
            $body = $this->pluginCode();
        }
        return $body;
    }

    protected function pluginCode()
    {
        $arrDefault = config('admin.plugin_protected');
        $pluginsInstalled = vncore_get_plugin_installed($onlyActive = false);
        $plugins = vncore_get_all_plugin();
        $title = vncore_language_render('admin.plugin.index');
        return $this->render($pluginsInstalled, $plugins, $title, $arrDefault);
    }

    public function render($pluginsInstalled, $plugins, $title,$arrDefault)
    {
        return view($this->vncore_templatePathAdmin.'screen.plugin')->with(
            [
                "title"            => $title,
                "pluginsInstalled" => $pluginsInstalled,
                "plugins"          => $plugins,
                "arrDefault"       => $arrDefault,
            ]
        );
    }

    /**
     * Install Plugin
     */
    public function install()
    {
        $key = request('key');
        $namespace = vncore_get_class_plugin_config($key);
        $response = (new $namespace)->install();
        return response()->json($response);
    }

    /**
     * Uninstall plugin
     *
     * @return  [type]  [return description]
     */
    public function uninstall()
    {
        $key = request('key');
        $onlyRemoveData = request('onlyRemoveData');
        $namespace = vncore_get_class_plugin_config($key);
        $response = (new $namespace)->uninstall();
        if (!$onlyRemoveData) {
            File::deleteDirectory(app_path('Plugins/'.$key));
            File::deleteDirectory(public_path('Plugins/'.$key));
        }
        return response()->json($response);
    }

    /**
     * Enable plugin
     *
     * @return  [type]  [return description]
     */
    public function enable()
    {
        $key = request('key');
        $namespace = vncore_get_class_plugin_config($key);
        $response = (new $namespace)->enable();
        return response()->json($response);
    }

    /**
     * Disable plugin
     *
     * @return  [type]  [return description]
     */
    public function disable()
    {
        $key = request('key');
        $namespace = vncore_get_class_plugin_config($key);
        $response = (new $namespace)->disable();
        return response()->json($response);
    }

    /**
     * Import plugin
     */
    public function importPlugin()
    {
        $data =  [
            'title' => vncore_language_render('admin.plugin.import')
        ];
        return view($this->vncore_templatePathAdmin.'screen.plugin_upload')
        ->with($data);
    }

    /**
     * Process import
     *
     * @return  [type]  [return description]
     */
    public function processImport()
    {
        $data = request()->all();
        $validator = \Validator::make(
            $data,
            [
                'file'   => 'required|mimetypes:application/zip|max:'.min($maxSizeConfig = vncore_getMaximumFileUploadSize($unit = 'K'), 51200),
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $pathTmp = time();
        $linkRedirect = '';
        $pathFile = vncore_file_upload($data['file'], 'tmp', $pathFolder = $pathTmp)['pathFile'] ?? '';

        if (!is_writable(storage_path('tmp'))) {
            return response()->json(['error' => 1, 'msg' => 'No write permission '.storage_path('tmp')]);
        }
        
        if ($pathFile) {
            $unzip = vncore_unzip(storage_path('tmp/'.$pathFile), storage_path('tmp/'.$pathTmp));
            if ($unzip) {
                $checkConfig = glob(storage_path('tmp/'.$pathTmp) . '/*/config.json');
                if ($checkConfig) {
                    $folderName = explode('/config.json', $checkConfig[0]);
                    $folderName = explode('/', $folderName[0]);
                    $folderName = end($folderName);
                    
                    //Check compatibility 
                    $config = json_decode(file_get_contents($checkConfig[0]), true);
                    $scartVersion = $config['scartVersion'] ?? '';
                    if (!vncore_plugin_compatibility_check($scartVersion)) {
                        File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                        return redirect()->back()->with('error', vncore_language_render('admin.plugin.not_compatible', ['version' => $scartVersion, 'vncore_version' => config('vncore.core')]));
                    }

                    $configGroup = $config['configGroup'] ?? '';
                    $configKey = $config['configKey'] ?? '';

                    //Process if plugin config incorect
                    if (!$configGroup || !$configKey) {
                        File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                        return redirect()->back()->with('error', vncore_language_render('admin.plugin.error_config_format'));
                    }
                    //Check plugin exist
                    $arrPluginLocal = vncore_get_all_plugin();
                    if (array_key_exists($configKey, $arrPluginLocal)) {
                        File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                        return redirect()->back()->with('error', vncore_language_render('admin.plugin.error_exist'));
                    }

                    $pathPlugin = $configGroup.'/'.$configKey;

                    if (!is_writable(public_path($configGroup))) {
                        return response()->json(['error' => 1, 'msg' => 'No write permission '.public_path($configGroup)]);
                    }
            
                    if (!is_writable(app_path($configGroup))) {
                        return response()->json(['error' => 1, 'msg' => 'No write permission '.app_path($configGroup)]);
                    }

                    try {
                        File::copyDirectory(storage_path('tmp/'.$pathTmp.'/'.$folderName.'/public'), public_path($pathPlugin));
                        File::copyDirectory(storage_path('tmp/'.$pathTmp.'/'.$folderName), app_path($pathPlugin));
                        File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                        $namespace = vncore_get_class_plugin_config($configKey);
                        $response = (new $namespace)->install();
                        if (!is_array($response) || $response['error'] == 1) {
                            return redirect()->back()->with('error', $response['msg']);
                        }
                        $linkRedirect = route('admin_plugin');
                    } catch (\Throwable $e) {
                        File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                        return redirect()->back()->with('error', $e->getMessage());
                    }
                } else {
                    File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                    return redirect()->back()->with('error', vncore_language_render('admin.plugin.error_check_config'));
                }
            } else {
                return redirect()->back()->with('error', vncore_language_render('admin.plugin.error_unzip'));
            }
        } else {
            return redirect()->back()->with('error', vncore_language_render('admin.plugin.error_upload'));
        }
        if ($linkRedirect) {
            return redirect($linkRedirect)->with('success', vncore_language_render('admin.plugin.import_success'));
        } else {
            return redirect()->back()->with('success', vncore_language_render('admin.plugin.import_success'));
        }
    }
}
