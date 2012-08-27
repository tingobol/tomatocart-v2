<?php

class TOC_Service {

  protected $ci = null;
  
  protected $code;
  protected $title;
  protected $description;
  protected $uninstallable = true;
  protected $depends;
  protected $precedes;
  protected $params;

  // class constructor
  public function __construct() {
    // Set the super object to a local variable for use later
    $this->ci =& get_instance();

    $this->ci->lang->db_load('modules/services/' . $this->code);
    
    $this->title = lang('services_' . $this->code . '_title');
    $this->description = lang('services_' . $this->code . '_description');
  }
  
  /**
   * 
   */
  public function get_title() {
    return $this->title;
  }
  
  /**
   * 
   */
  public function get_description() {
    return $this->description;
  }
  
  /**
   * 
   */
  public function install() {
  
  }
  
  public function remove() {
  
  }
  
  public function keys() {
  }
  
  public function run() {
  }
}

