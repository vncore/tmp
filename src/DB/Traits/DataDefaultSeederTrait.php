<?php

namespace Vncore\Core\DB\Traits;
use Illuminate\Support\Str;
use DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait DataDefaultSeederTrait
{
    public $adminUser = 'admin';
    //admin
    public $adminPassword = '$2y$10$JcmAHe5eUZ2rS0jU1GWr/.xhwCnh2RU13qwjTPcqfmtZXjZxcryPO';
    public $adminEmail = 'your-email@your-domain.com';
    public $language_default = 'en';
    public $title_en = 'Demo S-Cart : Free Laravel eCommerce';
    public $title_vi = 'Demo S-Cart: Mã nguồn website thương mại điện tử miễn phí';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function runProcess()
    {
        $this->updateDataVersion();

        $db = DB::connection(SC_CONNECTION);
        
        if (!empty(session('infoInstall')['admin_user'])) {
            $this->adminUser = session('infoInstall')['admin_user'];
        }
        if (!empty(session('infoInstall')['admin_password'])) {
            $this->adminPassword = session('infoInstall')['admin_password'];
        }
        if (!empty(session('infoInstall')['admin_email'])) {
            $this->adminEmail = session('infoInstall')['admin_email'];
        }
        if (!empty(session('infoInstall')['website_title'])) {
            $this->title_en = session('infoInstall')['website_title'];
            $this->title_vi = session('infoInstall')['website_title'];
        }

        if (!empty(session('infoInstall')['language_default'])) {
            $this->language_default = session('infoInstall')['language_default'];
        }

        $dataMenu = $this->dataMenu();
        $db->table(SC_DB_PREFIX.'admin_menu')->insertOrIgnore($dataMenu);
        
        $dataAdminPermission = $this->dataAdminPermission(SC_ADMIN_PREFIX);
        $db->table(SC_DB_PREFIX.'admin_permission')->insertOrIgnore($dataAdminPermission);
        
        $dataAdminRole = $this->dataAdminRole();
        $db->table(SC_DB_PREFIX.'admin_role')->insertOrIgnore($dataAdminRole);
        
        $dataAdminRolePermission = $this->dataAdminRolePermission();
        $db->table(SC_DB_PREFIX.'admin_role_permission')->insertOrIgnore($dataAdminRolePermission);

        $dataAdminRoleUser = $this->dataAdminRoleUser();
        $db->table(SC_DB_PREFIX.'admin_role_user')->insertOrIgnore($dataAdminRoleUser);

        $dataAdminUser = $this->dataAdminUser($this->adminUser, $this->adminPassword, $this->adminEmail);
        $db->table(SC_DB_PREFIX.'admin_user')->insertOrIgnore($dataAdminUser);

        $dataAdminConfig = $this->dataAdminConfig();
        $db->table(SC_DB_PREFIX.'admin_config')->insertOrIgnore($dataAdminConfig);

        $dataAdminStore = $this->dataAdminStore($this->adminEmail, $this->language_default, str_replace(['http://','https://', '/install.php'], '', url('/')));
        $db->table(SC_DB_PREFIX.'admin_store')->insertOrIgnore($dataAdminStore);

        $dataAdminStoreDescription = $this->dataAdminStoreDescription($this->title_en, $this->title_vi);
        $db->table(SC_DB_PREFIX.'admin_store_description')->insertOrIgnore($dataAdminStoreDescription);

        $dataShopLang = $this->dataShopLang();
        $db->table(SC_DB_PREFIX.'shop_language')->insertOrIgnore($dataShopLang);

    }

    public function dataMenu() {
        $dataMenu = [
            ['id' => 9,'parent_id' => 0,'sort' => 400,'title' => 'admin.menu_titles.ADMIN_SYSTEM','icon' => 'fas fa-cogs','uri' => '','key' => 'ADMIN_SYSTEM','type' => 0],
            ['id' => 7,'parent_id' => 0,'sort' => 100,'title' => 'admin.menu_titles.ADMIN_CONTENT','icon' => 'fas fa-file-signature','uri' => '','key' => 'ADMIN_CONTENT','type' => 0],
            ['id' => 25,'parent_id' => 0,'sort' => 200,'title' => 'admin.menu_titles.ADMIN_MARKETING','icon' => 'fas fa-sort-amount-up','uri' => '','key' => 'MARKETING','type' => 0],
            ['id' => 65,'parent_id' => 0,'sort' => 250,'title' => 'admin.menu_titles.ADMIN_SHOP_SETTING','icon' => 'fas fa-store-alt','uri' => '','key' => 'ADMIN_SHOP_SETTING','type' => 0],
            ['id' => 8,'parent_id' => 0,'sort' => 300,'title' => 'admin.menu_titles.ADMIN_EXTENSION','icon' => 'fas fa-th','uri' => '','key' => 'ADMIN_EXTENSION','type' => 0],
            ['id' => 3,'parent_id' => 25,'sort' => 3,'title' => 'admin.menu_titles.customer_manager','icon' => 'fas fa-users','uri' => '','key' => 'CUSTOMER_MANAGER','type' => 0],

            //System
            ['id' => 5,'parent_id' => 9,'sort' => 2,'title' => 'admin.menu_titles.admin_global','icon' => 'fab fa-whmcs','uri' => '','key' => 'CONFIG_SYSTEM','type' => 0],
            ['id' => 28,'parent_id' => 9,'sort' => 4,'title' => 'admin.menu_titles.error_log','icon' => 'far fa-clone','uri' => '','key' => '','type' => 0],
            ['id' => 30,'parent_id' => 9,'sort' => 5,'title' => 'admin.menu_titles.localisation','icon' => 'fa fa-map-signs','uri' => '','key' => null,'type' => 0],
            ['id' => 38,'parent_id' => 9,'sort' => 1,'title' => 'admin.menu_titles.user_permission','icon' => 'fas fa-users-cog','uri' => '','key' => 'ADMIN','type' => 0],
            ['id' => 70,'parent_id' => 9,'sort' => 6,'title' => 'admin.menu_titles.security','icon' => 'fab fa-shirtsinbulk','uri' => '','key' => 'ADMIN_SECURITY','type' => 0],

            ['id' => 34,'parent_id' => 5,'sort' => 5,'title' => 'admin.menu_titles.backup_restore','icon' => 'fas fa-save','uri' => 'admin::backup','key' => null,'type' => 0],
            ['id' => 49,'parent_id' => 5,'sort' => 0,'title' => 'admin.menu_titles.menu','icon' => 'fas fa-bars','uri' => 'admin::menu','key' => null,'type' => 0],
            ['id' => 58,'parent_id' => 5,'sort' => 5,'title' => 'admin.menu_titles.cache_manager','icon' => 'fab fa-tripadvisor','uri' => 'admin::cache_config','key' => null,'type' => 0],

            ['id' => 36,'parent_id' => 70,'sort' => 2,'title' => 'admin.menu_titles.webhook','icon' => 'fab fa-diaspora','uri' => 'admin::config/webhook','key' => null,'type' => 0],
            ['id' => 50,'parent_id' => 70,'sort' => 0,'title' => 'admin.menu_titles.operation_log','icon' => 'fas fa-history','uri' => 'admin::log','key' => null,'type' => 0],
            ['id' => 55,'parent_id' => 70,'sort' => 3,'title' => 'admin.menu_titles.password_policy','icon' => 'fa fa-unlock','uri' => 'admin::password_policy','key' => null,'type' => 0],

            ['id' => 46,'parent_id' => 38,'sort' => 0,'title' => 'admin.menu_titles.users','icon' => 'fas fa-users','uri' => 'admin::user','key' => null,'type' => 0],
            ['id' => 47,'parent_id' => 38,'sort' => 0,'title' => 'admin.menu_titles.roles','icon' => 'fas fa-user-tag','uri' => 'admin::role','key' => null,'type' => 0],
            ['id' => 48,'parent_id' => 38,'sort' => 0,'title' => 'admin.menu_titles.permission','icon' => 'fas fa-ban','uri' => 'admin::permission','key' => null,'type' => 0],

            ['id' => 31,'parent_id' => 30,'sort' => 1,'title' => 'admin.menu_titles.language','icon' => 'fas fa-language','uri' => 'admin::language','key' => null,'type' => 0],
            ['id' => 32,'parent_id' => 30,'sort' => 3,'title' => 'admin.menu_titles.currency','icon' => 'far fa-money-bill-alt','uri' => 'admin::currency','key' => null,'type' => 0],
            ['id' => 69,'parent_id' => 30,'sort' => 2,'title' => 'admin.menu_titles.language_manager','icon' => 'fa fa-universal-access','uri' => 'admin::language_manager','key' => null,'type' => 0],

            //Cms
            ['id' => 10,'parent_id' => 7,'sort' => 102,'title' => 'admin.menu_titles.page_manager','icon' => 'fas fa-clone','uri' => 'admin::page','key' => null,'type' => 0],
            ['id' => 33,'parent_id' => 7,'sort' => 101,'title' => 'admin.menu_titles.banner','icon' => 'fas fa-image','uri' => 'admin::banner','key' => null,'type' => 0],
            ['id' => 52,'parent_id' => 7,'sort' => 103,'title' => 'admin.menu_titles.news','icon' => 'far fa-file-powerpoint','uri' => 'admin::news','key' => null,'type' => 0],

            //Setting store
            ['id' => 26,'parent_id' => 65,'sort' => 1,'title' => 'admin.menu_titles.store_info','icon' => 'fas fa-h-square','uri' => 'admin::store_info','key' => null,'type' => 0],
            ['id' => 57,'parent_id' => 65,'sort' => 2,'title' => 'admin.menu_titles.store_config','icon' => 'fas fa-cog','uri' => 'admin::store_config','key' => null,'type' => 0],
            ['id' => 60,'parent_id' => 65,'sort' => 3,'title' => 'admin.menu_titles.store_maintain','icon' => 'fas fa-wrench','uri' => 'admin::store_maintain','key' => null,'type' => 0],
            ['id' => 67,'parent_id' => 65,'sort' => 5,'title' => 'admin.menu_titles.layout','icon' => 'far fa-object-group','uri' => '','key' => null,'type' => 0],
            ['id' => 22,'parent_id' => 67,'sort' => 1,'title' => 'admin.menu_titles.block_content','icon' => 'far fa-newspaper','uri' => 'admin::store_block','key' => null,'type' => 0],
            ['id' => 23,'parent_id' => 67,'sort' => 2,'title' => 'admin.menu_titles.block_link','icon' => 'fab fa-chrome','uri' => 'admin::store_link','key' => null,'type' => 0],
            ['id' => 44,'parent_id' => 67,'sort' => 3,'title' => 'admin.menu_titles.css','icon' => 'far fa-file-code','uri' => 'admin::store_css','key' => null,'type' => 0],

            //Marketing
            ['id' => 29,'parent_id' => 25,'sort' => 0,'title' => 'admin.menu_titles.email_template','icon' => 'fas fa-bars','uri' => 'admin::email_template','key' => null,'type' => 0],
            ['id' => 45,'parent_id' => 25,'sort' => 4,'title' => 'admin.menu_titles.seo_manager','icon' => 'fab fa-battle-net','uri' => '','key' => 'SEO_MANAGER','type' => 0],
            ['id' => 51,'parent_id' => 45,'sort' => 0,'title' => 'admin.menu_titles.seo_config','icon' => 'fas fa-bars','uri' => 'admin::seo/config','key' => null,'type' => 0],

            //Extension
            ['id' => 4,'parent_id' => 8,'sort' => 201,'title' => 'admin.menu_titles.template_layout','icon' => 'fab fa-windows','uri' => '','key' => 'TEMPLATE','type' => 0],
            ['id' => 35,'parent_id' => 8,'sort' => 202,'title' => 'admin.menu_titles.plugin','icon' => 'fas fa-puzzle-piece','uri' => '','key' => 'PLUGIN','type' => 0],
            ['id' => 42,'parent_id' => 35,'sort' => 100,'title' => 'vncore::admin.menu_titles.plugin_other','icon' => 'far fa-circle','uri' => 'admin::plugin/other','key' => null,'type' => 0],
            ['id' => 43,'parent_id' => 35,'sort' => 4,'title' => 'vncore::admin.menu_titles.plugin_cms','icon' => 'fab fa-modx','uri' => 'admin::plugin/cms','key' => null,'type' => 0],
            ['id' => 24,'parent_id' => 4,'sort' => 0,'title' => 'admin.menu_titles.template','icon' => 'fas fa-columns','uri' => 'admin::template','key' => null,'type' => 0],

            //Customer
            ['id' => 21,'parent_id' => 3,'sort' => 0,'title' => 'admin.menu_titles.subscribe','icon' => 'fas fa-user-circle','uri' => 'admin::subscribe','key' => null,'type' => 0],

        ];

        // If use ecommerce
        if (config('vncore.ecommerce_mode', 1)) {
            $dataMenu = array_merge($dataMenu, $this->dataMenuShop());
        }
        return $dataMenu;
    }

    public function dataMenuShop() {
        $dataMenu = [
            ['id' => 6,'parent_id' => 0,'sort' => 10,'title' => 'admin.menu_titles.ADMIN_SHOP','icon' => 'fab fa-shopify','uri' => '','key' => 'ADMIN_SHOP','type' => 0],
            ['id' => 1,'parent_id' => 6,'sort' => 1,'title' => 'admin.menu_titles.order_manager','icon' => 'fas fa-cart-arrow-down','uri' => '','key' => 'ORDER_MANAGER','type' => 0],
            ['id' => 2,'parent_id' => 6,'sort' => 2,'title' => 'admin.menu_titles.catalog_mamager','icon' => 'fas fa-folder-open','uri' => '','key' => 'CATALOG_MANAGER','type' => 0],
            ['id' => 12,'parent_id' => 1,'sort' => 3,'title' => 'admin.menu_titles.order','icon' => 'fas fa-shopping-cart','uri' => 'admin::order','key' => null,'type' => 0],
            ['id' => 15,'parent_id' => 2,'sort' => 0,'title' => 'admin.menu_titles.product','icon' => 'far fa-file-image','uri' => 'admin::product','key' => null,'type' => 0],
            ['id' => 16,'parent_id' => 2,'sort' => 0,'title' => 'admin.menu_titles.category','icon' => 'fas fa-folder-open','uri' => 'admin::category','key' => null,'type' => 0],
            ['id' => 20,'parent_id' => 3,'sort' => 0,'title' => 'admin.menu_titles.customer','icon' => 'fas fa-user','uri' => 'admin::customer','key' => null,'type' => 0],
            ['id' => 37,'parent_id' => 25,'sort' => 5,'title' => 'admin.menu_titles.report_manager','icon' => 'fas fa-chart-pie','uri' => '','key' => 'REPORT_MANAGER','type' => 0],
            ['id' => 39,'parent_id' => 35,'sort' => 0,'title' => 'vncore::admin.menu_titles.plugin_payment','icon' => 'far fa-money-bill-alt','uri' => 'admin::plugin/payment','key' => null,'type' => 0],
            ['id' => 40,'parent_id' => 35,'sort' => 1,'title' => 'vncore::admin.menu_titles.plugin_shipping','icon' => 'fas fa-ambulance','uri' => 'admin::plugin/shipping','key' => null,'type' => 0],
            ['id' => 41,'parent_id' => 35,'sort' => 2,'title' => 'vncore::admin.menu_titles.plugin_total','icon' => 'fas fa-atom','uri' => 'admin::plugin/total','key' => null,'type' => 0],
            ['id' => 53,'parent_id' => 35,'sort' => 3,'title' => 'vncore::admin.menu_titles.plugin_fee','icon' => 'fas fa-box','uri' => 'admin::plugin/fee','key' => null,'type' => 0],
            ['id' => 54,'parent_id' => 37,'sort' => 0,'title' => 'admin.menu_titles.report_product','icon' => 'fas fa-bars','uri' => 'admin::report/product','key' => null,'type' => 0],
            ['id' => 27,'parent_id' => 65,'sort' => 4,'title' => 'admin.menu_titles.setting_system','icon' => 'fas fa-tools','uri' => '','key' => 'SETTING_SYSTEM','type' => 0],
            ['id' => 11,'parent_id' => 27,'sort' => 2,'title' => 'admin.menu_titles.shipping_status','icon' => 'fas fa-truck','uri' => 'admin::shipping_status','key' => null,'type' => 0],
            ['id' => 13,'parent_id' => 27,'sort' => 1,'title' => 'admin.menu_titles.order_status','icon' => 'fas fa-asterisk','uri' => 'admin::order_status','key' => null,'type' => 0],
            ['id' => 14,'parent_id' => 27,'sort' => 3,'title' => 'admin.menu_titles.payment_status','icon' => 'fas fa-recycle','uri' => 'admin::payment_status','key' => null,'type' => 0],
            ['id' => 17,'parent_id' => 2,'sort' => 4,'title' => 'admin.menu_titles.supplier','icon' => 'fas fa-user-secret','uri' => 'admin::supplier','key' => null,'type' => 0],
            ['id' => 18,'parent_id' => 2,'sort' => 5,'title' => 'admin.menu_titles.brand','icon' => 'fas fa-university','uri' => 'admin::brand','key' => null,'type' => 0],
            ['id' => 19,'parent_id' => 27,'sort' => 8,'title' => 'admin.menu_titles.attribute_group','icon' => 'fas fa-bars','uri' => 'admin::attribute_group','key' => null,'type' => 0],
            ['id' => 61,'parent_id' => 27,'sort' => 9,'title' => 'admin.menu_titles.tax','icon' => 'far fa-calendar-minus','uri' => 'admin::tax','key' => null,'type' => 0],
            ['id' => 62,'parent_id' => 27,'sort' => 6,'title' => 'admin.menu_titles.weight','icon' => 'fas fa-balance-scale','uri' => 'admin::weight_unit','key' => null,'type' => 0],
            ['id' => 63,'parent_id' => 27,'sort' => 7,'title' => 'admin.menu_titles.length','icon' => 'fas fa-minus','uri' => 'admin::length_unit','key' => null,'type' => 0],
            ['id' => 68,'parent_id' => 27,'sort' => 5,'title' => 'admin.menu_titles.custom_field','icon' => 'fa fa-american-sign-language-interpreting','uri' => 'admin::custom_field','key' => null,'type' => 0],
            ['id' => 59,'parent_id' => 9,'sort' => 7,'title' => 'admin.menu_titles.api_manager','icon' => 'fas fa-plug','uri' => '','key' => 'API_MANAGER','type' => 0],
            ['id' => 66,'parent_id' => 59,'sort' => 1,'title' => 'admin.menu_titles.api_config','icon' => 'fas fa fa-cog','uri' => 'admin::api_connection','key' => null,'type' => 0],
        ];
        return $dataMenu;
    }

    public function dataAdminPermission($prefix) {
        $dataAdminPermission = [
            ['id' => '1','name' => 'Auth manager','slug' => 'auth.full','http_uri' => 'ANY::'.$prefix.'/auth/*'],
            ['id' => '2','name' => 'Dashboard','slug' => 'dashboard','http_uri' => 'GET::'.$prefix.''],
            ['id' => '3','name' => 'Base setting','slug' => 'base.setting','http_uri' => 'ANY::'.$prefix.'/order_status/*,ANY::'.$prefix.'/shipping_status/*,ANY::'.$prefix.'/payment_status/*,ANY::'.$prefix.'/supplier/*,ANY::'.$prefix.'/brand/*,ANY::'.$prefix.'/custom_field/*,ANY::'.$prefix.'/weight_unit/*,ANY::'.$prefix.'/length_unit/*,ANY::'.$prefix.'/attribute_group/*,ANY::'.$prefix.'/tax/*'],
            ['id' => '4','name' => 'Store manager','slug' => 'store.full','http_uri' => 'ANY::'.$prefix.'/store_info/*,ANY::'.$prefix.'/store_maintain/*,ANY::'.$prefix.'/store_config/*,ANY::'.$prefix.'/store_css/*,ANY::'.$prefix.'/store_block/*,ANY::'.$prefix.'/store_link/*'],
            ['id' => '5','name' => 'Product manager','slug' => 'product.full','http_uri' => 'ANY::'.$prefix.'/product/*,ANY::'.$prefix.'/product_property/*,ANY::'.$prefix.'/product_tag/*'],
            ['id' => '6','name' => 'Category manager','slug' => 'category.full','http_uri' => 'ANY::'.$prefix.'/category/*'],
            ['id' => '7','name' => 'Order manager','slug' => 'order.full','http_uri' => 'ANY::'.$prefix.'/order/*'],
            ['id' => '8','name' => 'Upload management','slug' => 'upload.full','http_uri' => 'ANY::'.$prefix.'/uploads/*'],
            ['id' => '9','name' => 'Extension manager','slug' => 'extension.full','http_uri' => 'ANY::'.$prefix.'/template/*,ANY::'.$prefix.'/plugin/*'],
            ['id' => '10','name' => 'Marketing manager','slug' => 'marketing.full','http_uri' => 'ANY::'.$prefix.'/shop_discount/*,ANY::'.$prefix.'/email_template/*,ANY::'.$prefix.'/customer/*,ANY::'.$prefix.'/subscribe/*,ANY::'.$prefix.'/seo/*'],
            ['id' => '11','name' => 'Report manager','slug' => 'report.full','http_uri' => 'ANY::'.$prefix.'/report/*'],
            ['id' => '12','name' => 'CMS full','slug' => 'cms.full','http_uri' => 'ANY::'.$prefix.'/page/*,ANY::'.$prefix.'/banner/*,ANY::'.$prefix.'/banner_type/*,ANY::'.$prefix.'/cms_category/*,ANY::'.$prefix.'/cms_content/*,ANY::'.$prefix.'/news/*'],
            ['id' => '13','name' => 'Update config','slug' => 'change.config','http_uri' => 'POST::'.$prefix.'/store_config/update'],
        ];
        return $dataAdminPermission;
    }

    public function dataAdminRole() {
        $dataAdminRole = [
            ['id' => '1','name' => 'Administrator','slug' => 'administrator'],
            ['id' => '2','name' => 'Group only View','slug' => 'view.all'],
            ['id' => '3','name' => 'Manager','slug' => 'manager'],
            ['id' => '4','name' => 'CMS','slug' => 'cms'],
            ['id' => '5','name' => 'Accountant','slug' => 'accountant'],
            ['id' => '6','name' => 'Marketing','slug' => 'maketing'],
            ['id' => '7','name' => 'Admin CMS','slug' => 'admin_cms'],
        ];
        return $dataAdminRole;
    }


    public function dataAdminRolePermission() {
        $dataAdminRolePermission = [
            ['role_id' => 3,'permission_id' => 1],
            ['role_id' => 3,'permission_id' => 2],
            ['role_id' => 3,'permission_id' => 3],
            ['role_id' => 3,'permission_id' => 4],
            ['role_id' => 3,'permission_id' => 5],
            ['role_id' => 3,'permission_id' => 6],
            ['role_id' => 3,'permission_id' => 13],
            ['role_id' => 3,'permission_id' => 7],
            ['role_id' => 3,'permission_id' => 8],
            ['role_id' => 3,'permission_id' => 9],
            ['role_id' => 3,'permission_id' => 10],
            ['role_id' => 3,'permission_id' => 11],
            ['role_id' => 3,'permission_id' => 12],
            ['role_id' => 4,'permission_id' => 1],
            ['role_id' => 4,'permission_id' => 12],
            ['role_id' => 5,'permission_id' => 1],
            ['role_id' => 5,'permission_id' => 2],
            ['role_id' => 5,'permission_id' => 7],
            ['role_id' => 5,'permission_id' => 11],
            ['role_id' => 6,'permission_id' => 1],
            ['role_id' => 6,'permission_id' => 2],
            ['role_id' => 6,'permission_id' => 8],
            ['role_id' => 6,'permission_id' => 10],
            ['role_id' => 6,'permission_id' => 11],
            ['role_id' => 6,'permission_id' => 12],
            ['role_id' => 7,'permission_id' => 1],
            ['role_id' => 7,'permission_id' => 4],
            ['role_id' => 7,'permission_id' => 8],
            ['role_id' => 7,'permission_id' => 12],
            ['role_id' => 7,'permission_id' => 13],
        ];
        return $dataAdminRolePermission;
    }

    public function dataAdminRoleUser() {
        $dataAdminRoleUser = [
            ['role_id' => '1','user_id' => '1']
        ];
        return $dataAdminRoleUser;
    }

    public function dataAdminUser($username, $password, $email) {
        $dataAdminUser = [
            ['id' => '1','username' => $username,'password' => $password,'email' => $email,'name' => 'Administrator','avatar' => '/admin/avatar/user.jpg']
        ];
        return $dataAdminUser;
    }

    public function dataAdminConfig() {
        $dataAdminConfig = [
            ['group' => 'Plugins','code' => 'Payment','key' => 'Cash','value' => '1','sort' => '0','detail' => 'Plugins/Payment/Cash::lang.title','store_id' => 0],
            ['group' => 'Plugins','code' => 'Shipping','key' => 'ShippingStandard','value' => '1','sort' => '0','detail' => 'Shipping Standard','store_id' => 0],
            ['group' => 'global','code' => 'seo_config','key' => 'url_seo_lang','value' => '0','sort' => '1','detail' => 'seo.url_seo_lang','store_id' => 0],
            ['group' => 'global','code' => 'webhook_config','key' => 'LOG_SLACK_WEBHOOK_URL','value' => '','sort' => '0','detail' => 'admin.config.LOG_SLACK_WEBHOOK_URL','store_id' => 0],
            ['group' => 'global','code' => 'webhook_config','key' => 'GOOGLE_CHAT_WEBHOOK_URL','value' => '','sort' => '0','detail' => 'admin.config.GOOGLE_CHAT_WEBHOOK_URL','store_id' => 0],
            ['group' => 'global','code' => 'webhook_config','key' => 'CHATWORK_CHAT_WEBHOOK_URL','value' => '','sort' => '0','detail' => 'admin.config.CHATWORK_CHAT_WEBHOOK_URL','store_id' => 0],
            ['group' => 'global','code' => 'api_config','key' => 'api_connection_required','value' => '1','sort' => '1','detail' => 'api_connection.api_connection_required','store_id' => 0],
            ['group' => 'global','code' => 'api_config','key' => 'api_mode','value' => '0','sort' => '1','detail' => 'api_connection.api_mode','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_status','value' => '0','sort' => '0','detail' => 'admin.cache.cache_status','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_time','value' => '600','sort' => '0','detail' => 'admin.cache.cache_time','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_category','value' => '0','sort' => '3','detail' => 'admin.cache.cache_category','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_product','value' => '0','sort' => '4','detail' => 'admin.cache.cache_product','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_news','value' => '0','sort' => '5','detail' => 'admin.cache.cache_news','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_category_cms','value' => '0','sort' => '6','detail' => 'admin.cache.cache_category_cms','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_content_cms','value' => '0','sort' => '7','detail' => 'admin.cache.cache_content_cms','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_page','value' => '0','sort' => '8','detail' => 'admin.cache.cache_page','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_country','value' => '0','sort' => '10','detail' => 'admin.cache.cache_country','store_id' => 0],
            ['group' => 'global','code' => 'env_mail','key' => 'smtp_mode','value' => '','sort' => '0','detail' => 'email.smtp_mode','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_min','value' => '6','sort' => '0','detail' => 'password_policy.customer.min','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_max','value' => '16','sort' => '0','detail' => 'password_policy.customer.max','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_letter','value' => '0','sort' => '1','detail' => 'password_policy.customer.letter','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_mixedcase','value' => '0','sort' => '2','detail' => 'password_policy.customer.mixed','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_number','value' => '0','sort' => '3','detail' => 'password_policy.customer.number','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_symbol','value' => '0','sort' => '4','detail' => 'password_policy.customer.symbol','store_id' => 0],
        ];
        return $dataAdminConfig;
    }

    public function dataAdminStore($email, $language, $domain) {
        $dataAdminStore = [
            ['id' => 1,'logo' => 'data/logo/scart-mid.png','template' => 'vncore-light','phone' => '0123456789','long_phone' => 'Support: 0987654321','email' => $email,'time_active' =>'','address' => '123st - abc - xyz','language' => $language,'currency' => 'USD','code' => 'vncore','domain' => $domain]
        ];
        return $dataAdminStore;
    }

    public function dataAdminStoreDescription($titleE, $titleV) {
        $dataAdminStoreDescription = [
            ['store_id' => SC_ID_ROOT,'lang' => 'en','title' => $titleE,'description' => 'Free website shopping cart for business','keyword' => '','maintain_content' => '<center><img src="/images/maintenance.png" />
            <h3><span style="color:#e74c3c;"><strong>Sorry! We are currently doing site maintenance!</strong></span></h3>
            </center>','maintain_note' => 'Website is in maintenance mode!'],
            ['store_id' => SC_ID_ROOT,'lang' => 'vi','title' => $titleV,'description' => 'Laravel shopping cart for business','keyword' => '','maintain_content' => '<center><img src="/images/maintenance.png" />
            <h3><span style="color:#e74c3c;"><strong>Xin lỗi! Hiện tại website đang bảo trì!</strong></span></h3>
            </center>','maintain_note' => 'Website đang trong chế độ bảo trì!'],
        ];
        return $dataAdminStoreDescription;
    }

    public function dataShopLang() {
        $dataShopLang = [
            ['id' => '1','name' => 'English','code' =>'en','icon' => '/data/language/flag_uk.png','status' => '1','rtl' => '0', 'sort' => '1'],
            ['id' => '2','name' => 'Tiếng Việt','code' => 'vi','icon' => '/data/language/flag_vn.png','status' => '1','rtl' => '0', 'sort' => '2'],
        ];
        return $dataShopLang;
    }

    public function updateDataVersion() {

        $db = DB::connection(SC_CONNECTION);
        $schema = Schema::connection(SC_CONNECTION);
        //Ony use updated v8.0 -> v8.1
        //--Update menu admin
        $db->table(SC_DB_PREFIX.'admin_menu')->where('id','27')->update(['parent_id' => 65]);
        $db->table(SC_DB_PREFIX.'admin_menu')->whereIn('id',['17','18'])->update(['parent_id' => 2]);
        $db->table(SC_DB_PREFIX.'admin_menu')->whereIn('id',['36','50'])->update(['parent_id' => 70]);
        
        // Add column
        if (!$schema->hasColumn(SC_DB_PREFIX.'admin_store', 'og_image')) {
            $schema->table(SC_DB_PREFIX.'admin_store',
                function (Blueprint $table) {
                $table->string('og_image', 255)->nullable()->default('images/org.jpg');
            });
        }

        //Notice
        if (!$schema->hasTable(SC_DB_PREFIX.'admin_notice')) {
            $schema->create(SC_DB_PREFIX . 'admin_notice', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('type')->index()->comment('order, customer, admin,...');
                $table->string('type_id', 36)->index()->nullable();
                $table->integer('status', 0)->default(0)->index();
                $table->string('admin_id', 36)->index();
                $table->string('url', 100);
                $table->text('content');
                $table->timestamps();
            });
        }
        //==End notice
    }

}
