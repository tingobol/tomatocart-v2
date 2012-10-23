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
 * Gets a configuration option value
 *
 * @access public
 * @param $key The configuration name
 * @return string
 */
if( ! function_exists('config'))
{
    function config($key)
    {
        $CI =& get_instance();
        $line = $CI->configuration->line($key);

        return $line;
    }
}

/**
 * Encrypt password
 *
 * @access public
 * @param $plain the password
 * @return string encrypted password
 */
if( ! function_exists('encrypt_password'))
{
    function encrypt_password($plain)
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
 * Parse and output a user submited value
 *
 * @access public
 * @param string $string The string to parse and output
 * @param array $translate An array containing the characters to parse
 * @return string
 */
if( ! function_exists('output_string'))
{
    function output_string($string, $translate = NULL)
    {
        if (empty($translate))
        {
            $translate = array('"' => '&quot;');
        }

        return strtr(trim($string), $translate);
    }
}


/**
 * Strictly parse and output a user submited value
 *
 * @param string $string The string to strictly parse and output
 * @access public
 */
if( ! function_exists('output_string_protected'))
{
    function output_string_protected($string)
    {
        return htmlspecialchars(trim($string));
    }
}

/**
 * Display Image
 *
 * @access public
 * @param string $image the image to show
 * @return string
 */
if( ! function_exists('image_url'))
{
    function image_url($image)
    {
        return base_url() . 'images/' . $image;
    }
}

/**
 * Display Product Image
 *
 * @access public
 * @param string $image the image to show
 * @param string $group image group
 * @return string
 */
if( ! function_exists('product_image_url'))
{
    function product_image_url($image, $group = 'thumbnails')
    {
        return base_url() . 'images/products/' . $group . '/' . $image;
    }
}

/**
 * Validates the format of an email address
 *
 * @param string $email_address The email address to validate
 * @access public
 */
if( ! function_exists('address_format'))
{
    function address_format($address, $new_line = "\n")
    {
        if (empty($address['address_format']))
        {
            $address['address_format'] = ":name\n:street_address\n:postcode :city\n:country";
        }

        $find_array = array('/\:name\b/',
                          '/\:street_address\b/',
                          '/\:suburb\b/',
                          '/\:city\b/',
                          '/\:postcode\b/',
                          '/\:state\b/',
                          '/\:zone_code\b/',
                          '/\:country\b/');

        if (isset($address['name']))
        {
            $name = $address['name'];
        }
        else if (isset($address['firstname']) && isset($address['lastname']))
        {
            $name = $address['firstname'] . ' ' . $address['lastname'];
        }


        $replace_array = array($name,
        $address['street_address'],
        $address['suburb'],
        $address['city'],
        $address['postcode'],
        $address['state'],
        $address['zone_code'],
        $address['countries_name']);

        $formated = preg_replace($find_array, $replace_array, $address['address_format']);

        if ( (config('ACCOUNT_COMPANY') > -1) && !empty($address['company']) )
        {
            $company = $address['company'];

            $formated = $company . $new_line . $formated;
        }

        if ($new_line != "\n")
        {
            $formated = str_replace("\n", $new_line, $formated);
        }

        return $formated;
    }

    /**
     * Execute service module
     *
     * @access public
     * @param $module
     * @return void
     */
    if( ! function_exists('run_service'))
    {
        function run_service($module)
        {
            //get instance
            $ci = get_instance();
            
            //run service
            $ci->service->run($module);
        }
    }

}

/* End of file general_helper.php */
/* Location: ./system/tomatocart/helpers/general_helper.php */