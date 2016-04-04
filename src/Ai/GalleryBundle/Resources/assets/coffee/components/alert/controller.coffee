
define [
  'backbone'
  'marionette'
  'core/controller'
  'components/alert/view'
], (Backbone, Marionette, Controller, AlertView) ->

  class AlertController extends Controller

    initialize: (options) ->
      { type, message } = options
      @showAlertView type, message

    showAlertView: (type, message) ->
      model = new Backbone.Model
        type: type
        message: message

      alertView = @getAlertView model

      @listenTo alertView, 'alert:closed', =>
        @region.close alertView

      @show alertView

    getAlertView: (model) ->
      new AlertView
        model: model
