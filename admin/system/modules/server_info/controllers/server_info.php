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
 * @filesource ./system/modules/server_info/controllers/server_info.php
 */

class Server_Info extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function show()
  {
    $system_info = $this->get_system_info();
    
    $this->load->view('main');
    $this->load->view('server_info_dialog', $system_info);
  }
  
  public function get_system_info()
  {
    @list($system, $host, $kernel) = preg_split('/[\s,]+/', @exec('uname -a'), 5);
    
    $data = array('server_host' => $host . ' (' . gethostbyname($host) . ')',
                  'project_version' => $this->config->item('project_version'), 
                  'server_operating_system' => $system . ' ' . $kernel, 
                  'server_date' => date('Y-m-d H:i:s'), 
                  'database_host' => $this->config->item('hostname') . ' (' . gethostbyname($this->config->item('hostname')) . ')', 
                  'database_version' => 'MySQL ' . (function_exists('mysql_get_server_info') ? mysql_get_server_info() : ''), 
                  'http_server' => $this->input->server('SERVER_SOFTWARE'), 
                  'php_version' => 'PHP: ' . PHP_VERSION . ' / Zend: ' . (function_exists('zend_version') ? zend_version() : ''));
    
    return array('success' => true, 'data' => $data);
  }
}


/* End of file server_info.php */
/* Location: ./system/modules/server_info/controllers/server_info.php */
