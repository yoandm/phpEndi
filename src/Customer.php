<?php

	namespace yoandm\phpEndi;

	class Customer extends Request {
		public static function add($data){

			$csrf_token = self::getCsrf('/companies/' . phpEndi::$company . '/customers');

			$data = array_merge(
				$data,
				array(
					'csrf_token' => $csrf_token
				)
			);

			$result = self::json('/api/v1/companies/' . phpEndi::$company . '/customers', $data, 'POST');

			if((int) $result['code'] !== 200)
				return 0;

			return $result['response'];
			
		}

		public static function addToProject($customer, $project){

			$data = array(
				'__formid__' => 'deform',
				'project_id' => $project

			);

			$result = self::html('/customers/' . $customer . '?action=addcustomer', $data);

			return 1;
			
		}	

	}
