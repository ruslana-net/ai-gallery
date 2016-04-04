
define [
  'core/bus'
  'marionette'
  'text!apps/albums/list/templates/search.html'
], (Bus, Marionette, Template) ->

  class SearchView extends Marionette.ItemView
    template: Template
    triggers:
      'input input': 'search:album:changed'

    initialize: (options) ->
      @search = if options.search? then options.search else null

      @.on 'render', =>
        if @search != null
          @updateSearch @search

    updateSearch: (search) ->
      @.$el.find('input[name="search"]').val search
