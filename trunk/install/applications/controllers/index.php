<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * Index Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-index-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Index extends TOC_Controller
{

    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Default Function
     *
     * @access public
     * @param string
     */
    public function index($view = null)
    {
        //load header
        $this->load->view('modules/header');
        
        //load view
        switch ($view) {
            case 'check':
                $this->load->view('check', array('step' => 2));
                break;
            case 'database':
                $this->load->view('database', array('step' => 3,'create_db_success' => false,'error_msg' => false));
                break;
            case 'webserver':
                $this->load->view('webserver', array('step' => 4));
                break;
            case 'setting':
                $this->load->view('setting', array('step' => 5));
                break;
            case 'finish':
                $this->load->view('setting', array('step' => 6));
                break;
            case 'db_create':
                $reback = $this->create_db();
                $reback['step'] = 3; 
                $this->load->view('database', $reback);
                break;                
            case 'licence':
            default:
                //Get GNU lisence
                $licence = file_get_contents('applications/language/' . lang_code() . '/license.txt');
                
                //reder the view
                $this->load->view('licence', array('step' => 1, 'licence' => $licence));
        }
        
        //load footer
        $this->load->view('modules/footer');
    }
    
    private function write_cfg(){
        
    }
    
    private function create_db(){
        $hostname = $this->input->post('DB_SERVER');
        $username = $this->input->post('DB_SERVER_USERNAME');
        $password = $this->input->post('DB_SERVER_PASSWORD');
        $database = $this->input->post('DB_DATABASE');
        $dbdriver = $this->input->post('DB_DATABASE_CLASS');
        $dbprefix = $this->input->post('DB_TABLE_PREFIX');
        $rebuild = $this->input->post('rebuild');
        $reback = array();
        $reback = array_merge($reback,$this->input->post());
        $reback['create_db_success'] = false;
        
        try {
            $con = mysql_connect($hostname,$username,$password);
            if(!$con){
                $reback['error_msg'] = 'error_cant_connect';
    //             exit();
                return $reback;
            }
        } catch (Exception $e) {
        }
        
        if(mysql_select_db($database,$con)){
            if(empty($rebuild)){
                $reback['warn_msg'] = 'db_exist';
                return $reback;
            }else if($rebuild=='yes'){
                mysql_query('DROP DATABASE '.$database);
                mysql_query('CREATE DATABASE '.$database);                
            }else{
                $reback['error_msg'] = 'error_duplicate_db';
                return $reback;
            }
        }else{
            mysql_query('CREATE DATABASE '.$database);
        }
        
        $reback['create_db_success'] = true;
        //$reback = array_merge($reback,$this->input->post());
        
        mysql_close($con);
        $config_file = realpath(dirname(__FILE__) . '/../../../').'/local/config/database.php';
        
        $conf_str = file_get_contents($config_file);
        
        $conf_str = preg_replace(array("/'hostname' => '.+?'/",
                        "/'username' => '.*?'/",
                        "/'password' => '.*?'/",
                        "/'database' => '.*?'/",
                        "/'dbdriver' => '.*?'/",
                        "/'dbprefix' => '.*?'/"), 
                        array("'hostname' => '$hostname'",
                                        "'username' => '$username'",
                                        "'password' => '$password'",
                                        "'database' => '$database'",
                                        "'dbdriver' => '$dbdriver'",
                                        "'dbprefix' => '$dbprefix'",
                                        ), $conf_str);
        file_put_contents($config_file, $conf_str);
        
        
        
        return $reback;
    }
}

/* End of file index.php */
/* Location: ./install/applications/controllers/index.php */