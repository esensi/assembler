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
        // TODO: This function "fails" silently. There is default behavior that
        // we can expect with frontend assets, and we should log when we don't
        // see that behavior. (It's possible that this non-default behavior is
        // desired, but this is the exception!)

        $assets = [];

        // Get build configs
        $file = 'esensi/assembler::assembler';
        $builds_dir = public_path(config($file.'.directories.base', 'builds')) . config($file.'.directories.' . $key, $key);
        // TODO: Log::warn if $builds_dir does not exist.
        $builds_url = asset(config($file.'.directories.base', 'builds')) . config($file.'.directories.' . $key, $key);

        // Compile the manifest files
        $manifest_file = $builds_dir . '/rev-manifest.json';
        if( ! file_exists($manifest_file) ) return;
        // TODO: Log::warn if $manifest_file doesn't exist.
        $manifest = json_decode(file_get_contents($manifest_file), true);

        // Babysit dependencies so we don't have duplications
        $dependencies = array_unique($dependencies);

        // Map dependencies to the latest revision
        foreach($dependencies as $dependency)
        {
            // Add extension to dependency
            $dependency .= '.' . $extension;

            // Only include dependencies that are built
            // TODO: Log::warn if a dependency is requested but not built.
            if (isset($manifest[$dependency]))
            {
                // Get the latest revision
                $revision = $manifest[$dependency];

                // Generate HTML for including the dependency
                switch($key)
                {
                    case 'styles':
                        // TODO: Log::warn if this file does not exist.
                        $assets[] = '<link rel="stylesheet" href="' . $builds_url . '/' . $revision .'">';
                        break;

                    case 'scripts':
                        /// TODO: Log::warn if this file does not exist.
                        $assets[] = '<script type="text/javascript" src="' . $builds_url . '/' . $revision .'"></script>';
                        break;
                }
            }
        }

        // Print out each asset on it's own line
        return implode(PHP_EOL, $assets) . PHP_EOL;
    }
}
