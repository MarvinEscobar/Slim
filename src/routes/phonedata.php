<?php  
use Slim\Http\Request;
use Slim\Http\Response;

// AGREGAR NUEVA DATA
$app->post('/add-phone-data', function (Request $request, Response $response, array $args){
  try{
    
    $datos = $request->getParsedBody();
    
    $phonedata = new PhoneData();
    $phonedata->id_accounts = $datos['id_accounts'];
    $phonedata->log_calls = $datos['log_calls'];
    $phonedata->log_sms = $datos['log_sms'];
    $phonedata->log_location = $datos['log_location'];
    $phonedata->log_apps = $datos['log_apps'];

    $phonedata->save();

    if($phonedata){
      return $response->withJson(array('success' => true), 200);
    }else{
        return $response->withJson(array('success' => false), 200);
    }    

  }catch(\Exception $ex){
    return $response->withJson(array('error' => $ex->getMessage()),422);
  }
});


// TRAER TODOS LOS LOGS DE UN USUARIO
$app->get('/phone-data/{id}', function (Request $request, Response $response, array $args){
  try{

    $phonedata = PhoneData::where('id_accounts','=',$args['id'])->get();
    
    if($phonedata){
      return $response->withJson(array('success' => true, $phonedata), 200);
    }else{
      return $response->withJson(array('success' => false), 200);
    }
    

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }

});




?>