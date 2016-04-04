
define [
  'marionette'
], (Marionette) ->

  class Controller extends Marionette.Controller

    constructor: (options = {}) ->
      @region = options.region
      super options

    close: (args...) ->
      delete @region
      delete @options
      super args

    show: (view) ->
      @listenTo view, 'close', @close
      @region.show view
