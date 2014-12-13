<?php namespace Esensi\Build\Commands;

use Esensi\Build\Commands\BuildCommand;

/**
 * Command for building the stylesheet assets.
 *
 * @package Esensi\Build
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/user/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class BuildStylesCommand extends BuildCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:styles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds the application\'s stylesheet assets.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('build', ['task' => 'styles']);
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
