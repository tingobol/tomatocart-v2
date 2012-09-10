<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Logo Upload Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Logo_Upload extends TOC_Controller
{
    /**
     * Path to the logo
     *
     * @var string
     */
    private $logo_path;
    
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->logo_path = ROOTPATH . 'images/';
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Save the logo
     *
     * @access public
     * @return string
     */
    public function save_logo()
    {
        if ($this->upload('logo_image'))
        {
            $image = $this->get_template_logo(config('DEFAULT_TEMPLATE'));
            
            if ($image != NULL)
            {
                list($width, $height) = getimagesize(ROOTPATH . 'images/' . $image);
            
                $response = array('success' => TRUE, 'image' => IMGHTTPPATH . $image, 'height' => $height, 'width' => $width, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed')); 
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed')); 
        }
        
        $this->output->set_header("Content-Type: text/html")->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------

    /**
     * Get the logo
     *
     * @access public
     * @return string
     */
    public function get_logo()
    {
        $image = $this->get_template_logo(config('DEFAULT_TEMPLATE'));
        
        if ($image != NULL)
        {
            list($width, $height) = getimagesize(ROOTPATH . 'images/' . $image);
            
            $image = '<img src="' . IMGHTTPPATH . $image . '" width="' . $width . '" height="' . $height . '" style="padding: 10px" />';
      
            $response = array('success' => TRUE, 'image' => $image);
        }
        else
        {
            $response = array('success' => FALSE);   
        }
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the template logo
     *
     * @access private
     * @param $template
     * @return mixed
     */
    private function get_template_logo($template)
    {
        $this->load->helper('directory');
    
        $logo = 'logo_' . $template . '_thumb';
        
        $images_map = directory_map($this->logo_path);
        
        if (!empty($images_map))
        {
            foreach($images_map as $image)
            {
                if (!is_array($image))
                {
                    $filename = explode(".", $image);
                    
                    if ($filename[0] == $logo)
                    {
                        return $logo . '.' . $filename[1];
                    }
                }
            }
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Upload the logo
     *
     * @access private
     * @param $field
     * @return boolean
     */
    private function upload($field)
    {
        $this->load->library(array('image_lib', 'upload'));
        $this->load->helper('directory');
        
        $this->upload->initialize(array('upload_path' => $this->logo_path, 'allowed_types' => 'gif|jpg|png'));
        
        if ($this->upload->do_upload($field))
        {
            $data = $this->upload->data();
            
            $logo_uploaded_path = $data['full_path'];
            
            $original = $this->logo_path . 'logo_originals.' . $data['image_type'];
            
            $templates_map = directory_map(ROOTPATH . 'templates', 2);
        
            foreach($templates_map as $template => $files)
            {
                if (file_exists(ROOTPATH . 'templates/' . $template . '/template.xml'))
                {
                    $this->delete_logo($template);
                    
                    $xml_info = simplexml_load_file(ROOTPATH . 'templates/' . $template . '/template.xml');
                    
                    $logo_info = $xml_info->Logo[0]->attributes();
                    
                    $logo_height = $logo_info['height'];
                    $logo_width = $logo_info['width'];
                    
                    $dest_image = $this->logo_path . 'logo_' . $template . '.' . $data['image_type'];
                    
                    $this->image_lib->initialize(array('image_library' => 'gd2', 
                                                       'source_image' => $logo_uploaded_path,
                                                       'new_image' => $dest_image,
                                                       'create_thumb' => TRUE, 
                                                       'width' => $logo_width, 
                                                       'height' => $logo_height));
                    
                    $this->image_lib->resize();
                }
            }
            
            @unlink($data['full_path']);
            
            return TRUE;
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Delete the logo
     *
     * @access private
     * @param $template
     * @return boolean
     */
    private function delete_logo($template)
    {
        $this->load->helper('directory');
        
        $logo = 'logo_' . $template . '_thumb';
        
        $images_map = directory_map($this->logo_path);
        
        if (!empty($images_map))
        {
            foreach($images_map as $image)
            {
                if (!is_array($image))
                {
                    $filename = explode(".", $image);
                    
                    if ($filename[0] == $logo)
                    {
                        if(unlink($this->logo_path . $image))
                        {
                            return TRUE;
                        }
                    }
                }
            }
        }
        
        return FALSE;
    }
}

/* End of file logo_upload.php */
/* Location: ./system/controllers/logo_upload.php */