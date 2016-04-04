
define [
  'marionette'
  'backbone'
  'apps/albums/show/views/item'
  'apps/albums/show/views/empty'
  'text!apps/albums/show/templates/list.html'
], (Marionette, Backbone, ItemView, EmptyView, Template) ->

  class ShowView extends Marionette.CollectionView
    template: Template
    tagName: 'div'
    className: 'row'
    itemView: ItemView
    emptyView: EmptyView
    itemViewEventPrefix: 'item'