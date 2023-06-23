<?php

	namespace yoandm\phpEndi;

	class Invoice extends Request {

		public static function add($customer, $project, $name){

			$csrf_token = self::getCsrf('/companies/' . phpEndi::$company . '/invoices');

			$data = array(
				'customer_id' => $customer,
				'project_id' => $project,
				'name' => $name,
				'business_type_id' => 2,
				'csrf_token' => $csrf_token
			);

			$result = self::json('/api/v1/companies/' . phpEndi::$company . '/invoices/add', $data, 'POST');

			return $result['response'];

		}	

		public static function setObject($invoice, $object){

			$data = array(
				'description' => $object
			);

			$result = self::json('/api/v1/invoices/' . $invoice, $data, 'PATCH');

		}

		public static function get($invoice){

			$result = self::json('/api/v1/invoices/' . $invoice , array(), 'GET');

			return $result['response'];
		}

		public static function getPdf($invoice){
			$result = self::html('/invoices/' . $invoice . '.pdf' , array(), 0);

			if($result['code'] != 200)
				return 0;

			return $result['response'];

		}

		public static function getTaskLineGroups($invoice){

			$result = self::json('/api/v1/invoices/' . $invoice .'/task_line_groups', array(), 'GET');

			return $result['response'];
		}

		public static function addLine($invoice, $data){
			$result = self::json('/api/v1/invoices/' . $invoice .'/task_line_groups/' . $data['group_id'] . '/task_lines', $data, 'POST');

		}

		public static function setDisplayUnit($invoice, $display){

			$data = array(
				'display_units' => $display
			);		

			$result = self::json('/api/v1/invoices/' . $invoice, $data, 'PATCH');

		}

		public static function setDisplayTTC($invoice, $display){

			$data = array(
				'display_ttc' => $display
			);		

			$result = self::json('/api/v1/invoices/' . $invoice, $data, 'PATCH');

		}

		public static function setPaymentConditions($invoice, $condition){

			$data = array(
				'payment_conditions' => $condition
			);		

			$result = self::json('/api/v1/invoices/' . $invoice, $data, 'PATCH');

		}

		public static function save($invoice, $data){

			$data = array_merge($data, array(
				'id' => $invoice,
				'business_type_id' => 2
			));		

			$result = self::json('/api/v1/invoices/' . $invoice, $data, 'PATCH');

		}

		public static function askValidation($invoice, $comment = ''){

			$data = array(
				'comment' => $comment,
				'submit' => 'wait'
			);		

			$result = self::json('/api/v1/invoices/' . $invoice . '?action=status', $data, 'POST', array('Referer' => phpEndi::$url . '/invoices/' . $invoice));

		}
	}