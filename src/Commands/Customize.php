<?php

namespace Vncore\Core\Commands;

use Illuminate\Console\Command;
use Throwable;
use DB;

class Customize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vncore:customize {obj?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Customize obj';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $obj = $this->argument('obj');
        switch ($obj) {
            case 'admin':
                $this->call('vendor:publish', ['--tag' => 'vncore:config-admin']);
                $this->call('vendor:publish', ['--tag' => 'vncore:view-admin']);
                break;

            case 'middleware':
                $this->call('vendor:publish', ['--tag' => 'vncore:config-middleware']);
                break;
                 
            case 'lfm':
                $this->call('vendor:publish', ['--tag' => 'vncore:config-lfm']);
                break;

            case 'api':
                $this->call('vendor:publish', ['--tag' => 'vncore:config-api']);
                break;
            case 'static':
                $this->call('vendor:publish', ['--tag' => 'vncore:public-static']);
                $this->call('vendor:publish', ['--tag' => 'vncore:public-vendor']);
                break;

            default:
                $this->info('Nothing');
                break;
        }
    }
}
