module.exports = (grunt) ->
  grunt.initConfig(
    pkg: grunt.file.readJSON('package.json')
    project:
      app: 'webapp'
      asset_path:  '<%= project.app %>/assets'
      css_path:    '<%= project.asset_path %>/css'
      less_path:   'extras/dev/assets/less'
    less:
      app:
        files:
          '<%= project.css_path %>/makerbase.css': '<%= project.less_path %>/makerbase.less'
    watch:
      css:
        files: '<%= project.less_path %>/*'
        tasks: ['less']
  )
  grunt.loadNpmTasks('grunt-contrib-watch')
  grunt.loadNpmTasks('grunt-contrib-less')
