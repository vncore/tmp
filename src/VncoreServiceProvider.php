<?php

namespace Vncore\Core;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Vncore\Core\Admin\Models\AdminStore;
use Vncore\Core\Commands\Customize;
use Vncore\Core\Commands\Backup;
use Vncore\Core\Commands\Restore;
use Vncore\Core\Commands\Make;
use Vncore\Core\Commands\Infomation;
use Vncore\Core\Commands\Initial;
use Vncore\Core\Commands\ClearCart;
use Vncore\Core\Commands\Update;
use Vncore\Core\Front\Middleware\Localization;
use Vncore\Core\Front\Middleware\EmailIsVerified;
use Vncore\Core\Api\Middleware\ApiConnection;
use Vncore\Core\Api\Middleware\ForceJsonResponse;
use Vncore\Core\Front\Middleware\CheckDomain;
use Vncore\Core\Admin\Middleware\Authenticate;
use Vncore\Core\Admin\Middleware\LogOperation;
use Vncore\Core\Admin\Middleware\PermissionMiddleware;
use Vncore\Core\Admin\Middleware\AdminStoreId;
use Vncore\Core\Admin\Middleware\AdminTheme;
use Laravel\Sanctum\Sanctum;
use Vncore\Core\Admin\Models\PersonalAccessToken;
use Illuminate\Pagination\Paginator;

class VncoreServiceProvider extends ServiceProvider
{
    protected $commands = [
        Backup::class,
        Restore::class,
        Make::class,
        Infomation::class,
        ClearCart::class,
        Update::class,
        Customize::class,
    ];

    protected $install = [
        Initial::class,
    ];

