
define ['marionette', 'handlebars', 'moment'], (Marionette, Handlebars, Moment) ->

  Handlebars.default.registerHelper 'formatDate', (string) ->
    moment = Moment string, 'YYYY-MM-DD HH:mm:ss'
    moment.format 'MMM Do YYYY'

  Marionette.Renderer.render = (template, data) ->
    return if template == false
    compiled = Handlebars.default.compile template
    compiled data
