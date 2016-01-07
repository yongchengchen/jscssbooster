process.env.DISABLE_NOTIFIER = true;
var elixir = require('laravel-elixir');
var gulp = require('gulp');
var fs = require("fs");
var path = require('path');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

var args = process.argv.slice(2);

if (args.length > 0) {
  var filepath = 'storage/framework/gulp/' + args[0]; 
  try{
    var json = JSON.parse(fs.readFileSync(filepath, 'utf8'));
    if (json.type == 'css') {
      var css_output = 'public/css/' + json.target;
      var tmp_output = css_output;
      if (json.css.length > 0) {
        tmp_output = 'resources/assets/css/' + json.target + '.tmp.css';
        json.css.push(json.target + '.tmp.css');
        elixir(function(mix) {
          mix.styles(json.css, css_output);
        });
      }
      elixir(function(mix) {
        mix.sass(json.sass, tmp_output);
      });
      
      gulp.task(args[0], ['sass'], function() {
        if (json.css.length > 1) {
          elixir.Task.find('styles').run();
        }
      });
    } else {
      elixir(function(mix) {
        mix.scripts(json.js, 'public/js/' + json.target);
      });
      gulp.task(args[0], ['scripts'], function() {
      });
    }
  } catch(err) {
    gulp.task(args[0], function() {
      console.log('args[0] doesn\'t exist!\n' + err);
    });
  }
}
