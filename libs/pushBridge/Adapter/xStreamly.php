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
class pushBridge_Adapter_xStreamly implements pushBridge_Adapter_AdapterInterface
{
     /**
     * @var resource
     */
    protected $_connection = null;
	protected $_config = null;
	protected $_uri = null;
	
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
			
		//нам обязателен SSL транспорт 
		$_tr = stream_get_transports();
		// ssl, sslv3, sslv2
		if (!in_array('ssl', $_tr))
			throw new Excepion('We neew SSL connection to communicate with x-Stream.ly server');	
			
		//не обязательный
		if ((!array_key_exists('secretKey', $options)) || (empty($options['secretKey'])))
			throw new Excepion('Empty password');
			
		if ((!array_key_exists('emailKey', $options)) || (empty($options['emailKey'])))
			throw new Excepion('Empty e-mail ');
				
		if ((!array_key_exists('authKey', $options)) || (empty($options['authKey'])))
			throw new Excepion('Empty auth (API) key');
				
		
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

		$this->_uri = 'https://secure.x-stream.ly/api/v1.1';
		$this->_connection = new Zend_Http_Client($this->_uri, array(
			'maxredirects' => 1,
			'timeout'      => 30,			
			'keepalive'    => true,
			'adapter'	   => $_adapter
			//'ssltransport' => 'sslv2',
            //'sslusecontext' => TRUE
		));
			
		$this->_connection->setHeaders(array('X-Powered-By' => 'pushBridge.IO API'));
		
		$this->_connection->setAuth($options['emailKey'], $options['secretKey'], Zend_Http_Client::AUTH_BASIC);
		
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
    public function send($data = '', $to = 'mychannel', $config = Array('event' => 'my_event')){
		if (empty($data))
			throw new Exception('Empty data value');
		
		if ((!array_key_exists('event', $config)) || (empty($config['event'])))
			$config['event'] = 'my_event';
		
		if ((!array_key_exists('persisted', $config)) || (empty($config['persisted'])))
			$config['persisted'] = false;
	
		$this->getConnection()->setRawData($data);	

		if ( $config['persisted'] === true )
			$_p = '?persisted=true';
		else
			$_p = '?persisted=false';


		
		$this->getConnection()->setUri($this->_uri . '/' . $this->_config['authKey'] . '/channels/' . $to . '/events/' . $config['event'] . $_p);
		$this->_connection->setMethod(Zend_Http_Client::POST);
		
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
