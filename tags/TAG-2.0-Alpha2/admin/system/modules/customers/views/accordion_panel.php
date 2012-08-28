<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource accordion_panel.php
 */
?>

Ext.define('Toc.customers.AccordionPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.region = 'east';
    config.border = false;
    config.split = true;
    config.minWidth = 240;
    config.maxWidth = 350;
    config.width = 300;
    config.layout = 'accordion';
    
    config.grdAddressBook = Ext.create('Toc.customers.AddressBookGrid');
    
    config.items = [config.grdAddressBook];
    
    this.callParent([config]);
  }
});


/* End of file accordion_panel.php */
/* Location: ./system/modules/customers/views/accordion_panel.php */