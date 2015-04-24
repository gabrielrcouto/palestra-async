var themeFolder = 'public/';

/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON('package.json'),
    banner: '/*! <%= pkg.title || pkg.name %> - v<%= pkg.version %> - ' +
      '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
      '<%= pkg.homepage ? "* " + pkg.homepage + "\\n" : "" %>' +
      '* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>;' +
      ' Licensed <%= _.pluck(pkg.licenses, "type").join(", ") %> */\n',

    compass: {
      site: {
        options: {
          sassDir: themeFolder + 'sass',
          cssDir: themeFolder + 'css',
          specify: [
            themeFolder + 'sass/style.scss'
          ],
          noLineComments: true
        }
      },
    },

    concat: {
      options: {
      
      },
      
      site : {
        src: [
          themeFolder + 'js/base.js', 
          themeFolder + 'js/modules/**/*.js',
          themeFolder + 'js/pages/**/*.js',

          themeFolder + 'js/start.js'
        ],

        dest: themeFolder + 'js/code.min.js',
      },
    },

    watch: {
      options: {
        livereload: true,
      },
      siteSass: {
        files: [themeFolder + 'sass/**/*.scss'],
        tasks: ['compass:site'],
      },
      siteJs: {
        files: [themeFolder + 'js/**/*.js', '!' + themeFolder + 'js/code.min.js'],
        tasks: ['concat:site'],
        options: {
          livereload: true,
        },
      },
      siteCodeJs: {
        files: [themeFolder + 'js/code.min.js'],
        tasks: [],
      },
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-concat');

  // Default task.
  grunt.registerTask('default', ['watch']);
  grunt.registerTask('concatenate', ['concat']);

};
