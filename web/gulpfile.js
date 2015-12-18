/* gulpfile.js */

var JSGulp = require('./gulp/JSGulp');
var gulp = require('gulp');

JSGulp.createTask();

gulp.task('watch', ['clean'], function() {
  JSGulp.watch();
});

gulp.task('clean', function() {
  JSGulp.clean();
});

gulp.task('default', ['watch']);
