'use strict';

var gulp       = require('gulp');
var rsync      = require('gulp-rsync');

// gulp.task('default', ['deploy']);


gulp.task('deploy-prod', [], function(){
    return gulp.src('./')
      .pipe(rsync({
        root: './',
        hostname: 'server.chaufourier.fr',
        username: 'peter',
        destination: '/home/peter/sites/fanart/wp-content/themes/fanart',
        recursive: true,
        emptyDirectories: true,
        incremental: true,
        progress: true,
        exclude: ['.DS_Store', '.git', '.gitignore', '.gitkeep', 'gulpfile.js', 'package.json', 'node_modules']
    }));
});
