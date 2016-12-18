PHP Extension Stub Generator
===========================================================

PHP ReflectionExtension's Information Rewind to PHP Code As Stub.

# Purpose
Code Completion under IDE.

## USAGE

```
$ php-extension-stub-generator.phar dump-files {extension name} {dir} 
```

## USAGE Example

```
$ php-extension-stub-generator.phar dump-files ast tmp
```

```
$ php -d extension=/home/you/git/nikic_php-ast/modules/ast.so php-extension-stub-generator.phar dump-files ast tmp
```


## BUILDING phar

to build phar, please install box.

```
$ composer.phar global require kherge/box --prefer-source
```

and run,

```
$ php -d phar.readonly=0 ~/.composer/vendor/bin/box build
```

## MOSTELY YOU DON'T NEED

  - http://stackoverflow.com/questions/30328805/phpstorm-how-to-add-method-stubs-from-a-pecl-library-that-phpstorm-doesnt-curr