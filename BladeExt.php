<?php 

/**
 * 
 * This class extends Blade compiler, it extends two directives: requirejs, requirecss
 *
 *  requirejs only can add local javascipt script file, and with .js subfix
 *  requirecss only can add local sass or css file, with .scss or .css subfix
 *
 * @author    Yongcheng Chen <yongcheng.chen@live.com>
 */

namespace Ycc\Jscssbooster;

use Closure;
use Illuminate\Container\Container;
use Log;

class BladeExt
{
  protected $app;
  private $js_array = ['js'=>[],'target'=>''];
  private $css_array = ['sass'=>[], 'css'=>[], 'target'=>''];

  public function __construct(Container $app)
  {
    $this->app = $app;
  }

  public function injectBlade()
  {
    $self = $this;
    $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

    $blade->directive('requirejs', function($var) use($self, $blade) {
      $self->addJs($var);
      static $jsplaceholder = null;
      if ($jsplaceholder === null) {
        $jsplaceholder = true;
        $path = basename($blade->getCompiledPath($blade->getPath()));
        return '<script type="text/javascript" src="' .
          '<?php echo \Ycc\Jscssbooster\Facades\Jscssbooster::computeResName(\''.$path.'\', \'js\'); ?>"></script>' . PHP_EOL;
      }
      return '';
    });

    $blade->directive('requirecss', function($var) use($self, $blade) {
      $self->addCss($var);
      static $placeholder = null;
      if ($placeholder === null) {
        $placeholder = true;
        $path = basename($blade->getCompiledPath($blade->getPath()));
        return '<link rel="stylesheet" type="text/css" href="' .
          '<?php echo \Ycc\Jscssbooster\Facades\Jscssbooster::computeResName(\''.$path.'\', \'css\'); ?>" />' . PHP_EOL;
      }
      return '';
    });
    return $this;
  }

  public function computeResName($key, $res_type) {
    $key = $res_type . $key;
    $md5 = '';
    $res_array = $res_type === 'css' ? $this->css_array : $this->js_array;
    
    if ($this->app['cache']->has($key)) {
      $md5 = $this->app['cache']->get($key);
    } else {
      $md5 = '__gulp__' . $res_type . '__' . md5(json_encode($res_array));
      $this->app['cache']->put($key, $md5, 60*1000*60*24*30);
    }
    
    if (!$this->app['cache']->has($md5)) {
      $filename = $md5 . '_' . time() . '.' . $res_type;
      $this->app['cache']->put($md5, $filename, 60*1000*60*24*30);
      $res_array['target'] = $filename;
      file_put_contents(base_path() . '/storage/framework/gulp/' . $key, json_encode($res_array));
      echo shell_exec('cd '. base_path() . ' && gulp ' . $key);
    }
    return 'public/' . $res_type . '/' . $this->app['cache']->get($md5);
  }

  public function addJs($filename) {
    $filename = str_replace('(', '', $filename);
    $filename = str_replace(')', '', $filename);
    $this->js_array['js'][] = $filename;
  }

  public function addCss($filename) {
    $filename = str_replace('(', '', $filename);
    $filename = str_replace(')', '', $filename);
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    switch($ext) {
      case 'scss':
        $this->css_array['sass'][] = $filename;
        break;
      case 'css':
        $this->css_array['css'][] = $filename;
        break;
      default:
        break;
    }
  }
}
