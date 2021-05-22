<?php

	namespace yoandm\phpEndi;

	class phpEndi extends Request{

		public static $cookie;
		public static $company;
		public static $url;

		public static function connect($login, $password, $url, $company){

			
			self::$company = $company;
			self::$url = $url;

			$data = array(
				'__formid__' => 'authentication',
				'login' => $login,
				'password' => $password,
				'nextpage' => '',

			);
			$result = self::html('/login?nextpage=%2F', $data);

			if(! strstr($result['response'], 'The resource was found at /'))
				return 0;

			if(empty(self::$cookie)){
				preg_match('/Set-Cookie: ([^;]*);/', $result['response'], $res);
				self::$cookie = $res[1];
			}

			return 1;
		}

		public static function destroySession(){
			$result = self::html('/logout', array());

		}

	}