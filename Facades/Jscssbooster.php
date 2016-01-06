<?php 
/**
 * @author    Yongcheng Chen <yongcheng.chen@live.com>
 */

namespace Ycc\Jscssbooster\Facades;

use Illuminate\Support\Facades\Facade as KernelFacade;
use Ycc\Jscssbooster\JscssboosterServiceProvider;

class Jscssbooster extends KernelFacade {
  protected static function getFacadeAccessor() 
  { 
    if (!static::$app) {
      static::$app = new Container();
      $provider = new JscssboosterServiceProvider(static::$app);
      $provider->register();
    }
    return "Jscssbooster";
  }
}
