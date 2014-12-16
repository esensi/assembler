<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration values for Esensi\Assembler components package
    |--------------------------------------------------------------------------
    |
    | The following lines contain the default configuration values for the
    | Esensi\Assembler components package. You can publish these to your project for
    | modification using the following Artisan command:
    |
    | php artisan config:publish esensi/assembler
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Application aliases
    |--------------------------------------------------------------------------
    |
    | The following configuration options allow the developer to map aliases to
    | controllers and models for easier customization of how Esensi handles
    | requests related to this components package. These aliases are loaded by
    | the service provider for this components package.
    |
    */

    'aliases' => [
        'EsensiBuildCommand'        => 'Esensi\Assembler\Commands\BuildCommand',
        'EsensiBuildWatchCommand'   => 'Esensi\Assembler\Commands\BuildWatchCommand',
        'EsensiBuildCleanCommand'   => 'Esensi\Assembler\Commands\BuildCleanCommand',
        'EsensiBuildStylesCommand'  => 'Esensi\Assembler\Commands\BuildStylesCommand',
        'EsensiBuildScriptsCommand' => 'Esensi\Assembler\Commands\BuildScriptsCommand',
        'EsensiBuildImagesCommand'  => 'Esensi\Assembler\Commands\BuildImagesCommand',
        'EsensiBuildFontsCommand'   => 'Esensi\Assembler\Commands\BuildFontsCommand',
        'EsensiBuildLintCommand'    => 'Esensi\Assembler\Commands\BuildLintCommand',
    ],

    /*
    |--------------------------------------------------------------------------
    | Gulp Binary Location
    |--------------------------------------------------------------------------
    |
    | The following configuration option sets where the "gulp" command can be
    | found. For a local install it defaults to node_modules/.bin/gulp however
    | if installed globally you can set it with this option.
    |
    */

    'binary' => null,

    /*
    |--------------------------------------------------------------------------
    | Production Environments
    |--------------------------------------------------------------------------
    |
    | The following configuration options set which environments should be
    | treated as "production" environments. In these environments the build
    | commands will always run with the --production switch.
    |
    */

    'environments' => [
        'production'
    ],

    /*
    |--------------------------------------------------------------------------
    | Builds Directories
    |--------------------------------------------------------------------------
    |
    | The following configuration option sets where the builds should be stored.
    | The base path is relative to the public directory while the other paths
    | are relative to the base path. If you make changes here be sure to change
    | the same configurations in the Gulpfile.js.
    |
    */

    'directories' => [
        'base'    => 'builds',
        'fonts'   => 'fonts',
        'images'  => 'images',
        'scripts' => 'scripts',
        'styles'  => 'styles',
    ],

];
