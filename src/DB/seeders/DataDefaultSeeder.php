<?php

namespace Vncore\Core\DB\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DataDefaultSeeder extends Seeder
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
    public function run()
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
            ['id' => 65,'parent_id' => 0,'sort' => 250,'title' => 'admin.menu_titles.ADMIN_SHOP_SETTING','icon' => 'fas fa-store-alt','uri' => '','key' => 'ADMIN_SHOP_SETTING','type' => 0],
            ['id' => 8,'parent_id' => 0,'sort' => 300,'title' => 'admin.menu_titles.ADMIN_EXTENSION','icon' => 'fas fa-th','uri' => '','key' => 'ADMIN_EXTENSION','type' => 0],

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
            ['id' => 69,'parent_id' => 30,'sort' => 2,'title' => 'admin.menu_titles.language_manager','icon' => 'fa fa-universal-access','uri' => 'admin::language_manager','key' => null,'type' => 0],

            //Setting store
            ['id' => 26,'parent_id' => 65,'sort' => 1,'title' => 'admin.menu_titles.store_info','icon' => 'fas fa-h-square','uri' => 'admin::store_info','key' => null,'type' => 0],
            ['id' => 57,'parent_id' => 65,'sort' => 2,'title' => 'admin.menu_titles.store_config','icon' => 'fas fa-cog','uri' => 'admin::store_config','key' => null,'type' => 0],
            ['id' => 60,'parent_id' => 65,'sort' => 3,'title' => 'admin.menu_titles.store_maintain','icon' => 'fas fa-wrench','uri' => 'admin::store_maintain','key' => null,'type' => 0],

            //Extension
            ['id' => 4,'parent_id' => 8,'sort' => 201,'title' => 'admin.menu_titles.template_layout','icon' => 'fab fa-windows','uri' => 'admin::template','key' => 'TEMPLATE','type' => 0],
            ['id' => 35,'parent_id' => 8,'sort' => 202,'title' => 'admin.menu_titles.plugin','icon' => 'fas fa-puzzle-piece','uri' => 'admin::plugin','key' => 'PLUGIN','type' => 0],

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

    }

}
