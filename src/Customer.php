<?php

	namespace yoandm\phpEndi;

	class Customer extends Request {
		public static function add($data){

			$data = array_merge($data, array(
				'__formid__' => 'company'
			));

			$result = self::html('https://' . phpEndi::$url . '/company/' . phpEndi::$company . '/customers', $data);

			if(! preg_match('/Location: https:\/\/' . phpEndi::$url . '\/customers\/([0-9]*)/', $result['response'], $res))
				return 0;

			return $res[1];
			
		}

		public static function addToProject($customer, $project){

			$data = array(
				'__formid__' => 'deform',
				'project_id' => $project

			);

			$result = self::html('https://' . phpEndi::$url . '/customers/' . $customer . '?action=addcustomer', $data);

			return 1;
			
		}	

	}