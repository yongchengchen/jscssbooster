# jscssbooster
Laravel > 5.0 extension for easy compile sass, combine css and js


How to use
Edit your blade view template file.
```html
<html>
    <head>
        @requirecss(sample1.scss)
        @requirecss(sample2.scss)
        @requirecss(sample3.css)
        @requirejs(sample1.js)
        @requirejs(sample2.js)
    </head>
</html>
```

And it will generate html likes below:
```html
<html>
    <head>
        <link href="/public/css/sample.css" type="text/css">
        <script type="text/javascript" src='/public/js/sample.js'></script>
    </head>
</html>
```
