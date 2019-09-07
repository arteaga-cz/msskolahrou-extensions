module.exports = function (grunt) {

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		config: {
			/*src: 'src/assets/',
			dist: 'assets/',*/
			npm: 'node_modules/'
		},

		sass: {
			build: {
				options: {
					style: 'expanded', // nested, compact, compressed, expanded
					sourceMap: true,
				},
				files: {
					'public/css/wpupee-public.css': 'src/public/scss/wpupee-public.scss'
				}
			}
		},

		concat: {
			options: {
				separator: ';',
			},
			dist: {
				src: ['src/public/js/elementor/elementor-base.js', 'src/public/js/elementor/widgets/*.js', 'src/public/js/*.js'],
				dest: 'public/js/wpupee-public.js',
			},
		},

		uglify: {
			dist: {
				options: {
					manage: false,
					//preserveComments: 'all' //preserve all comments on JS files
				},
				files: [
					{
						expand: true,
						src: ['*.js', '!*.min.js'],
						cwd: 'admin/js/',
						dest: 'admin/js/',
						ext: '.min.js'
					},
					{
						expand: true,
						src: ['*.js', '!*.min.js'],
						cwd: 'public/js/',
						dest: 'public/js/',
						ext: '.min.js'
					}
				]
			}
		},

		cssmin: {
			dist: {
				files: [
					{
						expand: true,
						cwd: 'admin/css/',
						src: ['*.css', '!*.min.css'],
						dest: 'admin/css/',
						ext: '.min.css'
					},
					{
						expand: true,
						cwd: 'public/css/',
						src: ['*.css', '!*.min.css'],
						dest: 'public/css/',
						ext: '.min.css'
					}
				]
			}
		},

		copy: {
			main: {
				files: [
					// makes all src relative to cwd
					{expand: true, cwd: 'src/public/js/vendor/', src: ['**'], dest: 'public/js/vendor/'},
				],
			},
		},

		// make a zipfile
		compress: {
			main: {
				options: {
					archive: 'msskolahrou-extensions.zip'
				},
				files: [
					//{src: ['admin/**', '!admin/**/*.js', '!admin/**/*.css', 'admin/**/*.min.js', 'admin/**/*.min.css'], dest: '/'}, // includes files in path and its subdirs
					{src: ['assets/**'], dest: '/'}, // includes files in path and its subdirs
					{src: ['includes/**'], dest: '/'}, // includes files in path and its subdirs
					{src: ['languages/**'], dest: '/'}, // includes files in path and its subdirs
					//{src: ['public/**', '!public/**/*.js', '!public/**/*.css', 'public/**/*.min.js', 'public/**/*.min.css'], dest: '/'}, // includes files in path and its subdirs
					{src: ['*.php'], dest: '/'}, // includes root PHP files
					{src: ['*.css'], dest: '/'}, // includes root CSS files
					{src: ['*.txt'], dest: '/'}, // includes root TXT files
					{src: ['*.md'], dest: '/'}, // includes root MD files
				]
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-contrib-sass');

	//grunt.registerTask('default', ['sass', 'concat', 'uglify', 'cssmin', 'copy', 'compress']);
	grunt.registerTask('default', ['compress']);

};
