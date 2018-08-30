<?php 



use Slim\Http\Request;

use Slim\Http\Response;



use Twilio\Rest\Client;


date_default_timezone_set('America/El_Salvador');


// VALIDAR EL NUMERO DE TELEFONO CON TWILIO POR SMS

$app->put('/validation-twl-sms/', function (Request $request, Response $response, array $args){

  try{


    // CAPTURAR EL TELEFONO Accounts. ConfirmCode.

    $datos = $request->getParsedBody();

    $confirmCode = ConfirmCode::get();



    // pin random

    $pin = rand(1000,9999 );



    $action = true;



    $id_code = null;



    foreach ($confirmCode as $key => $value) {

      if($value->phone == $datos['phone']){

        $action = false;

        $id_code = $value->id_confirm_code;

      }

    }



    if($action == true){

      // insert code

        $confirmCode = new ConfirmCode();

        $confirmCode->phone = $datos['phone'];

        $confirmCode->pin = $pin;

    }else{

      // update code      

        while ($datos['phone'] == $pin) {

          $pin = rand(10000, 99999);

        }

        $confirmCode = ConfirmCode::find($id_code);

        $confirmCode->pin = $pin;

    }



    $confirmCode->save();



    // Your Account Sid and Auth Token from twilio.com/user/account

    $account_sid = "ACee1e8f185c486e6a7e10b5697890a5c4";

    $auth_token = "008c6b4c7a483c6ad2064406730dbaa4";

    $twilio_phone_number = "+18084003739";

    $to_number = $datos['phone'];

    $client = new Client($account_sid, $auth_token);



    try {

        $client->messages->create(

          $to_number,

            array(

                "from" => $twilio_phone_number,

                "body" => $pin." es tu codigo de verificacion de Diimo."

            )

        );



        return $response->withJson(array('success' => true), 200);



    } catch ( \Services_Twilio_RestException $e ) {

        elog( 'EACT', $e->getMessage(  ) , __FUNCTION__ );  

    }





    return $response->withJson(array('success' => true), 200);



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }



});



// VALIDAR EL NUMERO DE TELEFONO CON TWILIO  POR LLAMADA

$app->put('/validation-twl-call/', function (Request $request, Response $response, array $args){

  try{

    

    // CAPTURAR EL TELEFONO Accounts. ConfirmCode.

    $datos = $request->getParsedBody();



    $confirmCode = ConfirmCode::get();



    // pin random

    $pin = rand(1000,9999 );



    $action = true;



    $id_code = null;



    foreach ($confirmCode as $key => $value) {

      if($value->phone == $datos['phone']){

        $action = false;

        $id_code = $value->id_confirm_code;

      }

    }



    if($action == true){

      // insert code

        $confirmCode = new ConfirmCode();

        $confirmCode->phone = $datos['phone'];

        $confirmCode->pin = $pin;

    }else{

      // update code      

        while ($datos['phone'] == $pin) {

          $pin = rand(1000,9999 );

        }

        $confirmCode = ConfirmCode::find($id_code);

        $confirmCode->pin = $pin;

    }



    $confirmCode->save();



    // Your Account Sid and Auth Token from twilio.com/user/account

    $account_sid = "ACee1e8f185c486e6a7e10b5697890a5c4";

    $auth_token = "008c6b4c7a483c6ad2064406730dbaa4";

    $twilio_phone_number = "+18084003739";

    $to_number ="+50370848731";

    $client = new Client($account_sid, $auth_token);



    try {

        $client->account->calls->create(  

            $to_number,

            $twilio_phone_number,

            array(

                "url" => "http://toolboxsv.com/dev/diimo/diimo.xml"

            )

        );



    } catch ( \Services_Twilio_RestException $e ) {

        elog( 'EACT', $e->getMessage(  ) , __FUNCTION__ );  

    }

    return $response->withJson(array('success' => true), 200);

  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }



});





