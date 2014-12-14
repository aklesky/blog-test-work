module.exports = function(config) {
  config.set({
    basePath: './',
    frameworks: ['jasmine-ajax', 'jasmine-jquery', 'jasmine', 'jasmine-matchers', 'requirejs'],
    files: [
      'test-main.js',
      {pattern: 'tests/js/testsuites/**/*.js', included: false},
      {pattern: 'htdocs/media/js/*.js', included: false},
      {pattern: 'tests/js/testsuites/fixtures/*', included:false, watched:true,
        served:true}
    ],

    exclude: [
    ],

    preprocessors: {

    },

    plugins: [
        'karma-jasmine-ajax',
        'karma-jasmine-jquery',
        'karma-jasmine',
        'karma-jasmine-matchers',
        'karma-requirejs',
        'karma-chrome-launcher',
        'karma-detect-browsers'
    ],

    reporters: ['progress'],
    port: 9876,
    colors: true,
    logLevel: config.LOG_INFO,
    autoWatch: false,
    browsers: ['Chrome'],
    singleRun: false
  });
};
