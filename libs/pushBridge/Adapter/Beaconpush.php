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
require_once 'pushBridge/Adapter/API/BeaconPush.php';
 
/**
 * Interface for common adapter operations
 *
 * @category   pushBridge
 * @package    pushBridge_Adapter
 * @subpackage Adapter
 * @copyright  Copyright (c) AGPsource.com 2007-2012
 * @license    http://agpsource.com/license/new-bsd     New BSD License
 */
class pushBridge_Adapter_Beaconpush implements pushBridge_Adapter_AdapterInterface
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
		
		//не обязательный
		if ((!array_key_exists('secretKey', $options)) || (empty($options['secretKey'])))
			$options['authKey'] = null;
				
		if ((!array_key_exists('authKey', $options)) || (empty($options['authKey'])))
			throw new Excepion('Empty auth (API) key');
				
		
		//!TODO: переписать на свою реализацию через Zend_Http
		$this->_connection = new BeaconPush(Array('api_key' => $options['authKey'], 'secret_key' => $options['secretKey']));
		
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
    public function send($data = '', $to = Array('mychannel'), $config = Array('event' => 'my_event')){
		if (empty($data))
			throw new Exception('Empty data value');
		
		if ((is_string($to)) && (!empty($to)))
		{
			$to = Array();
			$to[] = $to;			
		}
		
		if (is_array($to))
		{
			foreach($to as $x)
			{
				$this->getConnection()->add_channels($x);
			}
		}
		
		if ((!array_key_exists('event', $config)) || (empty($config['event'])))
			$config['event'] = 'my_event';
	
		$_res = Array();
		foreach($to as $_channel)
		{
			$_res[] = $this->getConnection()->send_to_channel($_channel, $config['event'], $data);
		}

	
		return $_res;

	
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
