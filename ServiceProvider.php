<?php 
/**
 * @author    Yongcheng Chen <yongcheng.chen@live.com>
 */

namespace Ycc\Jscssbooster;

use Illuminate\Support\ServiceProvider as KernelServiceProvider;
use Ycc\Jscssbooster\Jscssbooster;
use Ycc\Jscssbooster\CacheCore;
use Ycc\Jscssbooster\BladeExt;
use Ycc\Jscssbooster\Component;
use Event;

class ServiceProvider extends KernelServiceProvider {
/**
 * Bootstrap the application services.
 *
 * @return void
 */
  public function boot() {
    // Register view parts methods
    $this->app['Jscssbooster.bladeext']->injectBlade();
    //$this->app['Illuminate\Contracts\Http\Kernel']->pushMiddleware(\Ycc\Jscssbooster\Middleware::class);
  }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bindIf('request', function () {
      return Request::createFromGlobals();
    });

    $this->app->singleton('Jscssbooster',function($app) {
      return new Jscssbooster($app);
    });

    $this->app->singleton('Jscssbooster.bladeext', function ($app) {
      return new BladeExt($app);
    });
  }
}
