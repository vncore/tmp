<?php

namespace Vncore\Core\Commands;

use Illuminate\Console\Command;
use Throwable;
use DB;

class Initial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vncore:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vncore initial';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('migrate');
        $this->call('vendor:publish', ['--tag' => 'vncore:public-install']);
        $this->call('vendor:publish', ['--tag' => 'vncore:public-static']);
        $this->call('vendor:publish', ['--tag' => 'vncore:public-vendor']);
        $this->call('vendor:publish', ['--tag' => 'vncore:functions-except']);
    }
}
