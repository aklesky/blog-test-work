module.exports = function(config) {
  config.set({
    basePath: './',
    frameworks: ['jasmine-ajax', 'jasmine-jquery', 'jasmine', 'requirejs'],
    files: [
      'tests/js/test-main.js',
      {pattern: 'tests/js/testsuites/**/*.js', included: false},
      {pattern: 'htdocs/media/js/*.js', included: false},
      {pattern: 'tests/js/testsuites/fixtures/*', included:false, watched:true, served:true}
    ],

    exclude: [
    ],

    preprocessors: {

    },

    reporters: ['progress'],
    port: 9876,
    colors: true,
    logLevel: config.LOG_INFO,
    autoWatch: true,
    browsers: ['Chrome'],
    singleRun: true
  });
};
