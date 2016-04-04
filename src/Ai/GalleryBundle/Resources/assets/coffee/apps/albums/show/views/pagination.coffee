
define [
  'marionette'
  'text!apps/albums/show/templates/pagination.html'
], (
  Marionette
  Template
) ->
  class PaginationView extends Marionette.ItemView
    template: Template
    tagName: 'div'
    className: 'row text-center'
    pagination: {}

    events:
      'click a': 'changePage'

    initialize: (album, pagination, search) ->
      @album = album
      @pagination = pagination
      @search = search

    render: ->
      if @pagination.pageCount > 1
        html = $('<ul class="pagination">')
        for i in @pagination.pagesInRange
          css=''
          if(parseInt(@pagination.current) == i)
            css='active'
          url = '#album/' + @album.get('id')
          if(i > 1)
            url = url + '/page/' + i
          if(@search != '' && @search != null)
            url = url + '?search=' + @search
          link = $('<a>')
                  .attr('href', url)
                  .attr('data-page', i)
                  .html(i)
          if(@search != '' && @search != null)
            link.attr('data-search', @search)
          li = $('<li>').addClass(css).html(link)
          html.append(li)
        $(@.el).html(html)

    changePage: (ev) ->
      ev.preventDefault()
      a = $(ev.currentTarget)
      if(a.parent('li').hasClass('active') == false)
        $(@.el).find('li').removeClass('active')
        a.parent('li').addClass('active')
        page = a.attr('data-page')
        search = a.attr('data-search')
        @.trigger('images:page:changed', page, search)
