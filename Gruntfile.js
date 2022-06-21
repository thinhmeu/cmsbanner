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
        concat: {
            /*default_css: {
                src: [
                    'public/css/bootstrap.min.css',
                    'public/css/swiper.min.css',
                    'public/plugins/toastr/toastr.scss',
                    'public/css/default.css',
                    'public/css/video.css',
                    'public/css/icon-sprite.css'
                ],
                dest: 'public/css/style_default_minified.css'
            },*/
            non_critical: {
                src: [
                    'public/web/js/banner.js',
                    'public/web/js/custom.js',
                    'public/web/js/jquery.rateit.min.js',
                    'public/web/js/rating.js',
                ],
                dest: 'public/web/js/non-critical.js'
            },
        },
        uglify: {
        js: {
                src: 'public/web/js/non-critical.js',
                dest: 'public/web/js/non-critical.min.js'
            }
        },
        /*cssmin: {
            default_css: {
                src: 'public/css/style_default_minified.css',
                dest: 'public/css/style_default_minified.min.css'
            },
        },*/
        /*watch: {
            css: {
                files: ['public/css/!*.css'],
                tasks: ['concat', 'cssmin'],
            },
            scripts: {
                files: ['public/js/!*.js'],
                tasks: ['concat', 'uglify'],
            },
        }*/
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    /*grunt.loadNpmTasks('grunt-contrib-cssmin');*/
    /*grunt.loadNpmTasks('grunt-contrib-watch');*/
    grunt.registerTask('build', ['concat', 'uglify']);
};
