/* gulpfile.js */

var JSGulp = require('./gulp/JSGulp');

var gulp = require('gulp');

JSGulp.createTask();

gulp.task('watch', ['clean'], function(done) {
  JSGulp.watch();
});

gulp.task('clean', function(done) {
  var totalDone = 0;
  function childDone() {
    if (++totalDone === 1) {
      done();
    }
  };

  JSGulp.clean(childDone);
});

gulp.task('default', ['watch']);
