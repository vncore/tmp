<?php

namespace Vncore\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Throwable;

class Infomation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get infomation S-Cart';
    const LIMIT = 10;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(config('vncore.name').' - '.config('vncore.title'));
        $this->info(config('vncore.auth').' <'.config('vncore.email').'>');
        $this->info('Front version: '.config('vncore.version'));
        $this->info('Front sub-version: '.config('vncore.sub-version'));
        $this->info('Core: '.config('vncore.core'));
        $this->info('Core sub-version: '.config('vncore.core-sub-version'));
        $this->info('Type: '.config('vncore.type'));
        $this->info('Homepage: '.config('vncore.homepage'));
        $this->info('Github: '.config('vncore.github'));
        $this->info('Facebook: '.config('vncore.facebook'));
        $this->info('API: '.config('vncore.api_link'));
    }
}
