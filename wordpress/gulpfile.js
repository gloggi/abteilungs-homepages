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
	config     = require('./gulpconfig.json');

gulp.task('css', function() {
	return gulp.src('../frontend/src/css/*.scss')
    .pipe(sass())
    .pipe(gulp.dest('gloggi-theme/files/css'));
});

gulp.task('zip-plugin', function() {
	return gulp.src('gloggi-plugin/**')
	.pipe(zip('gloggi-plugin.zip'))
	.pipe(gulp.dest('.'));
});

gulp.task('zip-theme', ['css'], function() {
	return gulp.src('gloggi-theme/**')
	.pipe(zip('gloggi-theme.zip'))
	.pipe(gulp.dest('.'));
});

gulp.task('zip', ['zip-plugin', 'zip-theme'], function() {
	return util.noop();
});

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

gulp.task('deploy', ['zip-plugin', 'zip-theme'], function() {
	return merge(
		gulp.src(['./gloggi-plugin.json', './gloggi-theme.json'])
		.pipe(version({'prepend':''}))
		.pipe(inject.replace('%%GULP_INJECT_DATETIME%%', dateformat(new Date(), "yyyy-mm-dd hh:MM:ss"))),
		gulp.src(['gloggi-plugin.zip', 'gloggi-theme.zip'])
	)
	.pipe(ftp.create({'host': config.ftphost, 'user': config.ftpuser, 'password': config.ftppass}).dest(config.ftppath));
});

gulp.task('release', ['bump-version'], function() {
	return gulp.start('deploy');
});

gulp.task('release-minor', ['bump-version-minor'], function() {
	return gulp.start('deploy');
});

gulp.task('release-major', ['bump-version-major'], function() {
	return gulp.start('deploy');
});

gulp.task('default', ['zip'], function() {
	return util.noop();
});
