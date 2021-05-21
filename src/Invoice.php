<?php

	namespace yoandm\phpEndi;

	class Invoice extends Request {

		public static function add($customer, $project, $name){

			$data = array(
				'__formid__' => 'deform',
				'customer_id' => $customer,
				'project_id' => $project,
				'name' => $name,
				'business_type_id' => 2
			);

			$result = self::html('https://' . phpEndi::$url . '/company/' . phpEndi::$company . '/invoices?action=add', $data);

			if(! preg_match('/Location: https:\/\/' . phpEndi::$url . '\/invoices\/([0-9]*)/', $result['response'], $res))
				return 0;

			return $res[1];
			
		}	

		public static function setObject($invoice, $object){

			$data = array(
				'description' => $object
			);

			$result = self::json('https://' . phpEndi::$url . '/api/v1/invoices/' . $invoice, $data, 'PATCH');

		}

		public static function get($invoice){

			$result = self::json('https://' . phpEndi::$url . '/api/v1/invoices/' . $invoice , array(), 'GET');

			return $result['response'];
		}

		public static function getPdf($invoice){
			$result = self::html('https://' . phpEndi::$url . '/invoices/' . $invoice . '.pdf' , array(), 0);

			if($result['code'] != 200)
				return 0;

			return $result['response'];

		}

		public static function getTaskLineGroups($invoice){

			$result = self::json('https://' . phpEndi::$url . '/api/v1/invoices/' . $invoice .'/task_line_groups', array(), 'GET');

			return $result['response'];
		}

		public static function addLine($invoice, $data){
			$result = self::json('https://' . phpEndi::$url . '/api/v1/invoices/' . $invoice .'/task_line_groups/' . $data['group_id'] . '/task_lines', $data, 'POST');

		}

		public static function setDisplayUnit($invoice, $display){

			$data = array(
				'display_units' => $display
			);		

			$result = self::json('https://' . phpEndi::$url . '/api/v1/invoices/' . $invoice, $data, 'PATCH');

		}

		public static function setDisplayTTC($invoice, $display){

			$data = array(
				'display_ttc' => $display
			);		

			$result = self::json('https://' . phpEndi::$url . '/api/v1/invoices/' . $invoice, $data, 'PATCH');

		}

		public static function setPaymentConditions($invoice, $condition){

			$data = array(
				'payment_conditions' => $condition
			);		

			$result = self::json('https://' . phpEndi::$url . '/api/v1/invoices/' . $invoice, $data, 'PATCH');

		}

		public static function save($invoice, $data){

			$data = array_merge($data, array(
				'id' => $invoice,
				'business_type_id' => 2
			));		

			$result = self::json('https://' . phpEndi::$url . '/api/v1/invoices/' . $invoice, $data, 'PATCH');

		}

		public static function askValidation($invoice, $comment = ''){

			$data = array(
				'comment' => $comment,
				'submit' => 'validation'
			);		

			$result = self::json('https://' . phpEndi::$url . '/api/v1/invoices/' . $invoice . '?action=status', $data, 'PATCH');

		}
	}