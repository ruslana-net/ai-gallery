
define ['backbone', 'entities/album'], (Backbone, Album) ->

  class Albums extends Backbone.Collection
    model: Album
    url: 'api/albums'
    parse: (response) ->
      @.paginationData = response.paginationData
      response.items
