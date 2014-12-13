<?php namespace Esensi\Build\Commands;

use Esensi\Build\Commands\BuildCommand;

/**
 * Command for building the image assets.
 *
 * @package Esensi\Build
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/user/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class BuildImagesCommand extends BuildCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds the application\'s image assets.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('build', ['task' => 'images']);
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
