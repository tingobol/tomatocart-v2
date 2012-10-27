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
                $cfg = $this->read_db_config();
//                 echo json_encode($cfg);
//                 $this->output->set_content_type('application/json')->set_output(json_encode($cfg));
                $this->load->view('database', array_merge($cfg,array('step' => 3,'create_db_success' => false,'error_msg' => false)));
                break;
            case 'webserver':
                $this->load->view('webserver', array_merge($this->read_cfg(), array('step' => 4)));
                break;
            case 'setting':
                //$this->write_cfg();
                $this->load->view('setting', array_merge($this->read_cfg(), array('step' => 5)));
                break;
            case 'finish':
                $this->write_cfg();
                $this->load->view('finish', array('step' => 6));
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
    
    private function read_cfg(){
         $conf_str = file_get_contents(realpath(dirname(__FILE__) . '/../../../').'/local/config/config.php');
//          preg_match("/config\['base_url'\] = '(.*)';/",$conf_str,$matches);
         preg_match("/config\['base_url'\] = '(.*)';[\s\S]+'store_name'\] = '(.*)';[\s\S]+'store_owner_name'\] = '(.*)';[\s\S]+'store_owner_email'\] = '(.*)';[\s\S]+'admin_user_name'\] = '(.*)';[\s\S]+'admin_pwd'\] = '(.*)';/",$conf_str,$matches);
//          var_dump($matches);
//          echo $conf_str;
//          exit();
         $cfg['HTTP_WWW_ADDRESS'] = $matches[1] == "'" ? '' : $matches[1];
         $cfg['store_name'] = $matches[2] == "'" ? '' : $matches[2];
         $cfg['store_owner_name'] = $matches[3] == "'" ? '' : $matches[3];
         $cfg['store_owner_email'] = $matches[4] == "'" ? '' : $matches[4];
         $cfg['admin_user_name'] = $matches[5] == "'" ? '' : $matches[5];
         $cfg['admin_pwd'] = $matches[6] == "'" ? '' : $matches[6];
         return $cfg;
    }
    
    private function write_cfg(){
        $www_address = $this->input->post('HTTP_WWW_ADDRESS');
        $store_name = $this->input->post('CFG_STORE_NAME');
        $store_owner_name = $this->input->post('CFG_STORE_OWNER_NAME');
        $store_owner_email = $this->input->post('CFG_STORE_OWNER_EMAIL_ADDRESS');
        $admin_user_name = $this->input->post('CFG_ADMINISTRATOR_USERNAME');
        $admin_pwd = $this->input->post('CFG_ADMINISTRATOR_PASSWORD');
        $admin_cfm_pwd = $this->input->post('CFG_CONFIRM_PASSWORD');
        
        $config_file = realpath(dirname(__FILE__) . '/../../../').'/local/config/config.php';
        var_dump($this->input->post());
        exit();
        if(is_writable($config_file)){
            $conf_str = file_get_contents($config_file);
            //$conf_str = preg_replace("/config\['base_url'\] = '.*';/", "config['base_url'] = '".$www_address."';" , $conf_str);
            
            $conf_str = preg_replace(array("/base_url'\] = .*;/"
//                             ,"/store_name'\] = .*;/"
//                             ,"/store_owner_name'\] = .*;/"
//                             ,"/store_owner_email'\] = .*;/"
//                             ,"/admin_user_name'\] = .*;/"
//                             ,"/admin_pwd'\] = .*;/"
                            ), array(
                                            "base_url'] = '$www_address';"
//                                             "store_name'] = '$store_name';",
//                                             "store_owner_name'] = '$store_owner_name';",
//                                             "store_owner_email'] = '$store_owner_email';",
//                                             "admin_user_name'] = '$admin_user_name';",
//                                             "admin_pwd'] = '$admin_pwd';"
                                            ), $conf_str);
            
            file_put_contents($config_file, $conf_str);
        }
    }
    
    private function read_db_config(){
        
        $conf_str = file_get_contents(realpath(dirname(__FILE__) . '/../../../').'/local/config/database.php');
        
        preg_match("/'hostname' => '(.*)',\s+'username' => '(.*)',\s+'password' => '(.*)',\s+'database' => '(.*)',\s+'dbdriver' => '(.*)',\s+'dbprefix' => '(.*)'/", $conf_str,$matches);
        
        $db_conf['DB_SERVER'] = $matches[1] == "'" ? '' : $matches[1];
        $db_conf['DB_SERVER_USERNAME'] = $matches[2] == "'" ? '' : $matches[2];
        $db_conf['DB_SERVER_PASSWORD'] = $matches[3] == "'" ? '' : $matches[3];
        $db_conf['DB_DATABASE'] = $matches[4] == "'" ? '' : $matches[4];
        $db_conf['DB_DATABASE_CLASS'] = $matches[5] == "'" ? '' : $matches[5];
        $db_conf['DB_TABLE_PREFIX'] = $matches[6] == "'" ? '' : $matches[6];;
        
        return $db_conf;
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
        
        if(is_writable($config_file)){
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
        }
        
        
        return $reback;
    }
}

/* End of file index.php */
/* Location: ./install/applications/controllers/index.php */