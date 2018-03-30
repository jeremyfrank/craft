var globImporter = require('node-sass-glob-importer')

module.exports = {
  images      : true,
  fonts       : true,
  svgSprite   : true,

  stylesheets : {
    cssnano: {
      safe: true
    },
    sass: {
      importer: globImporter()
    }
  },

  javascripts: {
    entry: {
      // files paths are relative to
      // javascripts.dest in path-config.json
      app: ["./app.js"]
    },
    // This tells webpack middleware where to
    // serve js files from in development:
    publicPath: "/assets/javascripts"
  },

  browserSync: {
    // Update this to match your development URL
    proxy: 'craft-ssk.test',
    files: ['templates/**/*']
  },

  production: {
    rev: true
  },

  ghPages     : false,
  html        : false,
  static      : false,
}
