<?php namespace Esensi\Assembler\Commands;

use Esensi\Assembler\Commands\BuildCommand;

/**
 * Command for cleaning the already built assets.
 *
 * @package Esensi\Assembler
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/assembler/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class BuildCleanCommand extends BuildCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans the application\'s old static asset builds.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('build', ['task' => 'clean']);
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
