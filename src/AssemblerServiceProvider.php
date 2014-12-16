<?php namespace Esensi\Assembler;

use Esensi\Core\Providers\PackageServiceProvider;
use Illuminate\Console\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

/**
 * Service provider for Esensi\Assembler components package.
 *
 * @package Esensi\Assembler
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/assembler/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class AssemblerServiceProvider extends PackageServiceProvider {

    /**
     * Registers the resource dependencies.
     *
     * @return void
     */
    public function register()
    {
        // Add all of the Artisan commands
        Event::listen('artisan.start', function(Application $artisan)
        {
            foreach(Config::get('esensi/assembler::build.aliases', []) as $alias => $command)
            {
                $artisan->add(new $command());
            }
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Make sure helpers are included
        require_once( __DIR__ . '/helpers.php');

        // Bind build class aliases
        $this->package('esensi/assembler', 'esensi/assembler', __DIR__);
        $this->addAliases('esensi/assembler', ['build']);

        // Get Blade compiler
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        // Add @scripts($dependency1, $dependency2, $dependencyN)
        $blade->extend(function($value, $compiler)
        {
            $matcher = $compiler->createMatcher('scripts');

            return preg_replace($matcher, '$1<?php echo build_scripts$2; ?>', $value);
        });

        // Add @styles($dependency1, $dependency2, $dependencyN)
        $blade->extend(function($value, $compiler)
        {
            $matcher = $compiler->createMatcher('styles');

            return preg_replace($matcher, '$1<?php echo build_styles$2; ?>', $value);
        });
    }

}
