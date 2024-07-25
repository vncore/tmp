<?php
/**
 * @author Lanh Le <lanhktc@gmail.com>
 */
require __DIR__ . '/../../../autoload.php';
$app = include_once __DIR__ . '/../../../../bootstrap/app.php';

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Artisan;
$kernel   = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$lang = request('lang') ?? 'en';
app()->setlocale($lang);
if (request()->method() == 'POST' && request()->ajax()) {

    $step = request('step');
    switch ($step) {
        case 'step1':
            $infoInstall =  [
                'language_default' => request('language_default'),
                'admin_user' => request('admin_user'),
                'admin_password' => bcrypt(request('admin_password')),
                'admin_email' => request('admin_email'),
                'website_title' => request('website_title'),
            ];
            echo json_encode(['error' => 0, 'msg' => trans('vncore::install.init.process_sucess'), 'infoInstall' => $infoInstall]);
            break;

    case 'step2-1':
        session(['infoInstall'=> request('infoInstall')]);
        try {
            Artisan::call('migrate');
            \DB::connection(VNCORE_DB_CONNECTION)->table('migrations')->where('migration', '00_00_00_step1_create_tables_admin')->delete();
            Artisan::call('migrate --path=/vendor/vncore/core/src/DB/migrations/00_00_00_step1_create_tables_admin.php');
        } catch(\Throwable $e) {
            echo json_encode([
                'error' => '1',
                'msg' => '#VNCORE:IS001::'.$e->getMessage(),
            ]);
            break;
        }
        echo json_encode([
            'error' => '0',
            'msg' => trans('vncore::install.database.process_sucess_1'),
            'infoInstall' => request('infoInstall')
        ]);
        break;

        case 'step2-2':
            session(['infoInstall'=> request('infoInstall')]);
            try {
                Artisan::call('db:seed', 
                    [
                        '--class' => '\Vncore\Core\DB\seeders\DataDefaultSeeder',
                        '--force' => true
                    ]
                );
                Artisan::call('db:seed', 
                    [
                        '--class' => '\Vncore\Core\DB\seeders\DataStoreSeeder',
                        '--force' => true
                    ]
                );
            } catch(\Throwable $e) {
                echo json_encode([
                    'error' => '1',
                    'msg' => '#VNCORE:IS002::'.$e->getMessage(),
                ]);
                break;
            }
            echo json_encode([
                'error' => '0',
                'msg' => trans('vncore::install.database.process_sucess_2'),
                'infoInstall' => request('infoInstall')
            ]);
            break;

        case 'step2-3':
            session(['infoInstall'=> request('infoInstall')]);
            try {
                Artisan::call('db:seed', 
                    [
                        '--class' => '\Vncore\Core\DB\seeders\DataLocaleSeeder',
                        '--force' => true
                    ]
                );
            } catch(\Throwable $e) {
                echo json_encode([
                    'error' => '1',
                    'msg' => '#VNCORE:IS003::'.$e->getMessage(),
                ]);
                break;
            }
            session()->forget('infoInstall');
            echo json_encode([
                'error' => '0',
                'msg' => trans('vncore::install.database.process_sucess_3'),
                'infoInstall' => request('infoInstall')
            ]);
            break;

    case 'step3':
        try {
            Artisan::call('storage:link');
        } catch(\Throwable $e) {
            echo json_encode([
                'error' => '1',
                'msg' => '#VNCORE:IS003::'.$e->getMessage(),
            ]);
            break;
        }
        echo json_encode([
            'error' => '0',
            'msg' => trans('vncore::install.link_storage_success'),
            'infoInstall' => request('infoInstall')
        ]);
        break;

    case 'step4':
        try {
            rename(base_path() . '/public/vncore-install.php', base_path() . '/public/vncore-install.vncore');
        } catch (\Throwable $e) {
            echo json_encode([
                'error' => '1',
                'msg' => '#VNCORE:IS004::'.trans('vncore::install.rename_error'),
            ]);
            break;
        }
        echo json_encode([
            'error' => '0',
            'msg' => '',
            'admin_url' => VNCORE_ADMIN_PREFIX,
        ]);
        break;

    default:
        break;
    }
} else {
    $requirements = [
        'ext' => [
            'PHP >= 8.2'                 => version_compare(PHP_VERSION, '8.2', '>='),
            'BCMath PHP Extension'         => extension_loaded('bcmath'),
            'Ctype PHP Extension'          => extension_loaded('ctype'),
            'JSON PHP Extension'           => extension_loaded('json'),
            'OpenSSL PHP Extension'        => extension_loaded('openssl'),
            'PDO PHP Extension'            => extension_loaded('pdo'),
            'Tokenizer PHP Extension'      => extension_loaded('tokenizer'),
            'XML PHP extension'            => extension_loaded('xml'),
            'xmlwriter PHP extension'      => extension_loaded('xmlwriter'),
            'Mbstring PHP extension'       => extension_loaded('mbstring'),
            'ZipArchive PHP extension'     => extension_loaded('zip'),
            'GD (optional) PHP extension'  => extension_loaded('gd'),
            'Dom (optional) PHP extension' => extension_loaded('dom'),
        ],
        'writable' => [
            storage_path() => is_writable(storage_path()),
            base_path('vendor') => is_writable(base_path('vendor')),
            base_path('bootstrap/cache') => is_writable(base_path('bootstrap/cache')),
        ]
    ];

    //Check env file
    $errorEnv = '';
    if (!file_exists(base_path() . "/.env")) {
        $errorEnv = '<div>'.trans('vncore::install.env_not_found').'</div>';
    } else if (!config('app.key')) {
        $errorEnv .='<div>'.trans('vncore::install.env_key_not_found').'</div>';
    }
    if ($errorEnv) {
        echo view('vncore-front::install', array(
            'install_error'   => $errorEnv,
            'path_lang' => (($lang != 'en') ? "?lang=" . $lang : ""),
            'title'     => trans('vncore::install.title'), 'requirements' => $requirements)
        );
        exit();
    }

    //Check connection
    try {
        \DB::connection(VNCORE_DB_CONNECTION)->getPdo();
    } catch (\Throwable $e) {
        echo view('vncore-front::install', array(
            'install_error'   => '<div>'.trans('vncore::install.database_error').':'.$e->getMessage().'</div>',
            'path_lang' => (($lang != 'en') ? "?lang=" . $lang : ""),
            'title'     => trans('vncore::install.title'), 'requirements' => $requirements)
        );
        exit();
    }

    echo view('vncore-front::install', array(
        'path_lang' => (($lang != 'en') ? "?lang=" . $lang : ""),
        'title'     => trans('vncore::install.title'), 'requirements' => $requirements)
    );
    exit();
}
