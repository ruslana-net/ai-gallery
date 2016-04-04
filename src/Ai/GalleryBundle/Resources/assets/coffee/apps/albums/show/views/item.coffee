
define [
  'marionette'
  'text!apps/albums/show/templates/item.html'
], (Marionette
    Template) ->

  class ItemView extends Marionette.ItemView
    template: Template
    tagName: 'div'
    className: 'col-xs-6 col-sm-6 col-md-3 images-list-item'
