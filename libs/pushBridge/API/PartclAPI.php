<?php

class PartlAPI {
	private $publish_key = '';
	
	public function __contstruct($publish_key = null)
	{
		$this->publish_key = $publish_key;	
	}
	
	public static function send($tag = 'test', $val = 'test') {
		$url = 'http://partcl.com/publish?publish_key='. self::publish_key .'&id='. $tag .'&value='. $val;
		
		file_get_contents($url);
	}
}