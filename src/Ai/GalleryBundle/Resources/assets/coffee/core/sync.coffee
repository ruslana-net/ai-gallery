
define ['backbone', 'core/bus'], (Backbone, Bus) ->

  oldSync = Backbone.sync

  Backbone.sync = (method, entity, options = {}) ->

    # Configure triggers on the model & collection before and after syncing
    _.defaults options,
      beforeSend: _.bind(methods.beforeSend, entity)
      complete: _.bind(methods.complete, entity)

    # Apply some headers to all requests
    auth = Bus.reqres.request 'auth'
    options.headers =
      Application: 'application/json'
      'Content-Type': 'application/json'

    errorCallback = (jqXHR) ->

    if options.error?
      _errorCallback = options.error
      options.error = (jqXHR, textStatus, errorThrown) ->
        errorCallback jqXHR
        _errorCallback jqXHR, textStatus, errorThrown
    else
      options.error = (jqXHR) ->
        errorCallback jqXHR

    oldSync(method, entity, options)

  methods =
    beforeSend: ->
      @trigger "sync:start", @

    complete: ->
      @trigger "sync:stop", @
