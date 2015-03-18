var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    coffee = require('gulp-coffee'),
    gutil = require('gulp-util');

gulp.task('styles', function() {
  return gulp.src(['./scss/style.scss'])
    .pipe(sass({ style: 'expanded', includePaths: ['./scss'] }))
    .on('error', gutil.log)
    .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4', {map: false}))
    .pipe(gulp.dest('./'))
});

gulp.task('coffee', function () {
  gulp.src(['./coffee/*.coffee'])
    .pipe(coffee({bare: true}))
    .on('error', gutil.log)
    .pipe(gulp.dest('./js/'))
});

gulp.task('watch', function () {
  gulp.watch('scss/**/*.scss', ['styles']);
  gulp.watch('coffee/**/*.coffee', ['coffee']);
});

gulp.task('default', ['watch'], function () {});