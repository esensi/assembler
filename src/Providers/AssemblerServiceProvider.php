<?php namespace Esensi\Assembler\Providers;

use Esensi\Core\Providers\PackageServiceProvider;
use Illuminate\Console\Application;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Symfony\Component\Finder\Finder;

/**
 * Service provider for Esensi\Assembler components package.
 *
 * @package Esensi\Assembler
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/assembler/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class AssemblerServiceProvider extends ServiceProvider {

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
            foreach(config('esensi/assembler::build.aliases', []) as $alias => $command)
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
        require_once( __DIR__ . '/../helpers.php');

        $namespace = 'esensi/assembler';
        $path = config_path($namespace);

        // Get the configs that need to be published
        $configs = [];
        $files = Finder::create()->files()->name('*.php')->in(__DIR__ . '/../config');
        foreach($files as $file)
        {
            $configs[$file->getRealPath()] = $path . '/' . basename($file->getRealPath());
        }

        // Publish the configs to the app namespace
        $this->publishes($configs, 'config');

        // Wrapped in a try catch because Finder squawks when there is no directory
        try{

            // Load the namespaced config files
            $files = Finder::create()->files()->name('*.php')->in($path);
            foreach($files as $file)
            {
                $key = $namespace . '::' . basename($file->getRealPath(), '.php');
                $this->app['config']->set($key, require $file->getRealPath());
            }

        } catch( InvalidArgumentException $e){}

        // Get Blade compiler
        $blade = $this->app['blade.compiler'];

        // Add @scripts($dependency1, $dependency2, $dependencyN)
        $blade->extend(function($value, $compiler)
        {
            $matcher = $compiler->createMatcher('scripts');

            return preg_replace($matcher, '$1<?php echo build_scripts($2); ?>', $value);
        });

        // Add @styles($dependency1, $dependency2, $dependencyN)
        $blade->extend(function($value, $compiler)
        {
            $matcher = $compiler->createMatcher('styles');

            return preg_replace($matcher, '$1<?php echo build_styles($2); ?>', $value);
        });
    }

}
