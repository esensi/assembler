<?php

namespace Esensi\Assembler\Console\Commands;

use App\Console\Commands\BuildCommand as Command;

/**
 * Command for watching for asset changes and then
 * automatically rebuilding the changed assets.
 *
 * @package   Esensi\Assembler
 * @author    Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license   https://github.com/esensi/assembler/blob/master/LICENSE.txt MIT License
 * @link      http://www.emersonmedia.com
 */
class BuildWatchCommand extends Command
{
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
    public function handle()
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
