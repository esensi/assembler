<?php

if ( ! function_exists('build_styles'))
{
    /**
     * Ouput the stylesheets for several dependencies.
     *
     * @param [string, [string], ...]
     * @return string
     */
    function build_styles()
    {
        return build_assets(func_get_args(), 'styles', 'css');
    }
}

if ( ! function_exists('build_scripts'))
{
    /**
     * Ouput the scripts for several dependencies.
     *
     * @param [string, [string], ...]
     * @return string
     */
    function build_scripts()
    {
        return build_assets(func_get_args(), 'scripts', 'js');
    }
}

if ( ! function_exists('build_assets'))
{
    /**
     * Ouput the assets for several dependencies.
     *
     * @param array $dependencies
     * @param string $key
     * @param string $extension
     * @return string
     */
    function build_assets($dependencies = [], $key, $extension)
    {
        // This function used to "fail" silently. There is default behavior that
        // we can expect with frontend assets, and we should log when we don't
        // see that behavior.
        // It's possible that this non-default behavior is desired, but this is
        // the exception! so we added some Log::warning messages in those cases

        $assets = [];

        // Get build configs
        $file = 'esensi/assembler::assembler';
        $builds_dir = public_path(config($file.'.directories.base', 'builds')) . config($file.'.directories.' . $key, $key);

        // Warn if $builds_dir does not exist.
        if ( config('app.debug') )
        {
            if ( ! file_exists($builds_dir) )
            {
                Log::warning("The assets builds directory '$builds_dir' doesn't exist or is inaccessible.");
            }
        }

        $builds_url = asset(config($file.'.directories.base', 'builds')) . config($file.'.directories.' . $key, $key);

        // Compile the manifest files
        $manifest_file = $builds_dir . '/rev-manifest.json';
        if ( ! file_exists($manifest_file) )
        {
            // Warn if $manifest_file doesn't exist.
            if ( config('app.debug') )
            {
                Log::warning("The manifest file '$manifest_file' doesn't exist or is inaccessible.");
            }
            return;
        }

        $manifest = json_decode(file_get_contents($manifest_file), true);

        // Babysit dependencies so we don't have duplications
        $dependencies = array_unique($dependencies);

        // Map dependencies to the latest revision
        foreach($dependencies as $dependency)
        {
            // Add extension to dependency
            $dependency .= '.' . $extension;

            // Only include dependencies that are built
            if (isset($manifest[$dependency]))
            {
                // Get the latest revision
                $revision = $manifest[$dependency];
                $revisionFilePath = $builds_dir . '/' . $revision;

                // Generate HTML for including the dependency
                switch($key)
                {
                    case 'styles':
                        // Warn if this file does not exist.
                        if ( config('app.debug') )
                        {
                            if ( ! file_exists($revisionFilePath) )
                            {
                                Log::warning("The style revision file '$revisionFilePath' doesn't exist or is inaccessible.");
                            }
                        }
                        $assets[] = '<link rel="stylesheet" href="' . $builds_url . '/' . $revision .'">';
                        break;

                    case 'scripts':
                        // Warn if this file does not exist.
                        if ( config('app.debug') )
                        {
                            if ( ! file_exists($revisionFilePath) )
                            {
                                Log::warning("The script revision file '$revisionFilePath' doesn't exist or is inaccessible.");
                            }
                        }
                        $assets[] = '<script type="text/javascript" src="' . $builds_url . '/' . $revision .'"></script>';
                        break;
                }
            }
            else
            {
                // Warn that a dependency is requested but not built.
                if ( config('app.debug') )
                {
                    Log::warning("The dependency '$dependency' is NOT built, skipping!");
                }
            }
        }

        // Print out each asset on it's own line
        return implode(PHP_EOL, $assets) . PHP_EOL;
    }
}
