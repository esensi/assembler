<?php

namespace Esensi\Assembler\Listeners;

use Illuminate\Contracts\Console\Application;

/**
 * Handler for Artisan events.
 *
 * @package   Esensi\Assembler
 * @author    Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license   https://github.com/esensi/assembler/blob/master/LICENSE.txt MIT License
 * @link      http://www.emersonmedia.com
 */
class ArtisanEventListener
{
    /**
     * Add commands to Artisan.
     *
     * @return void
     */
    public function addCommands($app)
    {
        foreach(config('esensi/assembler::assembler.aliases', []) as $alias => $command)
        {
            $app->artisan->add(new $command());
        }
    }

}
