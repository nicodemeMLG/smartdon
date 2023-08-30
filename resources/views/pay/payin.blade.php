<?php
$id_aut=auth()->user()->id;
$email_aut=auth()->user()->email;

function Payin_with_redirection($transaction_id,$amount){
    $nom_aut=auth()->user()->name;
    $prenom_aut=auth()->user()->firstname;
    $email_aut=auth()->user()->email;

    $Api_key = '1Y9GYNQHHCPSUQO9U';
    $key = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9hcHAiOiI3NzQiLCJpZF9hYm9ubmUiOjg5OTQyLCJkYXRlY3JlYXRpb25fYXBwIjoiMjAyMy0wMi0wMSAxOTozNDowNSJ9.CrkksYoYvihtI2m2KvBVu1l58XO8Y2F2phc3VPYrv7U';


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://app.ligdicash.com/pay/v01/redirect/checkout-invoice/create",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>'
					  {
					  "commande": {
						"invoice": {
						  "items": [
							{
							  "name": "Paiement de Don",
							  "description": "Une noble donation à l\'endroit des déplacés internes du Burkina",
							  "quantity": 1,
							  "unit_price": "'.$amount.'",
							  "total_price": "'.$amount.'"
							}
						  ],
						  "total_amount": "'.$amount.'",
						  "devise": "XOF",
						  "description": "Une noble donation à l\'endroit des déplacés internes du Burkina",
						  "customer": "",
						  "customer_firstname":"'.$prenom_aut.'",
						  "customer_lastname":"'.$nom_aut.'",
						  "customer_email":"'.$email_aut.'"
						},
						"store": {
						  "name": "Rencontre B2B",
						  "website_url": "http://localhost:8000"
						},
						"actions": {
						  "cancel_url": "http://localhost:8000/transaction",
						  "return_url": "http://localhost:8000/transaction/status",
						  "callback_url": "http://localhost:8000/transaction/status"
						},
						"custom_data": {
						  "transaction_id": "'.$transaction_id.'"
						}
					  }
					}',
        CURLOPT_HTTPHEADER => array(
            "Apikey: $Api_key",
            "Authorization: Bearer $key",
            "Accept: application/json",
            "Content-Type: application/json"
        ),
    ));

    $response = json_decode(curl_exec($curl));

    curl_close($curl);
    return $response;
}


//XXXXXXXXXXXXXXXX-EXECUTION DES METHODES-XXXXXXXXXXXXXXXXXXXXXXX

/*
 En cas de reclamation ou de besoin de correction ou verrification d'une transaction,
 vous pouvez rappeler la transaction en recuperant le token par session ou depuis votre DB ou par variable $_GET['token']
 Raison pour laquel vous devez stocker le 'invoiceToken=' de votre transaction client dans votre base de données historique transaction ou en variable SESSION
 On suppose que le 'invoiceToken=' est recuperé par exemple
*/
//echo $_GET['token'];
//$invoiceToken=$_GET['token'];

session_start();
//XXXXXXXXXXXXXXXX-EXECUTION DES METHODES-XXXXXXXXXXXXXXXXXXXXXXX

//$amount=$_GET['option']*1.035;

$redirectPayin =Payin_with_redirection($transaction_id,$montant);

//vous pouvez decommenter print_r($response) pour voir les resultats vour la documentationV1.2
//print_r($redirectPayin);exit;
//echo $redirectPayin->response_text;exit;
//echo $redirectPayin->token;exit;//Ce token doit etre enregistrer dans votre base de donne trasction client pour vos verrification de status apres paiement
$_SESSION['invoiceToken']=$redirectPayin->token;//Vous devez stoker ce TOKEN pour de verrification de status ulterieur
$_SESSION['idUser']=$id_aut;//On recupere l'identifiant du participant
//$_SESSION['idForum']=$_GET['f'];//On recupere l'identifiant du forum
$_SESSION['montant']=$montant;//On recupere le total
//$_SESSION['montant']=$_GET['option'];//On recupere le montant
$_SESSION['transaction_id']=$transaction_id;//On recupere l'identifiant transaction

if(isset($redirectPayin->response_code) and $redirectPayin->response_code=="00") {
    //$redirectPayin->response_text contient l'url de redirection
    header('Location: '.$redirectPayin->response_text);
    print_r($redirectPayin->response_code);exit;
}
else{
    echo 'response_code='.$redirectPayin->response_code;
    echo '<br><br>';
    echo 'response_text='.$redirectPayin->response_text;
    echo '<br><br>';
    echo 'description='.$redirectPayin->description;
    echo '<br><br>';
    echo '<br><br>Veuillez lire la documentation et le WIKI subcodes[]';
    exit;
}

?>


