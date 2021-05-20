<?php

	require '../autoload.php';

	use yoandm\phpEndi\phpEndi;
	use yoandm\phpEndi\Customer;
	use yoandm\phpEndi\Invoice;

	phpEndi::connect('email@domain.com', 'password', 'mydomain.fr', 1000); 


	$client = Customer::add([
				'code' => '',
				'company_name' => 'test',
				'civilite' => '',
				'lastname' => '',
				'firstname' => '',
				'function' => '',
				'address' => 'rue test',
				'zip_code' => '63000',
				'city' => 'Clermont-Ferrand',
				'country' => '',
				'tva_intracomm' => '',
				'registration' => '',
				'email' => '',
				'mobile' => '',
				'phone' => '',
				'fax' => '',
				'comments' => ''
	]);
	


	Customer::addToProject($client, 142874);

	$facture = Invoice::add($client, 142874, 'Ma super facture');

	Invoice::setObject($facture, 'Ma super facture');
	Invoice::setDisplayUnit($facture, 1);

	$lgroups = Invoice::getTaskLineGroups($facture);

	Invoice::addLine($facture, [

								'order' => 1,
								'description' => "Super prestation du " . date('d/m/Y'),
								'cost' => 50,
								"quantity" => 1,
								'unity' => 'Unité(s)',
								'tva' => 20,
								'group_id' => $lgroups[0]['id'],
								'product_id' => 12,
								'mode' => 'ht'
						]

	);

	Invoice::setPaymentConditions($facture, 'Paiement par CB');

	Invoice::save($facture, [
								'name' => 'Ma super facture',
								'business_type_id' => 2,
								'financial_year' => date('Y')	
						]

	);



	phpEndi::destroySession();