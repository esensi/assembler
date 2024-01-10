<?php

namespace Esensi\Assembler\Console\Commands;

use App\Console\Commands\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

/**
 * Command for building all the assets.
 *
 * @package   Esensi\Assembler
 * @author    Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license   https://github.com/esensi/assembler/blob/master/LICENSE.txt MIT License
 * @link      http://www.emersonmedia.com
 */
class BuildCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build the application\'s assets with Gulp JS tasks.';

    /**
     * Execute the console command.
     *
     * @see http://symfony.com/blog/new-in-symfony-2-2-process-component-enhancements
     * @return mixed
     */
    public function handle()
    {
        // Construct the Gulp JS command
        $gulp_path = Config::get('esensi/build::build.binary');

        if(!$gulp_path )
            $gulp_path = 'npx gulp';

        $gulp_command = $gulp_path . ' build';

        $task = $this->argument('task');
        if($task != null)
        {
            $gulp_command .= ':' . $task;

            // Show subtask process
            switch($task)
            {
                case 'watch':
                    $this->info('Builder is now watching for asset changes...');
                    break;

                case 'clean':
                    $this->info('Builder is now cleaning old asset builds...');
                    break;

                case 'lint':
                    $this->info('Builder is now linting assets...');
                    break;

                case 'styles':
                    $this->info('Builder is now building stylesheets...');
                    break;

                case 'scripts':
                    $this->info('Builder is now building scripts...');
                    break;

                case 'images':
                    $this->info('Builder is now building images...');
                    break;

                case 'fonts':
                    $this->info('Builder is now building fonts...');
                    break;
            }
        }

        // Compare environment to configured environments for production
        $is_production = in_array(App::environment(), Config::get('esensi/build::build.environments', ['production']));

        // Run in production mode
        if($is_production || $this->option('production') === true)
            $gulp_command .= ' --production';

        // Run gulp process
        $process = new Process(['bash', '-c', $gulp_command]);
        $process->start();

        // Keep the processes running without timeout
        do {
            // Show incremental output
            echo $process->getIncrementalOutput();

            // Show incremental error output with highlighting
            $output = $process->getIncrementalErrorOutput();
            if(!empty($output))
                $this->error(rtrim($output, "\n"));

        } while ($process->isRunning());

        if($process->isSuccessful()) {
            $this->info('Builder has finished running "' . $gulp_command . '".');
        }
        // Show error when it errors out
        else {
            $this->error('Builder encountered an error while running "' . $gulp_command . '".');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['task', InputArgument::OPTIONAL, 'A task to run.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['production', 'p', InputOption::VALUE_NONE, 'Optimizes the build for production'],
        ];
    }

}
