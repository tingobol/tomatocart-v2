<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manufacturers extends TOC_Module {
    /**module code*/
    var $code = 'manufacturers';
    
    var $author_name = 'TomatoCart';
    
    var $author_url = 'http://www.tomatocart.com';
    
    var $version = '1.0';
  
    /**
     * Module Constructor
     *
     * @access public
     * @param string
     */
    public function __construct($config)
    {
        parent::__construct();
        
        if (!empty($config) && is_string($config))
        {
            $this->config = json_decode($config, TRUE);
        }
  
        $this->title = lang('box_manufacturers_heading');
    }
  
    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of the module
     */
    public function index()
    {
        //load model
        $this->ci->load->model('manufacturers_model');
        
        //setup view data
        $manufacturers = $this->ci->manufacturers_model->get_manufacturers();
        
        $data['manufacturers'] = array();
        if (!empty($manufacturers))
        {
            foreach($manufacturers as $manufacturer)
            {
                $data['manufacturers'][] = array('image_url' => image_url('manufacturers/' .  $manufacturer['manufacturers_image']), 'link_href' => site_url('search/manufacturers/' . $manufacturer['manufacturers_id']));
            }
        }
        
        //load view
        return $this->load_view('index.php', $data);
    }
}