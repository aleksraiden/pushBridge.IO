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
require_once 'pushBridge/Adapter/API/Pusher.php';
 
/**
 * Interface for common adapter operations
 *
 * @category   pushBridge
 * @package    pushBridge_Adapter
 * @subpackage Adapter
 * @copyright  Copyright (c) AGPsource.com 2007-2012
 * @license    http://agpsource.com/license/new-bsd     New BSD License
 */
class pushBridge_Adapter_Pusher implements pushBridge_Adapter_AdapterInterface
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
		
		// Check for dependent PHP extensions (JSON, cURL)
		if ( ! extension_loaded( 'curl' ) || ! extension_loaded( 'json' ) )
			throw new Exception('There is missing dependant extensions - please ensure both cURL and JSON modules are installed' );
		# Supports SHA256?
		if ( ! in_array( 'sha256', hash_algos() ) )
			throw new Exception( 'SHA256 appears to be unsupported - make sure you have support for it, or upgrade your version of PHP.' );
		
		
		//обязательные publish_key который для унификации секрет всеравно :) 
		if ((!array_key_exists('secretKey', $options)) || (empty($options['secretKey'])))
			throw new Excepion('Empty secret key (also called auth key)');
		
		if ((!array_key_exists('authKey', $options)) || (empty($options['authKey'])))
			throw new Excepion('Empty auth key');
			
		if ((!array_key_exists('appId', $options)) || (empty($options['appId'])))
			throw new Excepion('Empty appId');

		if ((!array_key_exists('debug', $options)) || (empty($options['debug'])))
			$options['debug'] = false;
		else
			$options['debug'] = true;

		//!TODO: переписать на свою реализацию через Zend_Http
		$this->_connection = new Pusher($options['authKey'], $options['secretKey'], $options['appId'], $options['debug']);
		
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
    public function send($data = '', $to = Array('test_channel'), $config = Array('event' => 'my_event', 'socket_id' => null, 'debug' => false)){
		//для пушера надо channel, event и данные
		// в $to у нас будет channel
		if (empty($data))
			throw new Exception('Empty data value');
		
		if (is_array($to))
			$_channel = (string)array_shift($to);
		else
			$_channel = (string)$to;
			
		if (empty($_channel)) $_channel = 'test_channel';			
		if (empty($config['event'])) $config['event'] = 'my_event';
		
		//еще может быть socket_id и дебаг флаг 
		if ((!array_key_exists('socket_id', $config)) || (empty($config['socket_id'])))
			$config['socket_id'] = null;
			
		if ((!array_key_exists('debug', $config)) || (empty($config['debug'])))
			$config['debug'] = false;
		
		
		return $this->getConnection()->trigger($_channel, $config['event'], $data, $config['socket_id'], $config['debug']);
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
