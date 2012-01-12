<?php /** examples **/

 $push = new pushBridge_IO( new pushBridge_Adapter_Partcl(
	'secretKey' => '<Your publish key from partcl.com>'
 ) );
 
 $push->send('Привет от pushBridge', 'test', Array('noSerialize' => true));






