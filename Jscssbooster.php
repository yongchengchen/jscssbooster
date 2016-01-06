<?php 
/**
 * @author    Yongcheng Chen <yongcheng.chen@live.com>
 */

namespace Ycc\Jscssbooster;

use Illuminate\Container\Container;

class Jscssbooster
{
  protected $app;

  public function __construct(Container $app)
  {
    $this->app = $app;
  }

  public function __call($method, $arguments)
  {
    $class = $this;
    $parts = array('bladeext');
    foreach ($parts as $part) {
      $part = $this->app['Jscssbooster.'.$part];
      if (method_exists($part, $method)) {
        $class = $part;
        break;
      }
    }
    return call_user_func_array(array($class, $method), $arguments);
  }
}
