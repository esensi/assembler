<?php namespace Esensi\Assembler\Providers;

use Esensi\Core\Traits\AliasLoaderTrait;
use Esensi\Core\Traits\ConfigLoaderTrait;
use Illuminate\Support\ServiceProvider;

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
     * Load namespace aliases from the config files.
     *
     * @see Esensi\Core\Traits\AliasLoaderTrait
     */
    use AliasLoaderTrait;

    /**
     * Make use of backported namespaced configs loader.
     *
     * @see Esensi\Core\Traits\ConfigLoaderTrait
     */
    use ConfigLoaderTrait;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $namespace = 'esensi/assembler';

        // Load config files
        $this->loadConfigsFrom(__DIR__ . '/../../config', $namespace);
        $this->loadAliasesFrom(config_path($namespace), $namespace);

        // Setup core Blade extensions
        $this->extendBlade();
    }

    /**
     * Registers the resource dependencies.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Extend the Blade compiler with @styles and @scripts.
     *
     * @return void
     */
    public function extendBlade()
    {
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