$app->post('/validation-code', function (Request $request, Response $response, array $args){

  try{

    

    $datos = $request->getParsedBody();



    $confirmCode = ConfirmCode::get();

    

    $action = false;



    $phone = null;



    foreach ($confirmCode as $key => $value) {

      if($value->pin == $datos['code']){

        $action = true;

        $phone = $value->phone;

       }

    }



    if($action == true){

        return $response->withJson(array('success' => true, 'phone' => $phone), 200);

    }else{

        return $response->withJson(array('success' => false), 422);

    }



  }catch(\Exception $ex){

    return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});



// AGREGAR CUENTA



$app->post('/add-account', function (Request $request, Response $response, array $args){

  try{



    function randomCodeInvitation($longitud) {

      $key = '';

      $pattern = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';

      $max = strlen($pattern)-1;

      

      for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};

        return $key;

    }



    $codeInvitation = randomCodeInvitation(8);

    $account = Accounts::where('invitation_code', $codeInvitation)->get();



    while(count($account) == 1){

      $codeInvitation = randomCodeInvitation(8);

      $account = Accounts::where('invitation_code', $codeInvitation)->get();

    }



    $datos = $request->getParsedBody();

    

    $account = new Accounts();

    

    $account->dui = $datos['dui'];

    $account->nit = $datos['nit'];

    $account->first_name = $datos['first_name'];

    $account->last_name = $datos['last_name'];

    $account->email = $datos['email'];

    $account->phone = $datos['phone'];

    $account->backup_phone = $datos['backup_phone'];

    $account->country = $datos['country'];

    $account->department = $datos['department'];

    $account->city = $datos['city'];

    $account->address = $datos['address'];

    $account->date_birth = $datos['date_birth'];

    $account->gender = $datos['gender'];

    $account->notifications = $datos['notifications'];

    $account->invitation_code = $codeInvitation;

    $account->ranking = $datos['ranking'];

    $account->access_information = $datos['access_information'];



    $account->save();



    if($account){

        return $response->withJson(array('success' => true, 'id_accounts' => $account->id_accounts), 200);

    }else{

        return $response->withJson(array('success' => false), 200);

    }



  }catch(\Exception $ex){

    return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});



// EDITAR CUENTA

$app->put('/edit-account', function (Request $request, Response $response, array $args){

  try{

        

    $datos = $request->getParsedBody();



    $account = Accounts::find($datos['id_accounts']);



    if (!empty($datos['phone'])){

      $account->phone = $datos['phone'];

    }



    if (!empty($datos['first_name'])){

      $account->first_name = $datos['first_name'];

    }



    if (!empty($datos['last_name'])){

      $account->last_name = $datos['last_name'];

    }

    if (!empty($datos['dui'])){

      $account->dui = $datos['dui'];

    }



    if (!empty($datos['date_birth'])){

      $account->date_birth = $datos['date_birth'];

    }



    if (!empty($datos['notifications'])){

      $account->notifications = $datos['notifications'];

    }



    if (!empty($datos['access_information'])){

      $account->access_information = $datos['access_information'];

    }



    $account->save();

    

    if($account){

      return $response->withJson(array('success' => true), 200);

    }else{

      return $response->withJson(array('success' => false), 200);

    }  



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }



});





// AGREGAR PIN

$app->post('/add-secure-code', function (Request $request, Response $response, array $args){

  try{

    

    $datos = $request->getParsedBody();

    

    $account = new SecureCode();

    

    $account->id_accounts = $datos['id_accounts'];

    $account->pin = $datos['pin'];



    $account->save();



    if($account){

        return $response->withJson(array('success' => true), 200);

    }else{

        return $response->withJson(array('success' => false), 200);

    }



  }catch(\Exception $ex){

    return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});



// EDITAR PIN

$app->put('/edit-secure-code', function (Request $request, Response $response, array $args){

  try{

        

    $datos = $request->getParsedBody();



    $account = SecureCode::find($datos['id_accounts']);



    $account->pin = $datos['pin'];



    $account->save();



    if($account){

      return $response->withJson(array('success' => true), 200);

    }else{

      return $response->withJson(array('success' => false), 200);

    }  



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }



});





// INICIO DE SESSION APP DIIMO

$app->put('/login-app-validation', function (Request $request, Response $response, array $args){

  try{

    

    $datos = $request->getParsedBody();

    

    $account = Accounts::join('security_code', 'accounts.id_accounts', '=', 'security_code.id_accounts')

        ->where('accounts.phone',$datos['phone'])

        ->where('security_code.pin',$datos['pin'])

            ->select('accounts.id_accounts')

            ->first();

    if($account->id_accounts){

      $session = AccountSession::where('id_accounts',$account->id_accounts)->first();

      // print_r($session);

      if($session){
        
        $session = AccountSession::find($session->id_account_session);

        $session->date = new DateTime(date("Y-m-d H:i:s"));

      }else{

        $session = new AccountSession();

        $session->id_accounts = $account->id_accounts;
        
        $session->date = new DateTime(date("Y-m-d H:i:s"));
      }

      $session->save();

      return sendOkResponse($account->toJson(),$response);    

    }else{

      return $response->withJson(array('success' => 'Unauthorized'), 200);

    }     



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }



});







// INFORMACION HOME / MEJORAR

$app->post('/home-information', function (Request $request, Response $response, array $args){

  try{

    

    $datos = $request->getParsedBody();



    $credits = Credits::where('id_accounts',$datos['id_accounts'])->first();



    $homeInformation = [];



    $status = null;



    if(!$credits->id_accounts){

        // aplicar a primer credito

      echo "primer credito";

      

      $homeInformation = Accounts::where('id_accounts',$datos['id_accounts'])

                                  ->select('id_accounts','ranking','invitation_code')

                                  ->first();



      $status ="primer credito";



    }else if($credits->status == 1){

       // prestamo en proceso

        $homeInformation = Accounts::where('id_accounts',$datos['id_accounts'])

                                  ->select('id_accounts','ranking','invitation_code')

                                  ->first();



      $status ="credito en proceso";



    }else if($credits->status == 2){

      // prestamo aprobado

      $homeInformation = Accounts::join('credits','accounts.id_accounts','=','credits.id_accounts')

                                  ->join('periods_payment','credits.periods_payment','=','periods_payment.id_periods_payment')

                                  ->where('accounts.id_accounts', '=', '2')

                                  ->select('accounts.id_accounts', 'accounts.ranking', 'accounts.invitation_code', 'credits.amount', 'periods_payment.interest' , 'periods_payment.period','credits.dates_payment')

                                  ->first();

        // fecha de pago

      $status ="credito aprobado";







    }else if($credits->status == 3){

        // prestamo finalizado

      $homeInformation = Accounts::where('id_accounts',$datos['id_accounts'])

                                  ->select('id_accounts','ranking','invitation_code')

                                  ->first();



      $status ="credito finaliazo";

    }



    

    if($homeInformation == true){

        return $response->withJson(array('success' => true, $homeInformation, $status), 200);

    }else{

        return $response->withJson(array('success' => false), 422);

    }







  }catch(\Exception $ex){

    return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});



// EDITAR CUENTA

$app->put('/edit-status-account', function (Request $request, Response $response, array $args){

  try{

        

    $datos = $request->getParsedBody();



    $account = Accounts::find($datos['id_accounts']);



    $account->status = $datos['status']; 



    $account->comment = $datos['comment'];



    $account->save();

    

    if($account){

      return $response->withJson(array('success' => true), 200);

    }else{

      return $response->withJson(array('success' => false), 200);

    }  



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }



});

          

// ESTADO DE LA CUENTA NIVEL ORO PLATA PLATINO
$app->get('/account-status/{id}', function (Request $request, Response $response, array $args){
  try{

  $account = Accounts::where('id_accounts','=',$args['id'])->select('ranking')->first();

  $ranking = Ranking::where('id_ranking','=',$account->ranking)->first();

  if(count($account) > 0){
    return $response->withJson(array($account, $ranking), 200);
  }else{
    return $response->withJson(array('success' => false), 200); 
  } 

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }

});






// RANKING

$app->post('/add_ranking', function (Request $request, Response $response, array $args){

  try{
    $datos = $request->getParsedBody();

    $ranking = new Ranking();

    $ranking->status_name = $datos['status_name'];
    $ranking->amount_min = $datos['amount_min'];
    $ranking->amount_max = $datos['amount_max'];
    $ranking->next_lvl_amount = $datos['next_lvl_amount'];
    $ranking->next_lvl_pay = $datos['next_lvl_pay'];

    $ranking->save();

    if($ranking){
        return $response->withJson(array('success' => true), 200);
    }else{
        return $response->withJson(array('success' => false), 200);
    }



  }catch(\Exception $ex){

    return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});


$app->put('/edit-ranking', function (Request $request, Response $response, array $args){

  try{
    
    $datos = $request->getParsedBody();

    $ranking = Ranking::find($datos['id_ranking']);

    if (!empty($datos['status_name'])){
      $ranking->status_name = $datos['status_name'];
    }

    if (!empty($datos['amount_min'])){
      $ranking->amount_min = $datos['amount_min'];
    }

    if (!empty($datos['amount_max'])){
      $ranking->amount_max = $datos['amount_max'];
    }

    if (!empty($datos['next_lvl_amount'])){
      $ranking->next_lvl_amount = $datos['next_lvl_amount'];
    }

    if (!empty($datos['next_lvl_pay'])){
      $ranking->next_lvl_pay = $datos['next_lvl_pay'];
    }

    $ranking->save();
   

    if($ranking){
      return $response->withJson(array('success' => true), 200);
    }else{
      return $response->withJson(array('success' => false), 200);
    }  

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }



});


$app->delete('/delete-ranking/{id}', function (Request $request, Response $response, array $args){

  try{
    //Delete book identified by $id
    $ranking = Ranking::where('id_ranking','=',$args['id'])->delete();

    if($ranking){
      return $response->withJson(array('success' => true), 200);
    }else{
      return $response->withJson(array('success' => false), 200);
    }    

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }

});



?>
















