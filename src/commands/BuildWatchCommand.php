<?php namespace Esensi\Build\Commands;

use \Esensi\Build\Commands\BuildCommand;

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
