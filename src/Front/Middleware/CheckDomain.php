<?php

namespace Vncore\Core\Front\Middleware;

use Closure;
use Vncore\Core\Front\Models\ShopStore;

class CheckDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Only apply for when plugin multi-vendor or multi-store active
        if (vncore_check_multi_shop_installed() && vncore_config_global('domain_strict')) {
            //Check domain exist
            $domain = vncore_process_domain_store(url('/')); //domain currently
            $domainRoot = vncore_process_domain_store(config('app.url')); //Domain root config in .env
            $arrDomainPartner = ShopStore::getDomainPartner(); // List domain is partner active
            $arrDomainActive = ShopStore::getDomainStore(); // List domain is unlock domain

            if (vncore_check_multi_vendor_installed()) {
                if (!in_array($domain, $arrDomainPartner) && $domain != $domainRoot) {
                    echo view('deny_domain')->render();
                    exit();
                }
            }

            if (vncore_check_multi_store_installed()) {
                if (!in_array($domain, $arrDomainActive) && $domain != $domainRoot) {
                    echo view('deny_domain')->render();
                    exit();
                }
            }
        }
        return $next($request);
    }
}
