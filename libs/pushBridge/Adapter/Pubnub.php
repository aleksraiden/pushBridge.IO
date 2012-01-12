<?php
/**
 * pushBridge.IO
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   pushBridge
 * @package    pushBridge_Adapter
 * @subpackage Adapter
 * @copyright  Copyright (c) AGPsource.com 2007-2012
 * @license    http://agpsource.com/license/new-bsd     New BSD License
 * @version    1.0
 */

 /**
 * @see Zend_Queue_Adapter_AdapterAbstract
 */
require_once 'pushBridge/Adapter/AdapterInterface.php';
require_once 'pushBridge/Adapter/API/Pubnub.php';
 
/**
 * Interface for common adapter operations
 *
 * @category   pushBridge
 * @package    pushBridge_Adapter
 * @subpackage Adapter
 * @copyright  Copyright (c) AGPsource.com 2007-2012
 * @license    http://agpsource.com/license/new-bsd     New BSD License
 */
class pushBridge_Adapter_Pubnub implements pushBridge_Adapter_AdapterInterface
{
     /**
     * @var resource
     */
    protected $_connection = null;
	protected $_config = null;
	
	/**
     * Constructor
     *
     * @param  array|Zend_Config $options
     * @return void
     */
    public function __construct($options = Array()){
		
		// Check for dependent PHP extensions (JSON)
		if ( ! extension_loaded( 'json' ) )
			throw new Exception('There is missing dependant extensions - please ensure JSON modules are installed' );
		
		//обязательные publish_key который для унификации секрет всеравно :) 
		if ((!array_key_exists('secretKey', $options)) || (empty($options['secretKey'])))
			throw new Excepion('Empty secret key (also called auth key)');
		
		if ((!array_key_exists('authKey', $options)) || (empty($options['authKey'])))
			throw new Excepion('Empty auth (publish) key');
			
		if ((!array_key_exists('readKey', $options)) || (empty($options['readKey'])))
			$options['readKey'] = 'demo';
		
		if ((!array_key_exists('ssl', $options)) || (empty($options['ssl'])))
			$options['ssl'] = false;
		else
			$options['ssl'] = true;

		//!TODO: переписать на свою реализацию через Zend_Http
		$this->_connection = new Pubnub($options['authKey'], $options['readKey'], $options['secretKey'], $options['ssl']);
		
		$this->_config = $options;
	}

    /**
     * Retrieve connection instance
     *
     * @return Zend_Queue
     */
    public function getConnection(){
		return $this->_connection;
	}
	
	/**
     * Sending data to provider
	 * @return boolean
     */
    public function send($data = '', $to = Array('test_channel'), $config = Array()){
		if (empty($data))
			throw new Exception('Empty data value');
		
		if (is_array($to))
			$_channel = (string)array_shift($to);
		else
			$_channel = (string)$to;
			
		if (empty($_channel)) $_channel = 'test_channel';			
		
		$_res = $this->getConnection()->publish(Array('channel' => $_channel, 'message' => $data));
		
		if ((is_array($_res)) && (count($_res) > 1))
		{
			if ($_res[1] == 'S')
				return true;
			else
				return false;
		}
		else
			return false;		
	}

    /**
     * Prepare and connect to provider
	 * @return boolean
     */
    public function connect(){
		return true;
	}

    /**
     * Disconnect from provider
     *
     * @return boolean
     */
    public function close(){
		return true;
	}
}
