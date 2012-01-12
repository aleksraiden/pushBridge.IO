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
class pushBridge_Adapter_Partcl implements pushBridge_Adapter_AdapterInterface
{
     /**
     * @var resource
     */
    protected $_connection = null;
	protected $_config = null;
	protected $_secretKey = null;
	
	/**
     * Constructor
     *
     * @param  array|Zend_Config $options
     * @return void
     */
    public function __construct($options = Array()){
		//обязательные publish_key который для унификации секрет всеравно :) 
		if ((!array_key_exists('secretKey', $options)) || (empty($options['secretKey'])))
			throw new Excepion('Empty secret key (also called publish key)');
		else
			$this->_secretKey = $options['secretKey'];
		
		if (!class_exists('Zend_Http_Client'))
			throw new Excepion('To obtain http connection we need Zend_Http component');
			
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
			
		if ((!array_key_exists('server', $options)) || (empty($options['server'])))
			$options['server'] = 'production';
		else
		if ($options['server'] == 'dev')
			$options['server'] = 'development';
		
		$_uri = 'http://partcl.com';
		
		if ( $options['server'] == 'development' )
			$_uri = 'http://dev.partcl.com';
		elseif ( $options['server'] == 'production' )
			$_uri = 'http://partcl.com';
		
		
		$this->_connection = new Zend_Http_Client($_uri . '/publish', array(
			'maxredirects' => 1,
			'timeout'      => 30,			
			'keepalive'    => true,
			'adapter'	   => $_adapter
		));
		
		if ((!array_key_exists('method', $options)) || (empty($options['method'])))		
			$options['method'] = 'GET';
		
		if ($options['method'] == 'GET')			
			$this->_connection->setMethod(Zend_Http_Client::GET);
		elseif ($options['method'] == 'POST')
			$this->_connection->setMethod(Zend_Http_Client::POST);

		
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
    public function send($data = '', $to = Array(), $config = Array()){
		//to не може быть пустым, это тег, куда паблишим 	
		if (empty($data))
			throw new Exception('Empty tag value');
			
		$this->getConnection()->setParameterGet('publish_key', $this->_secretKey);
		
		if (is_array($to))
			$_tag = (string)array_shift($to);
		else
			$_tag = (string)$to;
			
		if (empty($_tag))
			throw new Exception('Empty tag name');
			
		$this->getConnection()->setParameterGet('id', $_tag);
		
		//!TODO: фигня для Flash API 
		$data = str_replace('&amp;', '%26', $data);
        $data = str_replace('&', '%26', $data);
		
		$this->getConnection()->setParameterGet('value', $data);
		
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
		$this->_secretKey = null;
		return true;
	}
}
