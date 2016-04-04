
define [
  'config'
  'backbone'
  'marionette'
  'core/bus'
  'apps/footer/app'
  'apps/albums/app'
], (
  Config
  Backbone
  Marionette
  Bus
  FooterApp
  AlbumsApp
) ->

  app = new Marionette.Application

  app.addRegions
    headerRegion: 'header'
    mainRegion: '#main-region'
    footerRegion: 'footer'

  app.addInitializer ->
    FooterApp.start()
    AlbumsApp.start()

  app.on 'initialize:after', ->
    if not Backbone.history.started
      Backbone.history.start
        root: Config.baseUrl

  Bus.reqres.setHandler 'main_region', ->
    app.mainRegion

  Bus.reqres.setHandler 'header_region', ->
    app.headerRegion

  Bus.reqres.setHandler 'footer_region', ->
    app.footerRegion

  app