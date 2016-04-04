
define [
  'marionette'
  'text!apps/albums/list/templates/empty.html'
], (
  Marionette
  Template
) ->
  class EmptyView extends Marionette.ItemView
    template: Template
