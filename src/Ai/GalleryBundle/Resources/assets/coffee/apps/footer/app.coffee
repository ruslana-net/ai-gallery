define [
  'marionette'
  'core/bus'
  'apps/footer/controller'
], (Marionette, Bus, FooterController) ->

  app = new Marionette.Application()

  app.on 'start', ->
    new FooterController
      region: Bus.reqres.request 'footer_region'

  app
