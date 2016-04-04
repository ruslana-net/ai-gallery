
define [
  'config',
  'core/bus'
  'marionette',
  'entities/album'
  'entities/image'
  'entities/images'
  'text!apps/albums/edit/templates/form.html'
  'fine-uploader'
], (Config, Bus, Marionette, Album, Image, Images, Template) ->

  class FormView extends Marionette.ItemView
    template: Template
    model: Album
    images: Images
    @albUploader
    @imgUploader

    triggers:
      'click #btn-close': 'close:album:clicked'
      'click #btn-save': 'save:album:clicked'

    modelEvents:
      'sync:start': 'onSyncStart'
      'sync:stop': 'onSyncStop'
      'error': 'onError'

    events:
      'change .edit-filename-selector': 'updateImageName'
      'keypress .edit-filename-selector': 'updateImageName'
      'keypress #album-name': 'saveAlbum'

    albumIconUploader: ->
      $this = @
      @albUploader = new qq.FineUploader
        element: document.getElementById('fine-uploader-manual-trigger')
        template: 'qq-template-manual-trigger'
        multiple: false
        request:
          endpoint: Config.albumsUploaderPath
        thumbnails:
          placeholders:
            waitingPath: '/bundles/aigallery/js/vendor/placeholders/waiting-generic.png'
            notAvailablePath: '/bundles/aigallery/js/vendor/placeholders/not_available-generic.png'
        autoUpload: true
        validation:
          acceptFiles: 'image/*'
        debug: Config.debug
        session:
          endpoint: $('#album-icon').data('endpoint')
        callbacks:
          onComplete: (id, name, responseJson) ->
            if(responseJson.qqfile)
              $('#album-icon').val(responseJson.qqfile)
              $this.model.set('icon', responseJson.qqfile)

    albumImagesUploader: ->
      $this = @
      sessionEndpoint = null
      if(@model.has('id'))
        sessionEndpoint = '/api/album/images/' + @model.get('id')
      @imgUploader = new qq.FineUploader
        element: document.getElementById('fine-uploader-manual-trigger-images')
        template: 'qq-template-manual-trigger-images'
        multiple: true
        request:
          endpoint: Config.imagesUploaderPath
        session:
          endpoint: sessionEndpoint
        deleteFile:
          enabled: true
          endpoint: Config.imageDeletePath
        thumbnails:
          placeholders:
            waitingPath: '/bundles/aigallery/js/vendor/placeholders/waiting-generic.png'
            notAvailablePath: '/bundles/aigallery/js/vendor/placeholders/not_available-generic.png'
        autoUpload: true
        validation:
          acceptFiles: 'image/*'
        debug: Config.debug
        callbacks:
          onSessionRequestComplete: (response, success) ->
            imgObj = @
            if(success)
              $.each response, (id,item) ->
                imgItem = imgObj.getItemByFileId(id)
                nameItem = $(imgItem).find('.edit-filename-selector');
                nameItem.val(item.imageName)
                nameItem.attr('data-id', item.uuid)
                @
            imgObj
          onComplete: (id, name, responseJson) ->
            if(responseJson.qqfile)
              imgObj = @
              data = {
                'image': responseJson.qqfile,
                'album': $this.model
              }
              image = new Image(data)
              image.save data,
                success: (model) ->
                  imgObj.setUuid(id, model.get('id'))

                  imgItem = imgObj.getItemByFileId(id)
                  nameItem = $(imgItem).find('.edit-filename-selector')
                  nameItem.attr('data-id', model.get('id'))

    updateImageName: (ev) ->
      if(ev.type == 'keypress' && ev.keyCode != 13)
        return true

      ev.preventDefault()
      el = $(ev.currentTarget)

      image = Bus.reqres.request 'image:entities', el.data('id'), =>
        if(image)
          image.set('album', @model)
          image.set('name', el.val())
          image.save()
      @

    saveAlbum: (ev) ->
      if(ev.type == 'keypress' && ev.keyCode != 13)
        return true

      ev.preventDefault()
      @triggerMethod('save:album:clicked')

    onShow: ->
      @albumIconUploader()
      if(@model.has('id'))
        @albumImagesUploader()
      @

    onSyncStart: ->
      @removeInputErrors()
      @blockForm()

    onSyncStop: ->
      @unblockForm()

    onError: (model, response) ->
      if response.status == 400
        errors = JSON.parse response.responseText
        @showInputErrors errors

    blockForm: ->
      $('#btn-save').attr 'disabled', true

    unblockForm: ->
      $('#btn-save').attr 'disabled', false

    showInputErrors: (errors) ->
      for field, errors of errors.errors.children
        if errors.errors?
          fieldErrors = errors.errors
          inputElement = @.$el.find '[name="' + field + '"]'
          inputElement.addClass 'input-error'
          errorsElement = $ '<ul class="input-error-text"></ul>'
          inputElement.after errorsElement

          for errorString in fieldErrors
            errorsElement.append '<li>' + errorString + '</li>'

    removeInputErrors: ->
      @.$el.find('.input-error-text').remove()
      @.$el.find('.input-error').removeClass 'input-error'

