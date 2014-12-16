<?php namespace Esensi\Assembler\Commands;

use Esensi\Assembler\Commands\BuildCommand;

/**
 * Command for watching for asset changes and then
 * automatically rebuilding the changed assets.
 *
 * @package Esensi\Assembler
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/assembler/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class BuildWatchCommand extends BuildCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watches for asset changes to build.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('build', ['task' => 'watch']);
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
