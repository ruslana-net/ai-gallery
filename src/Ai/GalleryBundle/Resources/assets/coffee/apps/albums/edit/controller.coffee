
define [
  'marionette'
  'syphon'
  'core/bus'
  'core/controller'
  'apps/albums/edit/views/form'
  'apps/albums/edit/views/main'
], (
  Marionette
  Syphon
  Bus
  Controller
  FormView
  MainView
) ->

  class EditController extends Controller

    initialize: (options) ->
      {album, id} = options

      if album
        Bus.commands.execute 'show:albums:header'
        @showMainView album
      else if options.id?
        album = Bus.reqres.request 'album:entities', id, =>
          Bus.commands.execute 'show:albums:header'
          @showMainView album
      else
        album = Bus.reqres.request 'album:entities'
        Bus.commands.execute 'show:albums:header'
        @showMainView album

    showMainView: (album) ->
      @mainView = @getMainView()

      @listenTo @mainView, 'show', =>
        @showFormView album

      @show @mainView

    showFormView: (album) ->
      formView = @getFormView album

      @listenTo formView, 'close:album:clicked', ->
        Bus.commands.execute 'albums:redirect'

      @listenTo formView, 'save:album:clicked', ->
        @closeAlertView()

        model = formView.model
        wasNew = model.isNew()
        data = Syphon.serialize formView
        delete data.crdate
        delete data.qqfile

        model.save data,
          error: (model, resp) =>
            if resp.status == 400
              message = 'The submitted data is invalid'
            else
              message = resp.statusText
            @showAlertView 'danger', message
          success: =>
            if wasNew
              message = 'Album successfully saved'
              Bus.commands.execute 'album:redirect:edit', model.id
            else
              message = 'Album successfully updated'
            @showAlertView 'success', message

      @mainView.form.show formView

    showAlertView: (type, message) ->
      Bus.commands.execute 'show:alert', @mainView.alert, type, message

    closeAlertView: ->
      @mainView.alert.close()

    getMainView: ->
      new MainView

    getFormView: (album) ->
      new FormView
        model: album
