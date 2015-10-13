<?php

namespace Esensi\Assembler\Console\Commands;

use App\Console\Commands\BuildCommand as Command;

/**
 * Command for linting the assets.
 *
 * @package   Esensi\Assembler
 * @author    Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license   https://github.com/esensi/assembler/blob/master/LICENSE.txt MIT License
 * @link      http://www.emersonmedia.com
 */
class BuildLintCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:lint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lint the application\'s asset for errors in formatting.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('build', ['task' => 'lint']);
    }

    /**
     * Get the console command arguments.
     *
     * This is stubbed to overwrite parent class.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * This is stubbed to overwrite parent class.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
