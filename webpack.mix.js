const mix = require('laravel-mix');
const path = require('path');

var dir = __dirname;
var name = dir.split(path.sep).pop();

var assetPath = __dirname + '/Resources/assets';
var publicPath = path.resolve(__dirname,'../../public/modules/'+name);

mix.webpackConfig({
  resolve: {
    alias: {
      'pingu-forms': path.resolve(assetPath + '/js/components', './forms'),
    }
  }
});

mix.js(assetPath + '/js/app.js', publicPath + '/js/'+name+'.js').sourceMaps();

//Css
// mix.sass(assetPath + '/sass/app.scss', publicPath + '/css/'+name+'.css');