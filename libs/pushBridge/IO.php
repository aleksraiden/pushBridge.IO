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
    protected $_socket = null;
	
	/**
     * Constructor
     *
     * @param  array|Zend_Config $options
     * @return void
     */
    public function __construct(pushBridge_Adapter $adapter){
	
	
	
	}

    /**
     * Retrieve connection instance
     *
     * @return resource
     */
    public function getConnection(){
	
	
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
     * Disconnect from provider
     *
     * @return boolean
     */
    public function send($data = null, $config = Array()){
	
	
	}	
	
}
