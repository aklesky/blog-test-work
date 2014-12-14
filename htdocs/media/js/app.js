requirejs.config({
    paths: {
        'jquery': 'jquery-2.1.1.min',
        'bootstrap': '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min',
        'tinymce': 'tinymce/tinymce.min',
        'fileuploader' : 'fileuploader/uploader',
        'uploader.basic' : 'fileuploader/uploader.basic',
        'jquery.plugin' : 'fileuploader/jquery-plugin',
        'header' : 'fileuploader/header.js',
        'handler.xhr' : 'fileuploader/handler.xhr',
        'handler.form' : 'fileuploader/handler.form',
        'handler.base' : 'fileuploader/handler.base',
        'dnd' : 'fileuploader/dnd',
        'util' : 'fileuploader/util',
        'button' : 'fileuploader/button'
    },
    'shim': {
        "bootstrap": {deps: ['jquery']},
        'jquery': {
            exports: '$'
        },
        'tinymce': {
            deps: ['jquery'],
            exports : 'tinymce'
        },
        'fileuploader' : {
            deps:['jquery'],
            exports : 'qq'
        },
        'jquery.plugin' : {
            deps : ['jquery'],
            exports : 'qq'
        }
    },
    waitSeconds : 1
});

requirejs([
    'jquery',
    'bootstrap'
]);