<?php
// app/Helpers/CustomUrlHelper.php

if (!function_exists('assets')) {
    /**
     * Generates a URL for an image in the "images" directory.
     *
     * @param string $path The relative path to the image within the "images" folder.
     * @return string The full URL to the image.
     */
    function assets($path = '')
    {
        return base_url('public/assets/' . $path);
    }
}


if ( ! function_exists('images'))
{
	function images($path = '')
	{
            return base_url('public/assets/images/' . $path);
	}
}

if ( ! function_exists('css'))
{
	function css($path = '')
	{
            return base_url('public/assets/css/' . $path);
	}
}

if ( ! function_exists('js'))
{
	function js($path = '')
	{
            return base_url('public/assets/js/' . $path);
	}
}
if ( ! function_exists('jquery'))
{
	function jquery($path = '')
	{
            return base_url('public/assets/js/' . $path);
	}
}