
define [
  'marionette'
  'backbone'
  'apps/albums/list/views/item'
  'apps/albums/list/views/empty'
  'text!apps/albums/list/templates/list.html'
], (Marionette, Backbone, ItemView, EmptyView, Template) ->

  class ListView extends Marionette.CollectionView
    template: Template
    tagName: 'div'
    className: 'row'
    itemView: ItemView
    emptyView: EmptyView
    itemViewEventPrefix: 'item'