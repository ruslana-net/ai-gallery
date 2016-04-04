
define [
  'marionette'
  'text!apps/albums/edit/templates/main.html'
], (Marionette, Template) ->

  class MainView extends Marionette.Layout
    template: Template
    regions:
      alert: '#alert-region'
      form:  '#form-region'
