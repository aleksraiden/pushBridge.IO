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
 
/**
 * Interface for common adapter operations
 *
 * @category   pushBridge
 * @package    pushBridge_Adapter
 * @subpackage Adapter
 * @copyright  Copyright (c) AGPsource.com 2007-2012
 * @license    http://agpsource.com/license/new-bsd     New BSD License
 */
class pushBridge_Adapter_ioBridge implements pushBridge_Adapter_AdapterInterface
{
     /**
     * @var resource
     */
    protected $_connection = null;
	protected $_config = null;
	protected $_uri = null;
	protected $_widget = null;
	
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
		
		if (!class_exists('Zend_Http_Client'))
			throw new Excepion('To obtain http connection we need Zend_Http component');
	
		//не обязательный
		if ((!array_key_exists('authId', $options)) || (empty($options['authId'])))
			throw new Excepion('Empty widgetId');
			
		//также кастомный конфиг - httpAdapterConfig 
		if ((!array_key_exists('httpAdapterConfig', $options)) || (empty($options['httpAdapterConfig'])))
			$options['httpAdapterConfig'] = Array();	
			
		if ((!array_key_exists('httpAdapter', $options)) || (empty($options['httpAdapter'])))
			$options['httpAdapter'] = 'socket';
			
				
		if ( $options['httpAdapter'] == 'socket' )
			$_adapter = new Zend_Http_Client_Adapter_Socket(); 
		else
		if ( $options['httpAdapter'] == 'curl' )
			$_adapter = new Zend_Http_Client_Adapter_Curl(); 
		else
		if ( $options['httpAdapter'] == 'proxy' )
			$_adapter = new Zend_Http_Client_Adapter_Proxy();
			
		if ( $_adapter instanceof Zend_Http_Client_Adapter_Interface ) //cheking interface, not class 
			$_adapter->setConfig( $options['httpAdapterConfig'] );
		else
			throw new Exception('Error while obtain underline HTTP adapter');
			
		//здесб уже два запроса надо 
		$this->_connection = new Zend_Http_Client('http://www.iobridge.com/interface/?actionID=RM_Widget&widgetID='.$options['authId'].'&dt=' . time(), array(
			'maxredirects' => 1,
			'timeout'      => 30,			
			'keepalive'    => true,
			'adapter'	   => $_adapter
		));
		$this->_connection->setHeaders(array('X-Powered-By' => 'pushBridge.IO API'));
		$this->_connection->setMethod(Zend_Http_Client::GET);
		
		$_resp = $this->_connection->request();
		
		
		if ((!($_resp instanceof Zend_Http_Response)) || (!$_resp->isSuccessful()))
			throw new Exception('Error while obtain widget ID from iobridge interface');
		else
		{
			$tmp = trim($_resp->getBody());
			
			$_st = strpos($tmp, '?actionID=');
			$_ft = strpos($tmp, '&inFrame');
			
			$tmp = explode('&', substr($tmp, $_st, ($_ft - $_st)));
			$_act = explode('=', $tmp[0]);
			$_ses = explode('=', $tmp[1]);
			
			$this->_widget = Array('actionID' => $_act[1], 'sessionID' => $_ses[1]);
		}
		
		$this->_connection->resetParameters(true);		

		$this->_uri = 'http://www.iobridge.com/interface/?format=json&actionID='.$this->_widget['actionID'].'&sessionID='.$this->_widget['sessionID'];
		$this->_connection = new Zend_Http_Client($this->_uri, array(
			'maxredirects' => 1,
			'timeout'      => 30,			
			'keepalive'    => true,
			'adapter'	   => $_adapter
		));
			
		$this->_connection->setHeaders(array('X-Powered-By' => 'pushBridge.IO API'));
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
    public function send($data = '', $to = 'mychannel', $config = Array()){
		if (empty($data))
			throw new Exception('Empty data value');
		
		if ((!array_key_exists('event', $config)) || (empty($config['event'])))
			$config['event'] = 'my_event';
		
		
		$this->getConnection()->setParameterGet('setValue', '1');
		$this->_connection->setMethod(Zend_Http_Client::GET);
		
		$response = $this->getConnection()->request();
		
		if (!($response instanceof Zend_Http_Response))
			return false;
		else
		{
			//!TODO: а что с редиректами делать? 
			if (($response->isSuccessful()) || ($response->isRedirect()))
				return true;
			else
				if ($response->isError())
					return false;		
		}		
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
	
	/**
	 *	Get online users 
	 *  API specific to BeaconPush 
	 *  return int|boolean count of online users at channel
	 */
	 public function getSubscription(){
		$this->_connection->setMethod(Zend_Http_Client::GET);
		$this->getConnection()->setUri('http://api.beaconpush.com/'.$this->config['versionAPI'].'/'.$this->config['authKey'].'/users');
		
		$response = $this->getConnection()->request();
		
		if (!($response instanceof Zend_Http_Response))
			return false;
		else
		{
			//!TODO: а что с редиректами делать? 
			if (($response->isSuccessful()) || ($response->isRedirect()))
			{
				$_resp = Zend_Json::decode( $response->getBody() );
				return $_resp['online'];
			}else
				if ($response->isError())
					return false;		
		}	 
	 }
}
