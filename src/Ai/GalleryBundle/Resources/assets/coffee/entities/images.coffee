
define ['backbone', 'entities/image'], (Backbone, Image) ->

  class Images extends Backbone.Collection
    model: Image
    url: 'api/images'
    parse: (response) ->
      @.paginationData = response.paginationData
      response.items
