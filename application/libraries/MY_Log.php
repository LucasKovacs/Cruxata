<?php
/**
 * MY_Log Class
 *
 * This library extends the native Log library.
 * It adds the function to have the log messages being emailed when they have been outputted to the log file.
 *
 * @package     CodeIgniter
 * @subpackage      Libraries
 * @category        Logging
 * @author      Johan Steen
 * @link        http://wpstorm.net/
 */
class MY_Log extends CI_Log {
    /**
     * Constructor
     *
     * @access  public
     */
    public function __construct()
    {
        parent::__construct();
    }
 
    /**
     * Write Log File
     *
     * Calls the native write_log() method and then sends an email if a log message was generated.
     *
     * @access  public
     * @param   string  the error level
     * @param   string  the error message
     * @param   bool    whether the error is a native PHP error
     * @return  bool
     */
    public function write_log ( $level = 'error' , $msg , $php_error = FALSE )
    {
        $result = parent::write_log ( $level , $msg , $php_error );
 
        if ( $result == TRUE && strtoupper ( $level ) == 'ERROR' ) 
        {
            $message	= date ( $this->_date_fmt ) . ": \n\n";
            $message   .= $msg ."\n";
 
 	    if ( strpos ( $msg , 'Notice' ) !== FALSE )
 	    {
 	    	$level = 'NOTICE';
 	    }
 	    
 	    if ( strpos ( $msg , 'Warning' ) !== FALSE )
 	    {
 	    	$level = 'WARNING';
 	    }
 	    
  	    if ( strpos ( $msg , 'Fatal' ) !== FALSE )
 	    {
 	    	$level = 'FATAL';
 	    }	     	    
 
            $to 	= 'debug@cruxata.com';
            $subject 	= '[' . $level . '] An error has occured';
            $headers 	= 'From: ERROR REPORT <error-report@cruxata.com>' . "\r\n";
            $headers   .= 'Content-type: text/plain; charset=utf-8\r\n';
 
            mail ( $to , $subject , $message , $headers );
        }
        
        return $result;
    }
}

/* End of file MY_Log.php */
/* Location: ./application/libraries/MY_Log.php */