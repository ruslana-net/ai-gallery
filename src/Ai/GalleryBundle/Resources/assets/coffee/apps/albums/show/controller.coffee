define [
  'marionette'
  'syphon'
  'core/bus'
  'core/controller'
  'apps/albums/show/views/main'
  'apps/albums/show/views/search'
  'apps/albums/show/views/list'
  'apps/albums/show/views/pagination'
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
      if options.id?
        @album = Bus.reqres.request 'album:entities', options.id, =>
          Bus.commands.execute 'show:albums:header'
        @collection = Bus.reqres.request 'images:entities', options.id, @search, @page, =>
          Bus.commands.execute 'show:albums:header'
          @showMainView @album, @collection, @search

    showMainView: (album, collection, search) ->
      @mainView = @getMainView(album)

      @listenTo @mainView, 'show', =>
        @showSearchView search, collection
        @showListView collection

      @show @mainView

    showSearchView: (search, collection) ->
      $this = @
      searchView = @getSearchView search

      @listenTo searchView, 'search:image:changed', ->
        searchData = Syphon.serialize searchView
        search = searchData.search
        Bus.commands.execute 'images:navigate', @album, search, 1

        @collection.fetch
          reset: true
          data:
            albumId: @album.get('id')
            search: search
            page: 1
          success: (collection) ->
            $this.showPaginationView(collection, search, 1)

      @mainView.search.show searchView
      @showPaginationView(@collection, search, 1)

    showPaginationView: (collection, search, page) ->
      paginationView = @getPaginationView @album, collection, search
      @listenTo paginationView, 'images:page:changed', (page, search) ->
        Bus.commands.execute 'images:navigate', @album, search, page
        @collection.fetch
          reset: true
          data:
            albumId: @album.get('id')
            page: page
            search: search

      @mainView.pagination.show paginationView

    showListView: (collection) ->
      listView = @getListView collection

      @listenTo listView, 'item:add:image:clicked', ->
        Bus.commands.execute 'images:redirect'

      @listenTo listView, 'item:delete:image:clicked', (view) ->
        model = view.model
        imageName = model.get('name')
        model.destroy
          success: =>
            @showAlertView 'success', 'Image "' + imageName + '" successfully deleted'
          error: =>
            @showAlertView 'danger', 'Failed to delete the image "' + imageName + '"'

      @listenTo listView, 'item:show:image:clicked', (view) ->
        Bus.commands.execute 'image:redirect', view.model.id, view.model

      @mainView.list.show listView

    showAlertView: (type, message) ->
      Bus.commands.execute 'show:alert', @mainView.alert, type, message

    getMainView: (album) ->
      new MainView
        album: album

    getSearchView: (search) ->
      new SearchView
        search: search

    getListView: (collection) ->
      new ListView
        collection: collection

    getPaginationView: (album, collection, search) ->
      new PaginationView album, collection.paginationData, search