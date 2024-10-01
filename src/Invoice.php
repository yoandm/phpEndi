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

		public static function list($search = array()){

			$customer_id = '';
			$financial_year = '-1';
			$invoice_ref = '';
			$paid_status = 'all';

			if(isset($search['customer_id']))
				$customer_id = $search['customer_id'];

			if(isset($search['financial_year']))
				$financial_year = $search['financial_year'];

			if(isset($search['invoice_ref']))
				$invoice_ref = $search['invoice_ref'];

			if(isset($search['paid_status']))
				$paid_status = $search['paid_status'];
							
			$result = self::getHtml('/company/' . phpEndi::$company . '/invoices.csv?_charset_=UTF-8&__formid__=deform&year=-1&__start__=period%3Amapping&__start__=start%3Amapping&date=&__end__=start%3Amapping&__start__=end%3Amapping&date=&__end__=end%3Amapping&__end__=period%3Amapping&customer_id=' . $customer_id . '&__start__=ttc%3Amapping&start=&end=&__end__=ttc%3Amapping&financial_year=' . $financial_year . '&doctype=both&status=all&paid_status=' . $paid_status . '&payment_mode=all&business_type_id=all&search=' . $invoice_ref. '&items_per_page=100000');

			preg_match('/jobs\/([0-9]*)/', $result['response'], $res);
		
			$result = self::json('/jobs/' . $res[1], array(), 'get');
			$result = self::getHtml('/cooked/' . $result['response']['filename'])['response'];

			preg_match('/\r\n\r\n(.*)/s', $result, $res);

			$csv = trim($res[1]);

			
			$csv = str_replace('"', '', $csv);
			$csv = explode("\n", $csv);

			$titles = array();
			$invoices = array();

			foreach (explode(';', $csv[0]) as $key) {
				$titles[] = trim($key);
			}

			for($i = 1; $i < count($csv); $i++){
				$line = explode(';', $csv[$i]);
				for($j = 0; $j < count($line); $j++){
					$invoice[$titles[$j]] = $line[$j];
				}

				$invoices[] = $invoice; 
			}

			return $invoices;
		}
	}