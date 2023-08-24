<?php

//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

function StatusPayin($invoiceToken){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://app.ligdicash.com/pay/v01/redirect/checkout-invoice/confirm/?invoiceToken=".$invoiceToken,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Apikey: YNYZ3BXIFWRBBPF",
            "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhciOiJIUzI1NiJ9.eyJpZF9hcHAiOiI3NzQiLCJpZF9hYm9ubmUiOiI4OTk0MiIsImRhdGVjcmVhdGlvbl9hcHAiOiIyMDIxLTA4LTE4IDE4OjIwOjQyIn0.8rMinJMEDZeeoGNqcKxwD2VjXPC5t1__ilTJIOwFtQ4"
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
$invoiceToken=$_SESSION['invoiceToken'];
$idcompte=$_SESSION['idForum'];
$idparticipant=$_SESSION['idParticipant'];
$montant=$_SESSION['montant'];
$total=$_SESSION['total'];
$tid=$_SESSION['tid'];

$participant = \App\Participants::participant($idparticipant);

$Payin = StatusPayin($invoiceToken);

if(isset($Payin)) {
    if(trim($Payin->status)=="completed") {
        echo "Le client(Payer) a validé le paiement vous devez executé vos traitements apres paiement valide<br><br>";
        //print_r($Payin);
        $from_data = array(
            'id_compte'   =>  $idcompte,
            'id_participant'   =>  $idparticipant,
            'tid'   =>  $tid,
            'date'   =>  date('d/m/Y'),
            'montant'   =>  $montant,
            'total'   =>  $total,
            'frais'   =>  ($total - $montant),
            'tel_paiement'   =>  $Payin->customer,
            'nom_paiement'   =>  $participant->nomPart,
            'prenom_paiement'   =>  $participant->prenomPart,
            //'nom_recep'   =>  "",
            //'prenom_recep'   =>  "",
            'etat'   =>  $Payin->status,
            'operator_id'   =>  $Payin->operator_id,
            'operator_name'   =>  $Payin->operator_name,
            'token_d'   =>  $invoiceToken,
            'token_r'   =>  $invoiceToken
        );
        \App\Transaction::create($from_data);

        \Session::flash('success','Paiement effectué avec succès !!!');

        return view('participant.index');
        //echo 'status='.$Payin->status;;
        //echo '<br><br>';
        //echo 'response_text='.$Payin->response_text;
    }
    elseif(trim($Payin->status)=="nocompleted") {
        echo "Le client(Payer) a annulé le paiement donc vous devez executer vos traitements correspondant<br><br>";
        //print_r($Payin);
        echo 'status='.$Payin->status;;
        echo '<br><br>';
        echo 'response_text='.$Payin->response_text;
    }
    elseif(trim($Payin->status)=="pending") {
        echo "Le client(Payer) n'a pas encore validé le paiement mobile money,donc vous devez executer vos traitements correspondant<br><br>";
        //print_r($Payin);
        echo 'status='.$Payin->status;;
        echo '<br><br>';
        echo 'response_text='.$Payin->response_text;
    }
    else {
        echo '<br><br>Veuillez lire la documentation et le WIKI subcodes[]<br>';
        print_r($Payin);
    }
}else{
    return false;
}

?>
