
define [
  'core/bus'
  'marionette'
  'text!apps/albums/show/templates/search.html'
], (Bus, Marionette, Template) ->

  class SearchView extends Marionette.ItemView
    template: Template
    triggers:
      'input input': 'search:image:changed'

    initialize: (options) ->
      @search = if options.search? then options.search else null

      @.on 'render', =>
        if @search != null
          @updateSearch @search

    updateSearch: (search) ->
      @.$el.find('input[name="search"]').val search
