module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        publicDir: 'web/bundles/aigallery',
        baseAssetsDir: 'src/Ai/GalleryBundle/Resources/assets',
        faFontsDir: '<%= baseAssetsDir %>/components/font-awesome/fonts',
        coffeeDir: '<%= baseAssetsDir %>/coffee',
        imagesDir: '<%= baseAssetsDir %>/img',
        sassDir: '<%= baseAssetsDir %>/scss',
        cssDir: '<%= publicDir %>/css',
        jsDir: '<%= publicDir %>/js',
        fontsDir: '<%= publicDir %>/fonts',
        componentsDir: '<%= baseAssetsDir %>/components',

        // Cleans the public assets
        clean: {
            build: {
                src: ['<%= publicDir %>/**']
            }
        },

        // Copies files
        copy: {
            templates: {
                files: [
                    {
                        expand: true,
                        cwd: '<%= coffeeDir %>',
                        src: ['**/*.html'],
                        dest: '<%= jsDir %>'
                    }
                ]
            },
            fonts: {
                files: [
                    {
                        expand: true,
                        src: '<%= faFontsDir %>/**',
                        dest: '<%= fontsDir %>',
                        flatten: true,
                        filter: 'isFile'
                    }
                ]
            },
            images: {
                files: [
                    {
                        expand: true,
                        src: '<%= imagesDir %>/**',
                        dest: '<%= publicDir %>/img',
                        flatten: true,
                        filter: 'isFile'
                    }
                ]
            },
            js: {
                files: [
                    {
                        src: ['<%= componentsDir %>/jquery/dist/jquery.js'],
                        dest: '<%= jsDir %>/vendor/jquery.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone/backbone.js'],
                        dest: '<%= jsDir %>/vendor/backbone.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone.babysitter/lib/backbone.babysitter.js'],
                        dest: '<%= jsDir %>/vendor/babysitter.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone.marionette/lib/core/amd/backbone.marionette.js'],
                        dest: '<%= jsDir %>/vendor/marionette.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone.wreqr/lib/backbone.wreqr.js'],
                        dest: '<%= jsDir %>/vendor/wreqr.js'
                    },
                    {
                        src: ['<%= componentsDir %>/requirejs/require.js'],
                        dest: '<%= jsDir %>/vendor/require.js'
                    },
                    {
                        src: ['<%= componentsDir %>/text/text.js'],
                        dest: '<%= jsDir %>/vendor/text.js'
                    },
                    {
                        src: ['<%= componentsDir %>/underscore/underscore.js'],
                        dest: '<%= jsDir %>/vendor/underscore.js'
                    },
                    {
                        src: ['<%= componentsDir %>/handlebars/handlebars.amd.js'],
                        dest: '<%= jsDir %>/vendor/handlebars.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone.syphon/lib/backbone.syphon.js'],
                        dest: '<%= jsDir %>/vendor/syphon.js'
                    },
                    {
                        src: ['<%= componentsDir %>/bootstrap-sass/assets/javascripts/bootstrap.js'],
                        dest: '<%= jsDir %>/vendor/bootstrap.js'
                    },
                    {
                        src: ['<%= componentsDir %>/jquery.cookie/jquery.cookie.js'],
                        dest: '<%= jsDir %>/vendor/jquery.cookie.js'
                    },
                    {
                        src: ['<%= componentsDir %>/spin.js/spin.js'],
                        dest: '<%= jsDir %>/vendor/spin.js'
                    },
                    {
                        src: ['<%= componentsDir %>/spin.js/jquery.spin.js'],
                        dest: '<%= jsDir %>/vendor/jquery.spin.js'
                    },
                    {
                        src: ['<%= componentsDir %>/moment/moment.js'],
                        dest: '<%= jsDir %>/vendor/moment.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone-query-parameters/backbone.queryparams.js'],
                        dest: '<%= jsDir %>/vendor/backbone.queryparams.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-file-upload/js/vendor/jquery.ui.widget.js'],
                        dest: '<%= jsDir %>/vendor/jquery.ui.widget.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-load-image/js/load-image.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/load-image.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-load-image/js/load-image-meta.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/load-image-meta.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-load-image/js/load-image-exif.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/load-image-exif.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-load-image/js/load-image.all.min.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/load-image.all.min.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-canvas-to-blob/js/canvas-to-blob.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/canvas-to-blob.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-file-upload/js/jquery.fileupload-ui.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/jquery.fileupload-ui.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-file-upload/js/jquery.fileupload-process.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/jquery.fileupload-process.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-file-upload/js/jquery.fileupload-validate.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/jquery.fileupload-validate.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-file-upload/js/jquery.iframe-transport.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/jquery.iframe-transport.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-file-upload/js/jquery.fileupload.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/jquery.fileupload.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-file-upload/js/jquery.fileupload-image.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/jquery.fileupload-image.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-file-upload/js/jquery.fileupload-audio.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/jquery.fileupload-audio.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-file-upload/js/jquery.fileupload-video.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/jquery.fileupload-video.js'
                    },
                    {
                        src: ['<%= componentsDir %>/blueimp-tmpl/js/tmpl.js'],
                        dest: '<%= jsDir %>/vendor/file_upload/tmpl.js'
                    },
                    {
                        src: ['<%= componentsDir %>/fineuploader-dist/dist/fine-uploader.js'],
                        dest: '<%= jsDir %>/vendor/fine-uploader.js'
                    },
                    {
                        src: ['<%= componentsDir %>/fineuploader-dist/dist/placeholders/not_available-generic.png'],
                        dest: '<%= jsDir %>/vendor/placeholders/not_available-generic.png'
                    },
                    {
                        src: ['<%= componentsDir %>/fineuploader-dist/dist/placeholders/waiting-generic.png'],
                        dest: '<%= jsDir %>/vendor/placeholders/waiting-generic.png'
                    }
                ]
            }
        },

        // Compiles the sass files into the public js
        compass: {
            dev: {
                options: {
                    sassDir: '<%= sassDir %>',
                    cssDir: '<%= cssDir %>',
                    environment: 'development'
                }
            },
            prod: {
                options: {
                    sassDir: '<%= sassDir %>',
                    cssDir: '<%= cssDir %>',
                    environment: 'production',
                    outputStyle: 'compressed'
                }
            }
        },

        // Compiles coffee files
        coffee: {
            main: {
                expand: true,
                cwd: '<%= coffeeDir %>',
                src: ['**/*.coffee'],
                dest: '<%= jsDir %>',
                ext: '.js'
            }
        },

        // Lints the coffee files
        coffeelint: {
            main: {
                files: [
                    {
                        expand: true,
                        src: ['<%= coffeeDir %>/**/*.coffee']
                    }
                ]
            }
        },

        // Uglifies compiled js files
        uglify: {
            main: {
                files: [
                    {
                        expand: true,
                        cwd: '<%= jsDir %>',
                        src: '**/*.js',
                        dest: '<%= jsDir %>'
                    }
                ]
            }
        },

        watch: {
            sass: {
                files: ['<%= sassDir %>/**'],
                tasks: ['sass:watch'],
                options: {
                    spawn: false
                }
            },
            coffee: {
                files: ['<%= coffeeDir %>/**'],
                tasks: ['coffee:watch', 'templates:watch'],
                options: {
                    spawn: false
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-coffee');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-coffeelint');

    // Task for watching SASS file changes
    grunt.registerTask('sass:watch', ['compass:dev']);

    // Task for watching Coffee file changes
    grunt.registerTask('coffee:watch', ['coffee']);

    // Task for watching templates changes
    grunt.registerTask('templates:watch', ['copy:templates']);

    // Task to run when deploying
    grunt.registerTask('dev', ['clean', 'copy:templates', 'copy:fonts', 'copy:js', 'copy:images', 'compass:prod', 'coffee']);

    // Task to run when deploying
    grunt.registerTask('prod', ['clean', 'copy:templates', 'copy:fonts', 'copy:js', 'copy:images', 'compass:prod', 'coffee', 'uglify']);

    // Default task
    grunt.registerTask('default', ['prod']);
};
