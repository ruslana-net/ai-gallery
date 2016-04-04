
define [
  'marionette'
  'text!apps/albums/show/templates/empty.html'
], (
  Marionette
  Template
) ->
  class EmptyView extends Marionette.ItemView
    template: Template
