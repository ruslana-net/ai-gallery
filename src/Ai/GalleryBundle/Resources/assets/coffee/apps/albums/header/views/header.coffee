
define [
  'marionette'
  'text!apps/albums/header/templates/header.html'
], (Marionette, Template) ->

  class HeaderView extends Marionette.ItemView
    template: Template
    className: 'navbar-wrapper'
    triggers:
      'click #btn-add': 'add:album:clicked'
