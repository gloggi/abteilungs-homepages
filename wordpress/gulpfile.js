var gulp   = require('gulp'),
	sass   = require('gulp-sass'),
	zip    = require('gulp-zip'),
	ftp    = require('gulp-ftp'),
	config = require('./gulpconfig.json');

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
	return gulp.src('.');
});

gulp.task('deploy-plugin', ['zip-plugin'], function() {
	return gulp.src(['gloggi-plugin.zip', 'gloggi-plugin.json'])
	.pipe(ftp({ 'host': config.ftphost, 'user': config.ftpuser, 'pass': config.ftppass, 'remotePath': config.ftppath }))
	.pipe(gulp.dest('.'));
});

gulp.task('deploy-theme', ['zip-theme'], function() {
	return gulp.src(['gloggi-theme.zip', 'gloggi-theme.json'])
	.pipe(ftp({ 'host': config.ftphost, 'user': config.ftpuser, 'pass': config.ftppass, 'remotePath': config.ftppath }))
	.pipe(gulp.dest('.'));
});

gulp.task('deploy', function() {
	return gulp.src(['gloggi-plugin.zip', 'gloggi-plugin.json', 'gloggi-theme.zip', 'gloggi-theme.json'])
	.pipe(ftp({ 'host': config.ftphost, 'user': config.ftpuser, 'pass': config.ftppass, 'remotePath': config.ftppath }))
	.pipe(gulp.dest('.'));
});

gulp.task('default', ['zip'], function() {
	return gulp.src('.');
});
