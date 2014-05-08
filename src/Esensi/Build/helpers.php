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
        $assets = [];

        $builds_dir = public_path(Config::get('esensi::build.directories.base', 'builds')) . '/' . Config::get('esensi::build.directories.' . $key, $key);
        $builds_url = asset(Config::get('esensi::build.directories.base', 'builds')) . '/' . Config::get('esensi::build.directories.' . $key, $key);
        $manifest = json_decode(file_get_contents($builds_dir . '/rev-manifest.json'), true);

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
                
                // Generate HTML for including the dependency
                switch($key)
                {
                    case 'styles':
                        $assets[] = '<link rel="stylesheet" href="' . $builds_url . '/' . $revision .'">';
                        break;

                    case 'scripts':
                        $assets[] = '<script type="text/javascript" src="' . $builds_url . '/' . $revision .'"></script>';
                        break;
                }
            }
        }

        // Print out each asset on it's own line
        return implode(PHP_EOL, $assets) . PHP_EOL;
    }
}