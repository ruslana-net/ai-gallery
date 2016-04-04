
define [
  'core/bus',
  'entities/album',
  'entities/albums'
  'entities/images',
  'entities/image',
  ], (Bus, Album, Albums, Images, Image) ->

  Bus.reqres.setHandler 'albums:entities', (search = null, page = 1, success = null) ->
    albums = new Albums
    if search == null
      data = {}
    else
      data = {search: search}

    data.page = page
    albums.fetch
      reset: true
      data: data
      success: success
    albums

  Bus.reqres.setHandler 'album:entities', (id = null, success = null) ->
    if id == null
      return new Album
    album = new Album
      id: id
    album.fetch
      success: success
    album

  Bus.reqres.setHandler 'images:entities', (albumId, search = null, page = 1, success = null) ->
    images = new Images
    if search == null
      data = {}
    else
      data = {search: search}
    data.page = page
    data.albumId = albumId
    images.fetch
      reset: true
      data: data
      success: success
    images

  Bus.reqres.setHandler 'image:entities', (id = null, success = null) ->
    if id == null
      return new Image
    image = new Image
      id: id
    image.fetch
      success: success
    image
