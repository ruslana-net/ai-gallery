
define [
  'core/bus'
  'apps/albums/header/controller'
], (Bus, HeaderController) ->

  Bus.commands.setHandler 'show:albums:header', ->
    new HeaderController
      region: Bus.reqres.request 'header_region'
