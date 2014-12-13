'use strict';
var allTestFiles = [];
var TEST_REGEXP = /(spec|test)\.js$/i;

var pathToModule = function(path) {
  return path.replace(/^\/base\//, '').replace(/\.js$/, '');
};

Object.keys(window.__karma__.files).forEach(function(file) {
  if (TEST_REGEXP.test(file)) {
    // Normalize paths to RequireJS module names.
    allTestFiles.push(pathToModule(file));
  }
});

require.config({
  baseUrl: '/base',
  paths : {
    'jquery' : './htdocs/media/js/jquery-2.1.1.min'
  },
  'shim' : {
    'jquery' : {
      exports : '$'
    }
  },


  deps: allTestFiles,

  callback: window.__karma__.start
});
