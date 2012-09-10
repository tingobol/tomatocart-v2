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
 * Email library
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-library
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
Class TOC_Email
{
    /**
     * Holds the most recent mailer error message.
     *
     * @access private
     * @var array
     */
    private $_errors;
    
    /**
     * 
     *
     * @access private
     * @var string
     */
    private $_mailer;
    
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct($config = array())
    {
        require_once(THIRDPARTY . 'phpmailer/class.phpmailer.php');
        
        // the true param means it will throw exceptions on errors, which we could catch
        $this->_mailer = new PHPMailer(true);
        
        if (count($config) > 0)
        {
            $this->initialize($config);
        }
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Initialize preferences
     *
     * @param array
     * @return  void
     */
    public function initialize($config = array())
    {
        $this->_mailer->CharSet = 'utf-8';
        $this->_mailer->From = STORE_OWNER_EMAIL_ADDRESS;
        $this->_mailer->FromName = STORE_OWNER;
         
        foreach ($config as $key => $val)
        {
            if (isset($this->_mailer->$key))
            {
                $this->_mailer->$key = $val;
            }
        }
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Automatically call the methods of the phpmailer class
     *
     * @access public
     * 
     * @param array
     */
    public function __call($method, $args) 
    {
        if (is_callable(array($this->_mailer, $method)))
        {
            call_user_func_array(array($this->_mailer, $method), $args);
        }
    }
    
// ------------------------------------------------------------------------

    /**
     * Send the email
     *
     * @access public
     * @return boolean
     */
    public function send()
    {
        if (SEND_EMAILS == 1)
        {
            if (EMAIL_TRANSPORT == 'smtp')
            {
                //Set the information for connecting the smtp server
                $this->_mailer->IsSMTP();
                $this->_mailer->SMTPAuth = TRUE;
                
                //Whether need the ssl connection
                if (defined('EMAIL_SSL') && (EMAIL_SSL == '1')) {
                    $this->_mailer->SMTPSecure = "ssl";
                }
                
                $this->_mailer->Host = SMTP_HOST;
                $this->_mailer->Port = SMTP_PORT; 
                $this->_mailer->Username = SMTP_USERNAME;
                $this->_mailer->Password = SMTP_PASSWORD;
                
                //set the linefeed
                if (EMAIL_LINEFEED == 'LF')
                {
                    $this->_mailer->LE = "\n";
                }
                else
                {
                    $this->_mailer->LE = "\r\n";
                }
            }
            else
            {
                $this->_mailer->IsSendmail();
            }
            
            //Send the html content
            if (EMAIL_USE_HTML == '1')
            {
                $this->_mailer->IsHTML();
            }
            
            //Whether the email was sent successfully
            try 
            {
                $this->_mailer->Send();
                
                return TRUE;
            } 
            catch (Exception $e) 
            {
                $this->_errors[] = $e->errorMessage();
                
                return FALSE;
            }
        }
    }
    
// ------------------------------------------------------------------------

    /**
     * Get the error message
     *
     * @access public
     * @return string
     */
    public function get_errors()
    {
        $error_message = '';
        if (!empty($this->_errors))
        {
            foreach($this->_errors as $error)
            {
                $error_message .= $error;
            }
        }
        
        return $error_message;
    }
}

/* End of file email.php */
/* Location: ./system/libraries/email.php */