
define [
  'marionette'
  'core/controller'
  'apps/footer/views/footer'
], (Marionette, Controller, FooterView) ->

  class FooterController extends Controller

    initialize: ->
      footerView = @getFooterView()
      @show footerView

    getFooterView: ->
      new FooterView
