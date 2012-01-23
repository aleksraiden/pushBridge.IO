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
 
 require_once('AdapterInterface.php');

/**
 * Interface for common adapter operations
 *
 * @category   pushBridge
 * @package    pushBridge_Adapter
 * @subpackage Adapter
 * @copyright  Copyright (c) AGPsource.com 2007-2012
 * @license    http://agpsource.com/license/new-bsd     New BSD License
 */
class AbstractAdapter implements pushBridge_Adapter_AdapterInterface
{
    protected $_connection = null;
	protected $_config = null;
	protected $_auth = null; //данные для авторизации
	
	
	/**
     * Constructor
     *
     * @param  array|Zend_Config $options
     * @return void
     */
    public function __construct($options = Array(), $serializer){
	
	
	}

    /**
     * Retrieve connection instance
     *
     * @return mixed
     */
    public function getConnection(){
		return $this->_connection;
	}

    /**
     * Prepare and connect to provider
	 * @return boolean
     */
    public function connect(){
	
	}

    /**
     * Disconnect from provider
     *
     * @return boolean
     */
    public function close(){
	
	}
	
	/**
     * Sending data to provider
     *
     * @return boolean
     */
    public function send($data = null, $to = Array()){
	
	}
	
}