    protected function initial() {
        $this->loadTranslationsFrom(__DIR__.'/Lang', 'vncore');
        try {
            $this->commands($this->install);
        } catch (\Throwable $e) {
            $msg = '#VNCORE:01::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
            echo $msg;
            exit;
        }
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->initial();

        if (VNCORE_ACTIVE == 1 && !file_exists(public_path('vncore-install.php'))) {
            Paginator::useBootstrap();
            //If env is production, then disable debug mode
            if (config('app.env') === 'production') {
                config(['app.debug' => false]);
            }
            
           Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    
            //Load helper from front
            try {
                foreach (glob(__DIR__.'/Library/Helpers/*.php') as $filename) {
                    require_once $filename;
                }
            } catch (\Throwable $e) {
                $msg = '#SC001::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                // vncore_report($msg);
                echo $msg;
                exit;
            }

            if(file_exists(base_path('bootstrap/cache/routes-v7.php'))) {
                echo ('<div style="color:red;font-size:10px; background:black;z-index:99999;position:fixed; top:1px;">Sorry!! SC cannot use route cache. Please delete the file "bootstrap/cache/routes-v7.php" or use the command "php artisan route:clear""</div>');
            }

            //Check connection
            try {
                DB::connection(VNCORE_DB_CONNECTION)->getPdo();
            } catch (\Throwable $e) {
                $msg = '#SC003::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }
            //Load Plugin Provider
            try {
                foreach (glob(base_path() . '/vncore/Plugins/*/Provider.php') as $filename) {
                    require_once $filename;
                }
            } catch (\Throwable $e) {
                $msg = '#SC004::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            //Boot process S-Cart
            try {
                $this->bootScart();
            } catch (\Throwable $e) {
                $msg = '#SC005::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            //Route Admin
            try {
                if (file_exists($routes = __DIR__.'/Admin/routes.php')) {
                    $this->loadRoutesFrom($routes);
                }
            } catch (\Throwable $e) {
                $msg = '#SC006::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            //Route Api
            try {
                if (vncore_config_global('api_mode')) {
                    if (file_exists($routes = __DIR__.'/Api/routes.php')) {
                        $this->loadRoutesFrom($routes);
                    }
                }
            } catch (\Throwable $e) {
                $msg = '#SC007::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            //Route Front
            try {
                if (file_exists($routes = __DIR__.'/Front/routes.php')) {
                    $this->loadRoutesFrom($routes);
                }
            } catch (\Throwable $e) {
                $msg = '#SC008::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }
            
            try {
                $this->registerPublishing();
            } catch (\Throwable $e) {
                $msg = '#SC009::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }
            
            try {
                $this->registerRouteMiddleware();
            } catch (\Throwable $e) {
                $msg = '#SC010::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }
            
            try {
                $this->commands($this->commands);
            } catch (\Throwable $e) {
                $msg = '#SC011::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }
            
            try {
                $this->validationExtend();
            } catch (\Throwable $e) {
                $msg = '#SC012::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }
        }

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
     
        $this->mergeConfigFrom(__DIR__.'/Config/vncore-config.php', 'vncore-config');
        $this->mergeConfigFrom(__DIR__.'/Config/lfm.php', 'lfm');
        $this->mergeConfigFrom(__DIR__.'/Config/vncore.php', 'vncore');
        $this->loadViewsFrom(__DIR__.'/Views/admin', 'vncore-admin');
        $this->loadViewsFrom(__DIR__.'/Views/front', 'vncore-front');
        
        if (file_exists(__DIR__.'/Library/Const.php')) {
            require_once(__DIR__.'/Library/Const.php');
        } 
    }

    public function bootScart()
    {
        // Set store id
        // Default is domain root
        $storeId = VNCORE_ID_ROOT;

        //End process multi store
        config(['app.storeId' => $storeId]);
        // end set store Id

        config(['auth.guards.admin' => [
            'driver'   => 'session',
            'provider' => 'admin',
        ]]);
        config(['auth.guards.api' => [
            'driver'   => 'sanctum',
            'provider' => 'users',
        ]]);
        config(['auth.guards.admin-api' => [
            'driver'   => 'sanctum',
            'provider' => 'admins',
        ]]);
        config(['auth.providers.admin' => [
            'driver' => 'eloquent',
            'model'  => \Vncore\Core\Admin\Models\AdminUser::class,
        ]]);
        config(['auth.passwords.admins' => [
            'provider' => 'admins',
            'table'    => env('VNCORE_DB_PREFIX', '').'admin_password_resets',
            'expire'   => 60,
        ]]);

        if (vncore_config_global('LOG_SLACK_WEBHOOK_URL')) {
            config(['logging.channels.slack.url' => vncore_config_global('LOG_SLACK_WEBHOOK_URL')]);
        }

        //Config language url
        config(['app.seoLang' => (vncore_config_global('url_seo_lang') ? '{lang?}/' : '')]);

        //Title app
        config(['app.name' => vncore_store('title')]);

        //Config for  email
        if (
            // Default use smtp mode for for supplier if use multi-store
            ($storeId != VNCORE_ID_ROOT && vncore_check_multi_shop_installed())
            ||
            // Use smtp config from admin if root domain have smtp_mode enable
            ($storeId == VNCORE_ID_ROOT && vncore_config_global('smtp_mode'))
            ) {
            $smtpHost     = vncore_config('smtp_host');
            $smtpPort     = vncore_config('smtp_port');
            $smtpSecurity = vncore_config('smtp_security');
            $smtpUser     = vncore_config('smtp_user');
            $smtpPassword = vncore_config('smtp_password');
            $smtpName     = vncore_config('smtp_name');
            $smtpFrom     = vncore_config('smtp_from');
            config(['mail.default'                 => 'smtp']);
            config(['mail.mailers.smtp.host'       => $smtpHost]);
            config(['mail.mailers.smtp.port'       => $smtpPort]);
            config(['mail.mailers.smtp.encryption' => $smtpSecurity]);
            config(['mail.mailers.smtp.username'   => $smtpUser]);
            config(['mail.mailers.smtp.password' => $smtpPassword]);
            config(['mail.from.address' => ($smtpFrom ?? vncore_store('email'))]);
            config(['mail.from.name' => ($smtpName ?? vncore_store('title'))]);
        } else {
            //Set default
            config(['mail.from.address' => (config('mail.from.address')) ? config('mail.from.address'): vncore_store('email')]);
            config(['mail.from.name' => (config('mail.from.name')) ? config('mail.from.name'): vncore_store('title')]);
        }
        //email

        //Share variable for view
        view()->share('vncore_languages', vncore_language_all());
        view()->share('vncore_templatePath', 'templates.' . vncore_store('template'));
        view()->share('vncore_templateFile', 'templates/' . vncore_store('template'));
        //
        view()->share('vncore_templatePathAdmin', config('vncore-config.admin.path_view'));
    }

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'localization'     => Localization::class,
        'email.verify'     => EmailIsVerified::class,
        'api.connection'   => ApiConnection::class,
        'checkdomain'      => CheckDomain::class,
        'json.response'    => ForceJsonResponse::class,
        //Admin
        'admin.auth'       => Authenticate::class,
        'admin.log'        => LogOperation::class,
        'admin.permission' => PermissionMiddleware::class,
        'admin.storeId'    => AdminStoreId::class,
        'admin.theme'      => AdminTheme::class,
        //Sanctum
        'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
        'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected function middlewareGroups()
    {
        return [
            'admin'           => config('vncore-config.middleware.admin'),
            'front'           => config('vncore-config.middleware.front'),
            'api.extend'      => config('vncore-config.middleware.api_extend'),
        ];
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups() as $key => $middleware) {
            app('router')->middlewareGroup($key, array_values($middleware));
        }
    }


    /**
     * Validattion extend
     *
     * @return  [type]  [return description]
     */
    protected function validationExtend()
    {
        //
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/public/admin'  => public_path('vncore-static')], 'vncore:static');
            $this->publishes([__DIR__.'/vncore-install.php'  => public_path('vncore-install.php')], 'vncore:file-install');
            $this->publishes([__DIR__.'/Views/admin'  => resource_path('views/vendor/vncore-admin')], 'vncore:view-admin');
            $this->publishes([__DIR__.'/Views/front'  => resource_path('views/vendor/vncore-front')], 'vncore:view-front');
            $this->publishes([__DIR__.'/Config/vncore-config.php' => config_path('vncore-config.php')], 'vncore:config');
            $this->publishes([__DIR__.'/Config/lfm.php' => config_path('lfm.php')], 'vncore:config-lfm');
        }
    }
}
