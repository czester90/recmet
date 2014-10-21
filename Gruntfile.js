/*
 * grunt-cli
 * http://gruntjs.com/
 *
 * Copyright (c) 2012 Tyler Kellen, contributors
 * Licensed under the MIT license.
 * https://github.com/gruntjs/grunt-init/blob/master/LICENSE-MIT
 */

'use strict';

module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
        my_target: {
            files: {
                'public/js/scripts.min.js': [
                    'public/js/jquery.min.js',
                    'public/js/bootstrap.min.js',
                    'public/js/jquery.prettyPhoto.js',
                    'public/js/respond.min.js',
                    'public/js/main.js',
                    'public/js/vendor/*.js'
                ]
            }
        }
    },
    cssmin: {
        combine: {
            files: {
                'public/css/styles.min.css': [
                  'public/css/bootstrap-responsive.min.css',
                  'public/css/bootstrap-select.css',
                  'public/css/vendor/slider.css',
                  'public/css/jquery.mCustomScrollbar.css',
                  'public/css/views/company/price-table.less',
                  'public/css/views/advert/table-advert.less',
                  'public/css/font-awesome.min.css',
                  'public/css/style.css'
                ]
            }
        }
    },
    less: {
        development: {
            files: {
                //"css/result.css": "less/less.less"
            }
        }
    },
    watch: {
        scripts: {
            files: [
              //'public/js/*.js', 
              'public/css/*.css', 
              'public/css/*.less'
            ],
            tasks: [
              //'uglify', 
              'less', 
              'cssmin'
            ],
            options: {
              livereload: true,
            }
        }
    }
  });
  //Ładowanie zadania
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('default', ['less','uglify', 'cssmin']);

};
