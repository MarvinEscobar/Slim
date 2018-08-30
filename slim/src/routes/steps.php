<?php  
use Slim\Http\Request;
use Slim\Http\Response;

// AGREGAR NUEVO STEP
$app->post('/add-step', function (Request $request, Response $response, array $args){
  try{
    
    $datos = $request->getParsedBody();
    
    $steps = new Steps();
    $steps->name_step = $datos['name_step'];
    $steps->status = 1;
    $steps->save();

    if($steps){
      return $response->withJson(array('success' => true), 200);
    }else{
        return $response->withJson(array('success' => false), 200);
    }    

  }catch(\Exception $ex){
    return $response->withJson(array('error' => $ex->getMessage()),422);
  }
});


// TRAER TODOS LOS PASOS
$app->get('/all-steps', function (Request $request, Response $response, array $args){
  try{

  	$steps = Steps::get();

    return sendOkResponse($steps->toJson(),$response);

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }

});

// MODIFICAR NOMBRE STEP
$app->put('/edit-steps/{id}/{name_step}', function (Request $request, Response $response, array $args){
  try{
    
    $datos = $request->getParsedBody();

    $steps = Steps::find($args['id']);
    
    $steps->name_step = $args['name_step'];

    $steps->save();
    
    if($steps){
      return $response->withJson(array('success' => true), 200);
    }else{
      return $response->withJson(array('success' => false), 200);
    }   

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }
});


// BORRAR UN STEP
$app->delete('/delete-step/{id}', function (Request $request, Response $response, array $args){
  try{
    $steps = Steps::where('id_step','=',$args['id'])->delete();
    if($steps){
      return $response->withJson(array('success' => true), 200);
    }else{
      return $response->withJson(array('success' => false), 200);
    }    
  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }
});



?>