var gulp = require('gulp');
var less = require('gulp-less');
var browserSync = require('browser-sync');
var useref = require('gulp-useref');
var uglify = require('gulp-uglify');
var gulpIf = require('gulp-if');
var cssnano = require('gulp-cssnano');
var del = require('del');
var runSequence = require('run-sequence');
var autoprefixer = require('gulp-autoprefixer');
const { notify } = require('browser-sync');

gulp.task('less', function () {
    return gulp.src('app/assets/less/style.less')
      .pipe(less())
      .pipe(autoprefixer(['last 15 versions', '> 1%', 'ie 8', 'ie 7'], { cascade: true }))
      .pipe(gulp.dest('app/assets/css'))
      .pipe(browserSync.reload({
        stream: true
      }))
      
    });

  gulp.task('clean', function(done) {
  del.sync('dist');
  done();
  })

  gulp.task('serve', function(done) {
      browserSync.init({
        proxy: {
            target: "http://test.my"
        },
        notify: false
      }) 

      gulp.watch("app/assets/less/**/*.less", gulp.series('less'));
      gulp.watch("app/**/*.php").on('change', () => {
          browserSync.reload();
          done();
      });
      gulp.watch("app/assets/js/**/*.js").on('change', () => {
          browserSync.reload();
          done();
      })
  });

  gulp.task('compileCss', function(done) {
      gulp.watch("app/assets/less/**/*.less", gulp.series('less'));
      done();
  })

  gulp.task('useref', function() {
      return gulp.src('assets/*.html')
        .pipe(useref())
        .pipe(gulpIf('*.js', uglify()))
        .pipe(gulpIf('*.css', cssnano()))
        .pipe(gulp.dest('dist'))
  });
  gulp.task('run-server', gulp.series('less', 'serve'));

  gulp.task('build', gulp.series('clean', 'useref'), function(done) {
    done();
  })