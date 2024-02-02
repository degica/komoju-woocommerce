const { defineConfig } = require('cypress')

module.exports = defineConfig({
  chromeWebSecurity: false,
  video: false,
  e2e: {
    // We've imported your old cypress plugins here.
    // You may want to clean this up later by importing these.
    setupNodeEvents(on, config) {
      return require('./cypress/plugins/index.js')(on, config)
    },
    baseUrl: 'http://localhost:8000',
    defaultCommandTimeout: 6000,
    // default pageLoadTimeout is 60000ms
    pageLoadTimeout: 120000,
  },
  retries: {
    runMode: 2,
    openMode: 0,
  }
})
