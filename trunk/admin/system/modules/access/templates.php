<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
  $Id: templates.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class TOC_Access_Templates extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'templates';
      $this->_group = 'templates';
      $this->_icon = 'file.png';
      $this->_sort_order = 200;
      $this->_title = 'Templates';
    }
  }
