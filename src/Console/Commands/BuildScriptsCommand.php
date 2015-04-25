<?php namespace Esensi\Assembler\Console\Commands;

use App\Console\Commands\BuildCommand as Command;

/**
 * Command for building the script assets.
 *
 * @package Esensi\Assembler
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/assembler/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class BuildScriptsCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:scripts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds the application\'s script assets.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('build', ['task' => 'scripts']);
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

}
