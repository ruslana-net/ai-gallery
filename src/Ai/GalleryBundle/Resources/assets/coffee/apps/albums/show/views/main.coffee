
define [
  'marionette'
  'text!apps/albums/show/templates/main.html'
], (Marionette, Template) ->

  class MainView extends Marionette.Layout
    template: Template
    regions:
      alert: '#alert-region'
      search: '#search-region'
      list: '#list-region'
      pagination: '#pagination-region'

    initialize: (options) ->
      @album = options.album

    templateHelpers: ->
      if(@album)
        albumName: @album.get('name')