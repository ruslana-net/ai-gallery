
define [
  'marionette'
  'jquery'
  'text!components/alert/templates/alert.html'
  'bootstrap'
], (Marionette, $, Template) ->

  class AlertView extends Marionette.ItemView
    template: Template
    events:
      'click button': 'onClose'

    onClose: ->
      $('.alert').alert 'close'
      @trigger 'alert:closed'
