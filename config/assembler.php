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
    | php artisan vendor:publish --provider="Esensi\Assembler\Providers\AssemblerServiceProvider"
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
        'App\Console\Commands\BuildCommand'        => Esensi\Assembler\Console\Commands\BuildCommand::class,
        'App\Console\Commands\BuildWatchCommand'   => Esensi\Assembler\Console\Commands\BuildWatchCommand::class,
        'App\Console\Commands\BuildCleanCommand'   => Esensi\Assembler\Console\Commands\BuildCleanCommand::class,
        'App\Console\Commands\BuildStylesCommand'  => Esensi\Assembler\Console\Commands\BuildStylesCommand::class,
        'App\Console\Commands\BuildScriptsCommand' => Esensi\Assembler\Console\Commands\BuildScriptsCommand::class,
        'App\Console\Commands\BuildImagesCommand'  => Esensi\Assembler\Console\Commands\BuildImagesCommand::class,
        'App\Console\Commands\BuildFontsCommand'   => Esensi\Assembler\Console\Commands\BuildFontsCommand::class,
        'App\Console\Commands\BuildLintCommand'    => Esensi\Assembler\Console\Commands\BuildLintCommand::class,
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
        'base'    => '/',
        'fonts'   => 'fonts',
        'images'  => 'img',
        'scripts' => 'js',
        'styles'  => 'css',
    ],

];
