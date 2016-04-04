
define [
  'marionette'
  'text!apps/footer/templates/footer.html'
], (Marionette, Template) ->

  class FooterView extends Marionette.ItemView
    template: Template
