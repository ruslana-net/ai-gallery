
define [
  'marionette'
  'text!apps/albums/list/templates/item.html'
], (Marionette
    Template) ->

  class ItemView extends Marionette.ItemView
    template: Template
    tagName: 'div'
    className: 'col-xs-12 col-sm-6 col-md-4 albums-list-item'
    triggers:
      'click .btn-delete': 'delete:album:clicked'
      'click .btn-edit':   'edit:album:clicked'
      'click .album-show': 'show:album:clicked'
