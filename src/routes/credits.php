<?php  
use Slim\Http\Request;
use Slim\Http\Response;

// TODOS LOS CREDITOS PENDIENTES


// TODOS LOS CREDITOS APROBADOS


// TODOS LOS CREDITOS FINALIZADOS 


// TODOS LOS CREDITOS NEGADOS



// CREAR PRESTAMO

$app->post('/create-credit', function (Request $request, Response $response, array $args){

  try{
     
    $datos = $request->getParsedBody();

    $valAccount = Credit::where('id_accounts','=',$datos['id_accounts'])->orderBy('date', 'DESC')->first();

    function expiredDate($date){
        $date2 = new DateTime(date("Y-m-d H:i:s")); // hoy
        $dateEnd = $date2->diff($date);
        return $dateEnd;   
    }

    $date = new DateTime(date($valAccount["date"]));

    $expired = expiredDate($date);
    // credito x dia || primer credito
    if($expired->d > 0 || count($valAccount) == 0){

      $penalties = AccountPenalties::get();

      // print_r($penalties);

      foreach ($penalties as $key => $value) {
        if($value->id_accounts == $datos['id_accounts']){
          return $response->withJson(array('success' => false, 'penaltie' => $value->id_penalties), 200);
        }
      }

      $credits = new Credit();

      $credits->id_accounts = $datos['id_accounts'];

      $credits->amount = $datos['amount'];

      $credits->save();

      return $response->withJson(array('success' => true, 'id_credits' => $credits->id_credits), 200);
        
    }else{
        return $response->withJson(array('success' => false), 200);
    }



  }catch(\Exception $ex){

    return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});



// ESTADO DEL PRESTAMO
// -- 1 PROCESO, 2 APROBADO,3 ACEPTADO, 4 REALIZADO, 5 FINALIZADO, 6 DENEGADO 
$app->get('/credit-status/{id}', function (Request $request, Response $response, array $args){
  try{

  $credit = Credit::where('id_accounts','=',$args['id'])->select('status')->orderBy('date', 'DESC')->first();

  if(count($credit) > 0){
    return $response->withJson($credit, 200);
  }else{
    return $response->withJson(array('success' => false), 200); 
  } 

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }

});


// INFORMACIÓN GENERAL DEL PRESTAMO
$app->get('/credit-information/{id}', function (Request $request, Response $response, array $args){
  try{

  $credit = Credit::where('id_accounts','=',$args['id'])->orderBy('date', 'DESC')->first();

  if(count($credit) > 0){
    return $response->withJson($credit, 200);
  }else{
    return $response->withJson(array('success' => false), 200); 
  } 

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }

});


// ACEPTAR PRESTAMO
$app->put('/accept-credit', function (Request $request, Response $response, array $args){
  try{
    
    $datos = $request->getParsedBody(); 

    $credit = Credit::find($datos['id_credits']);

    $credit->status = 2;
    
    if($credit){
      
      $credit->save();

      return $response->withJson(array('success' => true), 200);
    
    }else{
      return $response->withJson(array('success' => false), 200);
    }   

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }
});



// 
$app->get('/credit-detail/{id}', function (Request $request, Response $response, array $args){
  try{

  $credit = Credit::where('id_accounts','=',$args['id'])->orderBy('date', 'DESC')->first();

  $period = Periods::get();

  $account_pay = AccountsPay::where('id_accounts','=',$args['id'])->get();

  $amount = $credit['amount'];

  $interest = array();

  $total_amount = array();

  $quota = array();

  $monthly = array();

  $today =  date("Y-m-d H:i:s");

  $date_pay = array();

  $date = array();

  foreach ($period as $key => $value) {
    // interest 
    $interest[$key] = ($value->interest / 100);
    
    // total 
    $total_amount[$key] = ($credit['amount'] * $interest[$key])+ $credit['amount'];

    // quota
    $quota[$key] = $total_amount[$key] / $value->pay;

    // monthly
    $monthly[$key] = number_format((($quota[$key] / $total_amount[$key]) * 2), 2, '.', ''); 

    // dates
    $date_count = $today;

    for ($i=0; $i < $value->pay; $i++) { 
      $date_count = date('Y-m-d', strtotime($date_count. ' + '.($value->period -1).' days'));
      $date_pay[$i] = $date_count;
    }

    $date[$key] = $date_pay;
  }

  if(count($credit) > 0){
    return $response->withJson(array($credit,$account_pay,$interest, $total_amount, $quota, $monthly,$date), 200);
  }else{
    return $response->withJson(array('success' => false), 200); 
  } 



  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }

});


// APLICAR A PRESTAMO
$app->put('/apply-credit', function (Request $request, Response $response, array $args){
  try{
    // UPDATE ESTADO DEL CREDITO
    
    $datos = $request->getParsedBody(); 

    $valCredit = Credit::where('id_credits','=',$datos['id_credits'])->first();

    $valAccount = Credit::where('id_accounts','=',$valCredit['id_accounts'])->orderBy('date', 'DESC')->first();

    function expiredDate($date){
        $date2 = new DateTime(date("Y-m-d H:i:s")); // hoy
        $dateEnd = $date2->diff($date);
        return $dateEnd;   
    }

    $date = new DateTime(date($valAccount["date"]));

    $expired = expiredDate($date);
    
    if($valAccount["status"] == 4){ //&& $expired->d > 0

      $credit = Credit::find($datos['id_credits']);
      $credit->status = 4;
      $credit->save();
      
      // CREDITO APLICADO 
      $credit_detail = new CreditDetail();
      $credit_detail->id_credits = $datos['id_credits'];
      $credit_detail->id_accounts_pay = $datos['id_accounts_pay'];
      $credit_detail->periods_payment = $datos['periods_payment'];  
      $credit_detail->save();

      // FECHAS DE PAGO
      $period = Periods::where('id_periods_payment','=',$datos['periods_payment'])->first();
      $today =  date("Y-m-d H:i:s");
      $date_pay = array();
      $date = array();
      $date_count = $today;

      // CUOTA
      $amount = Credit::where('id_credits','=',$datos['id_credits'])->first();
      
      for ($i=0; $i < $period['pay']; $i++) { 
        $date_count = date('Y-m-d', strtotime($date_count. ' + '.($period['period'] -1).' days'));
        $date_pay[$i] = $date_count;

        $credit_detail = new CreditPaymentDate();
        $credit_detail->id_credits = $datos['id_credits'];
        $credit_detail->date = $date_pay[$i];
        $credit_detail->amount = (($amount->amount * ($period->interest / 100)) + $amount->amount) / $period['pay']; // <- aqui ajustar monto / pagos = cuota
        $credit_detail->save();        
      }

        return $response->withJson(array('success' => true, 'id_credits' => $datos['id_credits']), 200);

    }else{
        return $response->withJson(array('success' => false), 200);
    }

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }
});



// PROXIMO PAGO - MONTO RESTANTE
$app->post('/next-payment', function (Request $request, Response $response, array $args){

  try{
     
    $datos = $request->getParsedBody();

    $next =  CreditPaymentDate::join('credits','credits.id_credits','=','credit_payment_date.id_credits')
                              ->join('accounts','accounts.id_accounts','=','credits.id_accounts')
                              ->where('credit_payment_date.status','<','2')
                              ->where('credits.status','=','4')
                              ->where('accounts.phone','=', $datos['phone'])
                              ->select('credit_payment_date.*','accounts.id_accounts')
                              ->orderBy('credit_payment_date.id_credit_payment_date', 'ASC')->get();

    $total = collect($next)->sum('amount');

    if($total > 0){
      return $response->withJson(array(
        'id_credits' => $next[0]->id_credits,
        'id_accounts' => $next[0]->id_accounts,
        'next_payment' => $next[0]->date,
        'amount' => $next[0]->amount,
        'overdue_amount' => $next[0]->overdue_amount,
        'payment status' => $next[0]->status,
        'total_pending' => $total, 
        'success' => true
      ), 200);
    }else{
      return $response->withJson(array('success' => false), 200);
    }
    

  }catch(\Exception $ex){

    return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});




// PERIOD - TERM - INACTIVITY

$app->post('/add-term-interest', function (Request $request, Response $response, array $args){

  try{
    $datos = $request->getParsedBody();

    $period = new Periods();

    $period->period = $datos['period'];
    $period->interest = $datos['interest'];
    $period->pay = $datos['pay'];
    $period->arrears = $datos['arrears'];
    $period->inactivity = $datos['inactivity'];

    $period->save();

    if($period){
        return $response->withJson(array('success' => true), 200);
    }else{
        return $response->withJson(array('success' => false), 200);
    }



  }catch(\Exception $ex){

    return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});


$app->put('/edit-term-interest', function (Request $request, Response $response, array $args){

  try{
    
    $datos = $request->getParsedBody();

    $period = Periods::find($datos['id_periods_payment']);

    if (!empty($datos['period'])){
      $period->period = $datos['period'];
    }

    if (!empty($datos['interest'])){
      $period->interest = $datos['interest'];
    }

    if (!empty($datos['interest_delay'])){
      $period->interest = $datos['interest_delay'];
    }

    if (!empty($datos['pay'])){
      $period->pay = $datos['pay'];
    }

    if (!empty($datos['arrears'])){
      $period->arrears = $datos['arrears'];
    }

    if (!empty($datos['inactivity'])){
      $period->inactivity = $datos['inactivity'];
    }

    $period->save();
   

    if($period){
      return $response->withJson(array('success' => true), 200);
    }else{
      return $response->withJson(array('success' => false), 200);
    }  

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }



});


$app->delete('/delete-term-interest/{id}', function (Request $request, Response $response, array $args){

  try{
    //Delete book identified by $id
    $ranking = Periods::where('id_periods_payment','=',$args['id'])->delete();

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