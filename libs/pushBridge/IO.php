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
 * Interface for common adapter operations
 *
 * @category   pushBridge
 * @package    pushBridge_Adapter
 * @subpackage Adapter
 * @copyright  Copyright (c) AGPsource.com 2007-2012
 * @license    http://agpsource.com/license/new-bsd     New BSD License
 */
class pushBridge_IO
{
     /**
     * @var resource
     */
    protected $_adapter = null;	
	protected $_config = null;
	protected $_serializer = null;
	
	private $_defaultSerializer = 'json';
	
	/**
     * Constructor
     *
     * @param  pushBridge_Adapter instance
     * @return void
     */
    public function __construct(pushBridge_Adapter_AdapterInterface $adapter, $serializer = null, $serializerOption = Array()){
		
		$this->_adapter = $adapter;
		$this->setSerializer( $serializer, $serializerOption );
		$this->connect();		
	}
	
	/**
     * Set serializer interface
     *
     * @param  mixed Zend_Serializer instance or null to use default
     * @return void
     */
	public function setSerializer($serializer = null, $option = Array()){
	
		if ($serializer instanceof Zend_Serializer)	
			$this->_serializer = $serializer;
		else
		{
			if (empty($serializer))
				$this->_defaultSerializer = 'json';
			else
				$this->_defaultSerializer = (string)$serializer;			
			
			if (class_exists('Zend_Serializer'))
			{
				if ($this->_defaultSerializer === 'json')
					$this->_serializer = Zend_Serializer::factory('Json', $option);
				else
				if ($this->_defaultSerializer === 'php')
					$this->_serializer = Zend_Serializer::factory('PhpSerialize', $option);
				else
				if ($this->_defaultSerializer === 'pickle')
					$this->_serializer = Zend_Serializer::factory('PythonPickle', $option);
			}
			else
				throw new Exception('Class serializer not found (need: Zend_Serializer)');
		}
	}

    /**
     * Retrieve connection instance
     *
     * @return resource
     */
    public function getConnection(){
		if ($this->_adapter instanceOf  pushBridge_Adapter)
			return $this->_adapter->getConnection();	
	}

    /**
     * Prepare and connect to provider
	 * @return boolean
     */
    public function connect(){
		if ($this->_adapter instanceOf  pushBridge_Adapter)
			return $this->_adapter->connect();
	}

    /**
     * Disconnect from provider
     *
     * @return boolean
     */
    public function close(){
		if ($this->_adapter instanceOf  pushBridge_Adapter)
			return $this->_adapter->close();
			
		return true;
	}
	
	
	/**
     * Disconnect from provider
     *
     * @return boolean
     */
    public function send($data = null, $to = Array(), $config = Array()){
		$_data = ''; 
		
		//serializing data
		if ((!array_key_exists('serialize', $config)) || (empty($config['serialize'])))
			$config['serialize'] = false;
		
		if (!( $this->_serializer instanceof Zend_Serializer_Adapter_AdapterAbstract ))
			$config['serialize'] = false;
		
		if ($config['serialize'] === false)
			$_data = (string)$data;
		else
			$_data = $this->_serializer->serialize( $data );
			
		if (!empty($_data))
			return $this->_adapter->send($_data, $to, $config);
		else
			throw new Exception('Empty data value');
	}	
	
}
