<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package      TomatoCart
 * @author       TomatoCart Dev Team
 * @copyright    Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html
 * @link         http://tomatocart.com
 * @since        Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Module Slideshow Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Slideshows extends TOC_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'slideshows';

    /**
     * Template Module Author Name
     *
     * @access private
     * @var string
     */
    var $author_name = 'TomatoCart';

    /**
     * Template Module Author Url
     *
     * @access private
     * @var string
     */
    var $author_url = 'http://www.tomatocart.com';

    /**
     * Template Module Version
     *
     * @access private
     * @var string
     */
    var $version = '1.0';

    /**
     * Template Module Version
     *
     * @access private
     * @var string
     */
    var $params = array(
        //MODULE_SLIDESHOW_IMAGE_GROUPS
        array('name' => 'MODULE_SLIDESHOW_IMAGE_GROUPS',
              'title' => 'Slideshow Image Group', 
              'type' => 'combobox',
              'mode' => 'remote',
              'value' => '',
              'description' => 'The image groups to choose',
              'module' => 'slide_images',
              'action' => 'get_image_groups'),
    
        //MODULE_SLIDESHOW_SLIDE_WIDTH
        array('name' => 'MODULE_SLIDESHOW_SLIDE_WIDTH',
              'title' => 'Slideshow Width', 
              'type' => 'numberfield',
              'value' => '960',
              'description' => 'Slideshow Wdith'),
        
        //MODULE_SLIDESHOW_SLIDE_HEIGHT
        array('name' => 'MODULE_SLIDESHOW_SLIDE_HEIGHT',
              'title' => 'Slideshow Height', 
              'type' => 'numberfield',
              'value' => '210',
              'description' => 'Slideshow Height'),
    
        //MODULE_SLIDESHOW_PLAY_INTERVAL
        array('name' => 'MODULE_SLIDESHOW_PLAY_INTERVAL',
              'title' => 'Slideshow Play Interval', 
              'type' => 'numberfield',
              'value' => '3000',
              'description' => 'Slideshow Play Interval'),
    
        //MODULE_SLIDESHOW_PAUSE_INTERVAL
        array('name' => 'MODULE_SLIDESHOW_PAUSE_INTERVAL',
              'title' => 'Slideshow Pause Interval', 
              'type' => 'numberfield',
              'value' => '2000',
              'description' => 'Slideshow Pause Interval'),
    
        //MODULE_SLIDESHOW_HOVER_PAUSE
        array('name' => 'MODULE_SLIDESHOW_HOVER_PAUSE',
              'title' => 'Slideshow Hover Pause', 
              'type' => 'combobox',
              'mode' => 'local',
              'description' => 'Stop slideshow when mouse hover',
              'values' => array(
                  array('id' => 'true', 'text' => 'True'), 
                  array('id' => 'false', 'text' => 'False'))));

    /**
     * Slideshow Module Constructor
     *
     * @access public
     * @param string
     */
    public function __construct($config)
    {
        parent::__construct();
        
        if (!empty($config) && is_string($config))
        {
            $this->config = json_decode($config, true);
        }

        $this->title = lang('slide_show_title');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of slideshow module
     */
    public function index()
    {
        //add jquery slide plugin
        $this->ci->template->add_javascript_file(base_url() . 'templates/default/web/javascript/slides.min.jquery.js');
        
        //Create a rand div id for slideshow
        $mid = 'slides_' . rand();
        //
        $this->ci->template->add_stylesheet_block('#' . $mid . ' .slides_container {display: block; width: ' . $this->config['MODULE_SLIDESHOW_SLIDE_WIDTH'] . 'px; height: ' . $this->config['MODULE_SLIDESHOW_SLIDE_HEIGHT'] . 'px}');
        
        //load products
        $slides = $this->slideshows->get_slides($this->config['MODULE_SLIDESHOW_IMAGE_GROUPS']);
        if (count($slides) > 0)
        {
            $data['mid'] = $mid;
            $data['play_interval'] = $this->config['MODULE_SLIDESHOW_PLAY_INTERVAL'];
            $data['pause_interval'] = $this->config['MODULE_SLIDESHOW_PAUSE_INTERVAL'];
            $data['hover_pause'] = $this->config['MODULE_SLIDESHOW_HOVER_PAUSE'];
            
            
            foreach($slides as $slide)
            {
                $data['images'][] = array(
                    'image_src' => $slide['image'],
                    'image_link' => $slide['image_url'],
                    'image_info' => $slide['description']);
            }
            
            //load view
            return $this->load_view('index.php', $data);
        } 
        
        return FALSE;
    }
}