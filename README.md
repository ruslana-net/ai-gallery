# Gallery

The backend is a REST API based on [Symfony2](http://symfony.com)
and the frontend is a [Marionette](http://marionettejs.com) MVC application in CoffeeScript.
[Demo](http://gallery.zap.center/)

## Installation

### Requirements

- [Composer](https://getcomposer.org/download)
- [NPM](https://www.npmjs.org)
- [Bower](http://bower.io)
- [Grunt](http://gruntjs.com)

You will need the APC extension to run the application in the prod environment.

### Steps

```bash
$ git clone https://github.com/ruslana-net/ai-gallery
$ composer update
$ php app/console doctrine:database:create --env=prod
$ php app/console doctrine:schema:update --force --env=prod
$ php app/console doctrine:fixtures:load --env=prod
$ npm install
$ bower install
$ grunt
```

## Running tests

```bash
$ phpunit -c app/
```

## License

[MIT](https://github.com/ruslana-net/ai-gallery/blob/master/LICENSE)