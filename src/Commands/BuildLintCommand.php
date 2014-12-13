<?php namespace Esensi\Build\Commands;

use Esensi\Build\Commands\BuildCommand;

/**
 * Command for linting the assets.
 *
 * @package Esensi\Build
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/user/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class BuildLintCommand extends BuildCommand {

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
