<?php

	require '../autoload.php';

	use yoandm\phpEndi\phpEndi;
	use yoandm\phpEndi\Customer;
	use yoandm\phpEndi\Invoice;

	// Remplacer 1000 par l'identiant de votre structure dans enDI
	// Si vous allez dans "Mon enseigne" et que vous jetez un oeil à l'URL de la page
	// vous verrez companies/xxxx. C'est ce xxxx qui nous intéresse
	phpEndi::connect('email@domain.com', 'password', 'mydomain.fr', 1000); 

	// Pour ajouter un client
	$client = Customer::add([
				'code' => '',
				'company_name' => 'test',
				'civilite' => '',
				'lastname' => '',
				'firstname' => '',
				'function' => '',
				'address' => '17 rue de la tour',
				'zip_code' => '75002',
				'city' => 'Paris',
				'country' => '',
				'tva_intracomm' => '',
				'registration' => '',
				'email' => '',
				'mobile' => '',
				'phone' => '',
				'fax' => '',
				'comments' => ''
	]);
	
	// Pour ajouter le client à un projet
	// remplacez 10 par l'identifiant de votre projet (dossier)
	// Vente / Dossier, vous sélectionnez votre projet
	// Vous verrez une URL du type projects/xxxx/invoices
	// C'est ce xxxx qui nous intéresse ici
	Customer::addToProject($client, 10);

	// Création d'une facture
	$facture = Invoice::add($client, 10, 'Ma super facture'); // remplacez 10 par l'identifiant de votre projet

	// Mise à jour de l'objet de la facture
	Invoice::setObject($facture, 'Ma super facture');

	// Pour que la facture affiche le détail
	Invoice::setDisplayUnit($facture, 1);

	// Ajout d'une ligne dans la facture
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

	// Conditions de paiement spécifique
	Invoice::setPaymentConditions($facture, 'Paiement par CB');

	// Enregistrement de la facture modifiée
	Invoice::save($facture, [
								'name' => 'Ma super facture',
								'financial_year' => date('Y')	
						]
	);

	// Fermeture de la session
	phpEndi::destroySession();