define [
  'marionette'
  'syphon'
  'core/bus'
  'core/controller'
  'apps/albums/list/views/main'
  'apps/albums/list/views/search'
  'apps/albums/list/views/list'
  'apps/albums/list/views/pagination'
  'entities/repository'
], (
  Marionette
  Syphon
  Bus
  Controller
  MainView
  SearchView
  ListView
  PaginationView
) ->

  class ListController extends Controller

    initialize: (options) ->
      @search = if options.search? then options.search else null
      @page = if options.page? then options.page else 1
      @collection = Bus.reqres.request 'albums:entities', @search, @page, =>
        Bus.commands.execute 'show:albums:header'
        @showMainView @collection, @search

    showMainView: (collection, search) ->
      @mainView = @getMainView()

      @listenTo @mainView, 'show', =>
        @showSearchView search, collection
        @showListView collection

      @show @mainView

    showSearchView: (search, collection) ->
      $this = @
      searchView = @getSearchView search

      @listenTo searchView, 'search:album:changed', ->
        searchData = Syphon.serialize searchView
        search = searchData.search
        Bus.commands.execute 'albums:navigate', search, 1

        @collection.fetch
          reset: true
          data:
            search: search
            page: 1
          success: (collection) ->
            $this.showPaginationView(collection, search, 1)

      @mainView.search.show searchView
      @showPaginationView(@collection, search, 1)

    showPaginationView: (collection, search, page) ->
      paginationView = @getPaginationView collection, search
      @listenTo paginationView, 'albums:page:changed', (page, search) ->
        Bus.commands.execute 'albums:navigate', search, page
        @collection.fetch
          reset: true
          data:
            page: page
            search: search

      @mainView.pagination.show paginationView

    showListView: (collection) ->
      listView = @getListView collection

      @listenTo listView, 'item:add:album:clicked', ->
        Bus.commands.execute 'album:redirect:edit'

      @listenTo listView, 'item:delete:album:clicked', (view) ->
        model = view.model
        albumName = model.get('name')
        model.destroy
          success: =>
            @showAlertView 'success', 'Album "' + albumName + '" successfully deleted'
          error: =>
            @showAlertView 'danger', 'Failed to delete the album "' + albumName + '"'

      @listenTo listView, 'item:edit:album:clicked', (view) ->
        Bus.commands.execute 'album:redirect:edit', view.model.id, view.model

      @listenTo listView, 'item:show:album:clicked', (view) ->
        Bus.commands.execute 'album:redirect:show', view.model.id, view.model

      @mainView.list.show listView

    showAlertView: (type, message) ->
      Bus.commands.execute 'show:alert', @mainView.alert, type, message

    getMainView: ->
      new MainView

    getSearchView: (search) ->
      new SearchView
        search: search

    getListView: (collection) ->
      new ListView
        collection: collection

    getPaginationView: (collection, search) ->
      new PaginationView collection.paginationData, search