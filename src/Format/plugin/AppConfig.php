<?php
/**
 * Plugin format 1.0
 */
#App\Vncore\Plugins\Plugin_Key\AppConfig.php
namespace App\Vncore\Plugins\Plugin_Key;

use App\Vncore\Plugins\Plugin_Key\Models\PluginModel;
use Vncore\Core\Admin\Models\AdminConfig;
use Vncore\Core\PluginConfigDefault;
class AppConfig extends PluginConfigDefault
{
    public function __construct()
    {
        //Read config from config.json
        $config = file_get_contents(__DIR__.'/config.json');
        $config = json_decode($config, true);
    	$this->configGroup = $config['configGroup'];
        $this->configKey = $config['configKey'];
        $this->vncoreVersion = $config['vncoreVersion'];
        //Path
        $this->pathPlugin = $this->configGroup . '/' . $this->configKey;
        //Language
        $this->title = trans($this->pathPlugin.'::lang.title');
        //Image logo or thumb
        $this->image = $this->pathPlugin.'/'.$config['image'];
        //
        $this->version = $config['version'];
        $this->auth = $config['auth'];
        $this->link = $config['link'];
    }

    public function install()
    {
        $return = ['error' => 0, 'msg' => ''];
        $check = AdminConfig::where('key', $this->configKey)->first();
        if ($check) {
            //Check Plugin key exist
            $return = ['error' => 1, 'msg' =>  vncore_language_render('admin.plugin.plugin_exist')];
        } else {
            //Insert plugin to config
            $dataInsert = [
                [
                    'group'  => $this->configGroup,
                    'key'    => $this->configKey,
                    'code'    => $this->configKey,
                    'sort'   => 0,
                    'store_id' => VNCORE_ID_GLOBAL,
                    'value'  => self::ON, //Enable extension
                    'detail' => $this->pathPlugin.'::lang.title',
                ],
            ];
            $process = AdminConfig::insert(
                $dataInsert
            );

            /*Insert plugin's html elements into index of admin pages
            Detail: https://vncore.net/docs/master/create-new-a-plugin.html 
            */

            // AdminConfig::insert(
            //     [
            //         /*
            //         This is where the html content of the Plugin appears
            //         group_of_layout allow:
            //         Position include "topMenuRight, topMenuLeft, menuLeft,menuRight, blockBottom" -> Show on all index pages in admin with corresponding position as above.
            //         or Position_route_name_of_admin_page. Example menuLeft__admin_product.index, topMenuLeft__admin_order.index
            //         */
            //         'group' => 'group_of_layout',
            //         /*
            //         code is value option
            //         */
            //         'code' => 'code_config_of_plugin',
            //         'key' => 'key_with_value_unique', //
            //         'sort' => 0, // int value
            //         'value' => 'html content or view::path_to_view', // allow html or view::path_to_view
            //         'detail' => '',
            //     ]
            // );
            if (!$process) {
                $return = ['error' => 1, 'msg' => vncore_language_render('admin.plugin.install_faild')];
            } else {
                $return = (new PluginModel)->installExtension();
            }
        }

        return $return;
    }

    public function uninstall()
    {
        $return = ['error' => 0, 'msg' => ''];
        //Please delete all values inserted in the installation step
        $process = (new AdminConfig)
            ->where('key', $this->configKey)
            ->orWhere('code', $this->configKey.'_config')
            ->delete();
        if (!$process) {
            $return = ['error' => 1, 'msg' => vncore_language_render('admin.plugin.action_error', ['action' => 'Uninstall'])];
        }
        (new PluginModel)->uninstallExtension();
        return $return;
    }
    
    public function enable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new AdminConfig)->where('key', $this->configKey)->update(['value' => self::ON]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error enable'];
        }
        return $return;
    }

    public function disable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new AdminConfig)
            ->where('key', $this->configKey)
            ->update(['value' => self::OFF]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error disable'];
        }
        return $return;
    }

    public function config()
    {
        //redirect to url config of plugin
        return;
    }

    /**
     * Get info plugin
     *
     * @return  [type]  [return description]
     */
    public function getInfo()
    {
        $arrData = [
            'title' => $this->title,
            'key' => $this->configKey,
            'image' => $this->image,
            'permission' => self::ALLOW,
            'version' => $this->version,
            'auth' => $this->auth,
            'link' => $this->link,
            'value' => 0, // this return need for plugin shipping
            'pathPlugin' => $this->pathPlugin
        ];

        return $arrData;
    }
}
