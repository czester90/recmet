/*
 * grunt-cli
 * http://gruntjs.com/
 *
 * Copyright (c) 2012 Tyler Kellen, contributors
 * Licensed under the MIT license.
 * https://github.com/gruntjs/grunt-init/blob/master/LICENSE-MIT
 */

'use strict';

module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            development: {
                files: {
                    'public/js/jquery.min.js': [
                        'public/js/jquery-1.8.3.min.js',
                        'public/js/jquery-ui.js'
                    ],
                    'public/js/plugins.min.js': [
                        'public/js/bootstrap.min.js',
                        'public/js/jquery.prettyPhoto.js'
                    ],
                    'public/js/script.min.js': [
                        'public/js/vendor/*.js',
                        'public/js/jquery.validate.min.js',
                        'public/js/additional-methods.min.js',
                        'public/js/main.js',
                        'public/js/validator.js'
                    ],
                    'public/js/tinymce.js': [
                        'vendor/tinymce/tinymce/tinymce.js',
                        'vendor/tinymce/tinymce/tinymce.jquery.js',
                        "vendor/tinymce/tinymce/plugins/*/plugin.js",
                        "vendor/tinymce/tinymce/themes/*/theme.js"
                    ]
                }
            }
        },
        less: {
            development: {
                files: {
                    "public/css/validate.css": "public/css/validate.less"
                }
            }
        },
        cssmin: {
            development: {
                files: {
                    'public/css/styles.min.css': [
                        'public/css/bootstrap-responsive.min.css',
                        'public/css/bootstrap-select.css',
                        'public/css/vendor/slider.css',
                        'public/css/jquery.mCustomScrollbar.css',
                        'public/css/views/company/price-table.less',
                        'public/css/views/advert/table-advert.less',
                        'public/css/font-awesome.min.css',
                        'public/css/style.css',
                        'public/css/common.css',
                        'public/css/validate.css',
                        'public/css/button.css'
                    ],
                    'public/css/tinymce.css': [
                        'vendor/tinymce/tinymce/skins/*/*.css'
                    ]
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
                    livereload: true
                }
            },
            js: {
                files: [
                    'public/js/*.js'
                ],
                tasks: [
                    'uglify'
                ],
                options: {
                    livereload: true
                }
            }
        }
    });
    //Ładowanie zadania
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['less', 'uglify', 'cssmin']);
    grunt.registerTask('wt-js', ['watch:']);

};
