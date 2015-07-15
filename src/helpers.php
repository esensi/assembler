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
        // Get the builds dir
        $file = 'esensi/assembler::assembler';
        $key = 'styles';
        $builds_dir = public_path(config($file.'.directories.base', 'builds') . '/' . config($file.'.directories.' . $key, $key));

        // Compile the manifest files
        $manifest_file = $builds_dir . '/manifest.json';
        if( ! file_exists($manifest_file) ) return;
        $manifest = (array) json_decode(file_get_contents($manifest_file), true);

        // Build the assets from the manifest
        return build_assets(func_get_args(), $key, 'css', $manifest);
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
        // Get the dependencies
        $dependencies = func_get_args();

        // Get the builds dir
        $file = 'esensi/assembler::assembler';
        $key = 'scripts';
        $builds_dir = public_path(config($file.'.directories.base', 'builds') . '/' . config($file.'.directories.' . $key, $key));

        // Compile the manifest files
        $manifest = [];
        foreach($dependencies as $dependency)
        {
            $manifest_file = $builds_dir . '/' . $dependency . '.json';
            if( ! file_exists($manifest_file) ) continue;
            $manifest = array_merge($manifest, (array) json_decode(file_get_contents($manifest_file), true));
        }

        return build_assets($dependencies, $key, 'js', $manifest);
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
     * @param array $manifest
     * @return string
     */
    function build_assets($dependencies = [], $key, $extension, $manifest)
    {
        // This function used to "fail" silently. There is default behavior that
        // we can expect with frontend assets, and we should log when we don't
        // see that behavior.
        // It's possible that this non-default behavior is desired, but this is
        // the exception! so we added some Log::warning messages in those cases

        $assets = [];

        // Get build configs
        $file = 'esensi/assembler::assembler';
        $builds_dir = public_path(config($file.'.directories.base', 'builds') . '/' . config($file.'.directories.' . $key, $key));

        // Warn if $builds_dir does not exist.
        if ( config('app.debug') )
        {
            if ( ! file_exists($builds_dir) )
            {
                Log::warning("The assets builds directory '$builds_dir' doesn't exist or is inaccessible.");
            }
        }

        $builds_url = asset(config($file.'.directories.base', 'builds')) . config($file.'.directories.' . $key, $key);

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
