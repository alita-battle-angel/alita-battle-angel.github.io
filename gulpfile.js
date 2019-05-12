const gulp = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const babel = require('gulp-babel');
const concat = require('gulp-concat');
const del = require('del');

const destination = 'public';

const clean = function (done) {
  del(destination + '/**/*.*');
  done();
};

const copy = function (done) {
  gulp.src('src/index.html')
    .pipe(gulp.dest(destination));

  gulp.src('src/images/*')
    .pipe(gulp.dest(destination + '/images'));

  gulp.src('src/styles/*')
    .pipe(gulp.dest(destination + '/styles'));
  return done();
};

const build = function (done) {
  return gulp.src('src/**/*.js')
    .pipe(sourcemaps.init())
    .pipe(babel())
    .pipe(concat('bundle.js'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(destination));
  return done();
};

const buildProd = function (done) {
  return gulp.src('src/**/*.js')
    .pipe(babel())
    .pipe(concat('bundle.js'))
    .pipe(gulp.dest(destination));
  return done();
};

// gulp.task('build-development', gulp.series(copy, build));

gulp.task('build-production', gulp.series(clean, copy, buildProd));

gulp.task('watch', gulp.series(clean, copy, build, function watching() {
  gulp.watch('src/**/*.*', gulp.series(copy, build));
}));
