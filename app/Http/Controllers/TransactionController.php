<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;


//require_once '../../vendor/autoload.php';
use Twilio\Rest\Client;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id=auth()->user()->id;
        $transactions= Transaction::orderBy('id','desc')->where('user_id','=',$id)->paginate(10);
        //dd($transactions);

        return view('dashboard',[
            'transactions'=>$transactions,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'montant'=>['required','integer','min:100','max:1000000'],
        ]);

        $montant=$request->montant;
        $nom_aut=auth()->user()->name;
        $prenom_aut=auth()->user()->firstname;
        $email_aut=auth()->user()->email;
        $id_auth=auth()->user()->id;
        $transaction_id='MLG'.date('Y').date('m').date('d').'.'.date('h').date('m').'.a'.rand(5,100000);
        //paiment avec ligidicash
        // Update the path below to your autoload.php,
        // see https://getcomposer.org/doc/01-basic-usage.md
        $numero=auth()->user()->phone;


        /*$sid    = "ACb7b63fd01388e30b27a7015488f3c784";
        $token  = "1e95200f1cf872364667715b9feb7bbf";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
        ->create("+22664838676", // to
            array(
            "from" => "+15739283828",
            "body" => "Votre don de $montant a bien été réçu, merci pour votre générosité"
            )
        );

        print($message->sid);*/

        return view('pay.payin',[
            'transaction_id'=> $transaction_id,
            'montant'=>$montant,

        ]);

        //fin paiement

        /*$transaction = auth()->user()->transactions()->create([
            'numtrans'=>'11234556',
            'montant'=>$request->montant,
        ]);

        return redirect()->route('dashboard');*/
    }

    public function status() {
        session_start();
        //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
        $invoiceToken=$_SESSION['invoiceToken'];

        $Api_key = '1Y9GYNQHHCPSUQO9U';
        $key = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9hcHAiOiI3NzQiLCJpZF9hYm9ubmUiOjg5OTQyLCJkYXRlY3JlYXRpb25fYXBwIjoiMjAyMy0wMi0wMSAxOTozNDowNSJ9.CrkksYoYvihtI2m2KvBVu1l58XO8Y2F2phc3VPYrv7U';


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
                "Apikey: $Api_key",
                "Authorization: Bearer $key"
            ),
        ));
        $Payin = json_decode(curl_exec($curl));
        curl_close($curl);





        //XXXXXXXXXXXXXXXX-EXECUTION DES METHODES-XXXXXXXXXXXXXXXXXXXXXXX

        /*
        En cas de reclamation ou de besoin de correction ou verrification d'une transaction,
        vous pouvez rappeler la transaction en recuperant le token par session ou depuis votre DB ou par variable $_GET['token']
        Raison pour laquel vous devez stocker le 'invoiceToken=' de votre transaction client dans votre base de données historique transaction ou en variable SESSION
        On suppose que le 'invoiceToken=' est recuperé par exemple
        */
        //echo $_GET['token'];
        //$invoiceToken=$_GET['token'];


        $invoiceToken=$_SESSION['invoiceToken'];
        //$idcompte=$_SESSION['idForum'];
        $user=$_SESSION['idUser'];
        $montant=$_SESSION['montant'];

        $transaction_id=$_SESSION['transaction_id'];

        //$participant = \App\Participants::participant($idparticipant);



        if(isset($Payin)) {
            if(trim($Payin->status)=="completed") {
                echo "Le client(Payer) a validé le paiement vous devez executé vos traitements apres paiement valide<br><br>";
                //print_r($Payin);
                /*$from_data = array(
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
                );*/
                //\App\Transaction::create($from_data);

                \Session::flash('success','Paiement effectué avec succès !!!');
                /*$transaction = auth()->user()->transactions()->create([
                    'numtrans'=>$transaction_id,
                    'montant'=>$montant,
                ]);*/


                return redirect()->route('dashboard');
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

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

function payin(){
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
                                        "name": "PAIEMENT DE DONS",
                                        "description": "Une noble donation à l\'endroit des déplacés internes du Burkina ",
                                        "quantity": 1,
                                        "unit_price": "'.$montant.'",
                                        "total_price": "'.$montant.'"
                                      }
                                    ],
                                    "total_amount": "'.$montant.'",
                                    "devise": "XOF",
                                    "description": "PAIEMENT DES FRAIS",
                                    "customer": "",
                                    "customer_firstname":"'.$nom_aut.'",
                                    "customer_lastname":"'.$prenom_aut.'",
                                    "customer_email":"'.$email_aut.'"
                                  },
                                  "store": {
                                    "name": "DONS",
                                    "website_url": "https://dons.test/"
                                  },
                                  "actions": {
                                    "cancel_url": "https:localhost/dons/pay/cancel.php",
                                     "return_url": "https:localhost/dons/pay/status_payin_php_cURL.php",
                                    "callback_url": "https:localhost/dons/pay/status_payin_php_cURL.php"
                                  },
                                  "custom_data": {
                                    "transaction_id": "'.$transaction_id.'",
                                    "iddonateur": "'.$id_auth.'"
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

          //dd($response);
          $response = json_decode(curl_exec($curl));
          curl_close($curl);
          if(isset($response->response_code) and $response->response_code=="00") {
            //$redirectPayin->response_text contient l'url de redirection
            header('Location: '.$response->response_text);
            print_r($response->response_code);exit;
        }
        else{
            echo 'response_code='.$response->response_code;
            echo '<br><br>';
            echo 'response_text='.$response->response_text;
            echo '<br><br>';
            echo 'description='.$response->description;
            echo '<br><br>';
            echo '<br><br>Veuillez lire la documentation et le WIKI subcodes[]';
            exit;
        }

}
