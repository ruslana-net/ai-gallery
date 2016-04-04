
define [
  'core/bus'
  'core/controller'
  'apps/albums/header/views/header'
], (Bus, Controller, HeaderView) ->

  class AlbumsController extends Controller

    initialize: ->
      @showHeaderView()

    showHeaderView: ->
      headerView = @getHeaderView()

      @listenTo headerView, 'add:album:clicked', ->
        Bus.commands.execute 'album:redirect:edit'

      @show headerView

    getHeaderView: ->
      new HeaderView
