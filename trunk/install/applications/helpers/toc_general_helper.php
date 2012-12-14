<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * TomatoCart General Helpers
 *
 * @package		TomatoCart
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

/**
 * Gets a translation
 *
 * @access public
 * @param $key The key to the translation
 * @return string translation
 */
if( ! function_exists('lang'))
{
    function lang($key)
    {
        $CI =& get_instance();

        return $CI->lang->line($key);
    }
}

/**
 * Gets all languages
 *
 * @access public
 * @return array languages
 */
if( ! function_exists('lang_code'))
{
    function lang_code()
    {
        $CI =& get_instance();

        return $CI->lang->get_code();
    }
}

/**
 * Gets all languages
 *
 * @access public
 * @return array languages
 */
if( ! function_exists('get_languages'))
{
    function get_languages()
    {
        $CI =& get_instance();

        return $CI->lang->get_languages();
    }
}

/**
 * Display language flag
 *
 * @access public
 * @param $code language code
 * @return array languages
 */
if( ! function_exists('get_language_flag'))
{
    function get_language_flag($code)
    {
        $flag = strtolower(substr($code, 3));

        return store_url() . '/images/worldflags/' . $flag . '.png';
    }
}

/**
 * Get store url
 *
 * @access public
 * @return string
 */
if( ! function_exists('store_url'))
{
    function store_url()
    {
        return trim(base_url(), 'install/');
    }
}

/**
 * Encrypt password
 *
 * @access public
 * @param $plian
 * @return string
 */
if( ! function_exists('encrypt_string'))
{
    function encrypt_string($plain)
    {
        $password = '';

        for ($i=0; $i<10; $i++)
        {
            $password .= mt_rand();
        }

        $salt = substr(md5($password), 0, 2);

        $password = md5($salt . $plain) . ':' . $salt;

        return $password;
    }
}

/**
 * Traverse recursively directory and return files
 * 
 * @access public
 * @param $path
 * @return array
 */
function traverse_hierarchy($path)
{
    $return_array = array();

    $dir = opendir($path);
    while(($file = readdir($dir)) !== false)
    {
        if($file[0] == '.') continue;

        $fullpath = $path . '/' . $file;
        if(is_dir($fullpath))
        $return_array = array_merge($return_array, traverse_hierarchy($fullpath));
        else // your if goes here: if(substr($file, -3) == "jpg") or something like that
        $return_array[] = $fullpath;
    }

    return $return_array;
}

/**
 * Copy complete directroy
 * 
 * @access public
 * @param $source
 * @param $target
 * @return void
 */
function toc_copy($source, $target) {
    if (is_dir($source)) {
        $src_dir = dir($source);

        while ( false !== ($file = $src_dir->read()) ) {
            if ($file == '.' || $file == '..' || $file == '.svn') {
                continue;
            }

            $src_file = $source . '/' . $file;
            if (is_dir($src_file)) {
                toc_copy($src_file, $target . '/' . $file );
                continue;
            }
            copy( $src_file, $target . '/' . $file );
        }

        $src_dir->close();
    }else {
        copy($source, $target);
    }
}

/* End of file general_helper.php */
/* Location: ./install/helpers/toc_general_helper.php */