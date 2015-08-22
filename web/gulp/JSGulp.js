var argv = require('yargs').argv;
var browserify = require('browserify');
var buffer = require('vinyl-buffer');
var del = require('del');
var es6ify = require('es6ify');
var gulp = require('gulp');
var notify = require('gulp-notify');
var reactify = require('reactify');
var replacestream = require('replacestream');
var source = require('vinyl-source-stream');
var uglify = require('gulp-uglify');
var watchify = require('watchify');

es6ify.traceurOverrides = {experimental: true};

var jsBundler;
var rootJSPath = __dirname + '/../src/inc/appRouter.js';
var outputPath = __dirname + '/../src/root/js';
var buildFile = 'Bundle.js';

var JSGulp = {
  createTask: function() {
    gulp.task('js', function() {
      return JSGulp.getBundleStream();
    });
  },

  _createBundler: function(watch) {
    var bundler = browserify({
      basedir: __dirname,
      bundleExternal: false,
      cache: {},
      debug: !argv.production,
      entries: rootJSPath,
      extensions: ['.js'],
      packageCache: {},
      fullPaths: true,
    });
    bundler.transform(reactify);
    bundler.transform(es6ify.configure(/.js/));

    if (watch) {
      bundler = watchify(bundler);
      bundler.on('update', JSGulp.getBundleStream);
    }

    jsBundler = bundler;
  },

  getBundleStream: function() {
    if (!jsBundler) {
      JSGulp._createBundler(false);
    }

    console.log('Bundling JS');
    var start = Date.now();
    var filename = rootJSPath.substring(rootJSPath.lastIndexOf('/') + 1);

    var stream = jsBundler.bundle();
    stream.on('error', notify.onError({
        title: 'JS Gulp',
        subtitle: 'Failure!',
        message: 'Error: <%= error.message %>',
        sound: 'beep',
    }));
    stream.on('end', function() {
      var time = Date.now() - start;
      console.log('Bundled JS (' + time + ' ms)');
    });

    stream = stream.pipe(replacestream('__DEV__', !argv.production));
    stream = stream.pipe(source(buildFile));

    if (argv.production) {
      // minification
      stream = stream.pipe(buffer());
      stream = stream.pipe(uglify());
    }

    stream = stream.pipe(gulp.dest(outputPath));
    return stream;
  },

  watch: function() {
    JSGulp._createBundler(true);

    var stream = JSGulp.getBundleStream();
    stream.read();
  },

  clean: function(done) {
    del([outputPath + '/' + buildFile], function(err, paths) {
      if (err) {
        console.error('Error cleaning JS: ' + err.toString());
        done();
        return;
      }

      console.log('Cleaned JS');
      done();
    });
  },
};

module.exports = JSGulp;
