<?php

class PartclAPI {
	private static $publish_key = '';

	public static function setPublishKey($key) {
		self::$publish_key = $key;
	}

	public static function send($tag, $val) {
		if(is_array($val) ) {
			$val = self::unicode_unescape( json_encode($val) );
            $val = str_replace('&amp;', '%26', $val);
            $val = str_replace('&', '%26', $val);
		}
		else {
			$val = urlencode($val);
		}



		$url = 'http://partcl.com/publish?publish_key='. self::$publish_key;

        $postData = array(
            'id'=> $tag,
            'value' => $val
        );


		self::curl_download($url, $postData);
	}
	public static function unicode_unescape($val) {
		return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'partcl_api_replace_unicode_escape_sequence', $val);
	}
    private static function curl_download($Url, $postData){

        // is cURL installed yet?
        if (!function_exists('curl_init')){
            die('Sorry cURL is not installed!');
        }

        // OK cool - then let's create a new cURL resource handle
        $ch = curl_init();

        // Now set some options (most are optional)

        // Set URL to download
        curl_setopt($ch, CURLOPT_URL, $Url);

        // Set a referer
        curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");

        // User agent
        curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");

        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        // post data
        $postDataString = '';
        foreach($postData as $key=>$value) { $postDataString .= $key.'='.$value.'&'; }
        rtrim($postDataString,'&');

        curl_setopt($ch,CURLOPT_POST, count($postData));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $postDataString);

        // Download the given URL, and return output
        $output = curl_exec($ch);

        // Close the cURL resource, and free system resources
        curl_close($ch);

        return $output;
    }

}

function partcl_api_replace_unicode_escape_sequence($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}