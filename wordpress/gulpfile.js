var gulp       = require('gulp'),
    sass       = require('gulp-sass'),
    zip        = require('gulp-zip'),
    bump       = require('gulp-bump'),
    ftp        = require('vinyl-ftp'),
    merge      = require('merge-stream'),
    version    = require('gulp-inject-version'),
    util       = require('gulp-util'),
    dateformat = require('dateformat'),
    inject     = require('gulp-inject-string'),
    filter     = require('gulp-filter'),
    git        = require('gulp-git'),
    config     = require('./gulpconfig.json');

gulp.task('test', function() {
    console.log(process.env)
})

gulp.task('css', function() {
    return gulp.src('../frontend/src/css/*.scss')
    .pipe(sass())
    .pipe(gulp.dest('gloggi-theme/files/css'));
});

gulp.task('zip-plugin', function() {
    var f = filter(['**/*.php', '**/*.css'], {restore: true});
    return gulp.src('gloggi-plugin/**', {'base':'.'})
    .pipe(f)
    .pipe(version({'prepend':''}))
    .pipe(f.restore)
    .pipe(zip('gloggi-plugin.zip'))
    .pipe(gulp.dest('.'));
});

gulp.task('zip-theme', gulp.series('css', function() {
    var f = filter(['**/*.php', '**/*.css'], {restore: true});
    return gulp.src('gloggi-theme/**', {'base':'.'})
    .pipe(f)
    .pipe(version({'prepend':''}))
    .pipe(f.restore)
    .pipe(zip('gloggi-theme.zip'))
    .pipe(gulp.dest('.'));
}));

gulp.task('zip', gulp.parallel('zip-plugin', 'zip-theme'));

gulp.task('bump-version', function() {
    return gulp.src('./package.json')
    .pipe(bump())
    .pipe(gulp.dest('.'));
});

gulp.task('bump-version-minor', function() {
    return gulp.src('./package.json')
    .pipe(bump({'type':'minor'}))
    .pipe(gulp.dest('.'));
});

gulp.task('bump-version-major', function() {
    return gulp.src('./package.json')
    .pipe(bump({'type':'major'}))
    .pipe(gulp.dest('.'));
});

gulp.task('deploy', gulp.series('zip', function() {
    return merge(
        gulp.src(['./gloggi-plugin.json', './gloggi-theme.json'])
        .pipe(version({'prepend':''}))
        .pipe(inject.replace('%%GULP_INJECT_DATETIME%%', dateformat(new Date(), "yyyy-mm-dd hh:MM:ss"))),
        gulp.src(['gloggi-plugin.zip', 'gloggi-theme.zip'])
    )
    .pipe(ftp.create({'host': config.ftphost, 'user': config.ftpuser, 'password': config.ftppass}).dest(config.ftppath));
}));

gulp.task('release', gulp.series('bump-version', function() {
    return do_release();
}));

gulp.task('release-minor', gulp.series('bump-version-minor', function() {
    return do_release();
}));

gulp.task('release-major', gulp.series('bump-version-major', function() {
    return do_release();
}));

function do_release() {
    package = require('./package.json');
    gulp.src('./*')
    .pipe(git.commit('Release v' + package.version, {args: '-a', disableAppendPaths: true}))
    .on('end', function () {
        git.tag('v' + package.version, 'Release v' + package.version, function (err) { });
    });
    gulp.start('deploy');
}

gulp.task('default', gulp.parallel('zip-plugin', 'zip-theme'));
