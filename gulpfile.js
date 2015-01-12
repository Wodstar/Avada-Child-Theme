var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps');

gulp.task('styles', function() {
  return gulp.src('./scss/style.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({ style: 'expanded' }))
    .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
    .pipe(sourcemaps.write('./maps'))
    .pipe(gulp.dest('./'))
});

gulp.task('watch', function () {
  gulp.watch('scss/*.scss', ['styles']);
});

gulp.task('default', ['watch'], function () {});