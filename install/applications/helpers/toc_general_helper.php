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

/* End of file general_helper.php */
/* Location: ./install/helpers/toc_general_helper.php */