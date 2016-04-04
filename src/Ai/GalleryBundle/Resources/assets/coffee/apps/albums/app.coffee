
define [
  'marionette'
  'core/bus'
  'apps/albums/header/app'
  'apps/albums/list/controller'
  'apps/albums/show/controller'
  'apps/albums/edit/controller'
], (Marionette, Bus, HeaderApp, ListController, ShowController, EditController) ->

  class AppRouter extends Marionette.AppRouter
    appRoutes:
      '': 'list'
      ':page': 'list'
      'album': 'create'
      'album/:id': 'show'
      'album/:id/page/:page': 'show'
      'album/:id/edit': 'edit'

  API =
    list: (page, params) ->
      search = null
      if params? && params.search?
        search = decodeURIComponent params.search

      new ListController
        region: Bus.reqres.request 'main_region'
        search: search
        page: page

    create: ->
      new EditController
        region: Bus.reqres.request 'main_region'

    show: (id, page=1, params) ->
      search = null
      if params? && params.search?
        search = decodeURIComponent params.search
      new ShowController
        id: id
        page: page
        search: search
        region: Bus.reqres.request 'main_region'

    edit: (id, album) ->
      new EditController
        id: id
        album: album
        region: Bus.reqres.request 'main_region'

  # Handle redirection to album's show page
  Bus.commands.setHandler 'album:redirect:show', (id = null) ->
    if id == null
      Backbone.history.navigate '/'
    else
      Backbone.history.navigate 'album/' + id
    API.show id

  # Handle redirection to album's edit page
  Bus.commands.setHandler 'album:redirect:edit', (id = null, album = null) ->
    if id == null
      Backbone.history.navigate 'album'
    else
      Backbone.history.navigate 'album/' + id + '/edit'
    API.edit id, album

  Bus.commands.setHandler 'albums:redirect', ->
    Backbone.history.navigate '/', trigger: true

  Bus.commands.setHandler 'albums:navigate', (search = null, page=null) ->
    if page == ''
      route = '/'
    else
      route = '/' + page

    if search == '' || search == null
      route = route
    else
      route = route + '?search=' +  encodeURIComponent search
    Backbone.history.navigate route

  Bus.commands.setHandler 'images:navigate', (album, search = null, page=null) ->
    if page == ''
      route = '/album/' + album.get('id')
    else
      route = '/album/' + album.get('id') + '/page/' + page

    if search == '' || search == null
      route = route
    else
      route = route + '?search=' +  encodeURIComponent search
    Backbone.history.navigate route

  albumsApp = new Marionette.Application

  albumsApp.addInitializer ->
    new AppRouter
      controller: API

  albumsApp
