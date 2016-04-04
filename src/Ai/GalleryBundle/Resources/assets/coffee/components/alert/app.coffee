
define [
  'core/bus'
  'marionette'
  'components/alert/controller'
], (Bus, Marionette, AlertController) ->

  Bus.commands.setHandler 'show:alert', (region, type, message) ->
    new AlertController
      region: region
      type: type
      message: message
