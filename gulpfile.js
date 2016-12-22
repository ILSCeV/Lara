/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

var gulp = require('gulp');
var ts = require('gulp-typescript');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');

gulp.task('default', function() {
    var tsResult = gulp.src('typescript/*.ts')
        .pipe(sourcemaps.init())
        .pipe(ts({
            noImplicitAny: true,
            target: 'ES5',
        }));
    return tsResult.js
        .pipe(concat('bundle.js'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('public/js/bin'));
});

gulp.task('release', function() {
    var tsResult = gulp.src('typescript/*.ts')
        .pipe(ts({
            noImplicitAny: true,
            target: 'ES5',
        }));
    return tsResult.js
        .pipe(concat('bundle.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/js/bin'));
});
