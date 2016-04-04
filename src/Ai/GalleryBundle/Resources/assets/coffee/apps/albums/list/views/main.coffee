
define [
  'marionette'
  'text!apps/albums/list/templates/main.html'
], (Marionette, Template) ->

  class MainView extends Marionette.Layout
    template: Template
    regions:
      alert: '#alert-region'
      search: '#search-region'
      list: '#list-region'
      pagination: '#pagination-region'
