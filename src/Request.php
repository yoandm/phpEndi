<?php

	namespace yoandm\phpEndi;

	class Request {


		public static function multipart_build_query($fields){
			$boundary = '------WebKitFormBoundarykCpSmBbPgyusHvjn';

			$retval = '';
			foreach($fields as $key => $value){
				$retval .= "$boundary\r\nContent-Disposition: form-data; name=\"$key\"\r\n\r\n$value\r\n";
			}

			$retval .= "$boundary--\r\n";
			
			return $retval;
		}

		public static function html($endpoint, $data, $headers = 1){

			$url = phpEndi::$url . $endpoint;

			$data = array_merge($data, array(
				'_charset_' => 'utf-8',
				'submit' => ''
			));


			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			
			if($headers)
				curl_setopt($ch,  CURLOPT_HEADER,  1);
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, self::multipart_build_query($data));

			$headers = array();
			$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
			$headers[] = 'Content-Type: multipart/form-data; boundary=----WebKitFormBoundarykCpSmBbPgyusHvjn';
			$headers[] = 'Origin: ' . phpEndi::$url;
			if(! empty(phpEndi::$cookie))
				$headers[] = 'Cookie: ' . phpEndi::$cookie . ';';

			$headers[] = 'Accept-Language: fr-fr';
			$headers[] = 'Host: ' . str_replace('https://', '', phpEndi::$url);
			$headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.1.2 Safari/605.1.15';
			$headers[] = 'Referer: ' . phpEndi::$url . '/login?nextpage=%2F';
			$headers[] = 'Connection: keep-alive';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);

			return array(
				'code' => $httpcode,
				'response' => $result
			);

		}

		public static function json($endpoint, $data, $method){

			$url = phpEndi::$url . $endpoint;

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			switch ($method) {
				case 'POST':
					curl_setopt($ch, CURLOPT_POST, 1); 
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
					break;
				case 'PATCH':
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
					break;
			}		

			//

			$headers = array();
			$headers[] = 'Content-Type: application/json';
			$headers[] = 'Pragma: no-cache';
			$headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';

			if(! empty(phpEndi::$cookie))
				$headers[] = 'Cookie: ' . phpEndi::$cookie . ';';

			$headers[] = 'Accept-Encoding: gzip, deflate';
			$headers[] = 'Host: ' . str_replace('https://', '', phpEndi::$url);
			$headers[] = 'Accept-Language: fr-fr';
			$headers[] = 'Cache-Control: no-cache';
			$headers[] = 'Origin: ' . phpEndi::$url;
			$headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.1.2 Safari/605.1.15';
			$headers[] = 'Connection: keep-alive';
			$headers[] = 'X-Requested-With: XMLHttpRequest';
			$headers[] = 'X-Csrftoken: undefined';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			return array(
				'code' => $httpcode,
				'response' => json_decode($result, 1)
			);
		}
		
	}