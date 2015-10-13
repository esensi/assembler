<?php

namespace Esensi\Assembler\Providers;

use Esensi\Loaders\Providers\ServiceProvider;

/**
 * Service provider for Esensi\Assembler components package.
 *
 * @package   Esensi\Assembler
 * @author    Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license   https://github.com/esensi/assembler/blob/master/LICENSE.txt MIT License
 * @link      http://www.emersonmedia.com
 */
class AssemblerServiceProvider extends ServiceProvider
{
    /**
     * The namespace of the loaded config files.
     *
     * @var string
     */
    protected $namespace = 'esensi/assembler';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Load config files
        $this->loadConfigsFrom(__DIR__ . '/../../config', $this->namespace);
        $this->loadAliasesFrom(config_path($this->namespace), $this->namespace);

        // Setup core Blade extensions
        $this->addDirectives();
    }

    /**
     * Register any application services.
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
    public function addDirectives()
    {
        // Get Blade compiler
        $blade = $this->app['blade.compiler'];

        // Add @scripts($dependency1, $dependency2, $dependencyN)
        $blade->directive('scripts', function($expression)
        {
            return '<?php echo build_scripts(' . $expression . '); ?>';
        });

        // Add @styles($dependency1, $dependency2, $dependencyN)
        $blade->directive('styles', function($expression)
        {
            return '<?php echo build_styles(' . $expression . '); ?>';
        });
    }
}
