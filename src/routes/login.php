<?php 



use Slim\Http\Request;

use Slim\Http\Response;



// INSERT



$app->post('/add-user', function (Request $request, Response $response, array $args){

  try{

    

    $datos = $request->getParsedBody();

    $users = new Users();

    $users->full_name = $datos['full_name'];

    $users->level = $datos['level'];

    $users->email = $datos['email'];

    $users->username = $datos['username'];

    $users->password = md5($datos['password']);

    $users->phone = $datos['phone'];



    $users->save();



    return $response->withJson(array('success' => true, 'last_insert_id' => $users->id_user), 200);



  }catch(\Exception $ex){

    return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});



// SELECT



 



// UPDATE



$app->put('/edit-user/{id}', function (Request $request, Response $response, array $args){

  try{

    

    $datos = $request->getParsedBody();

    $users = Users::find($args['id']);

    $users->password = md5($datos['password']);



    $users->save();

    

    if($users){

      return $response->withJson(array('success' => true), 200);

    }else{

      return $response->withJson(array('success' => false), 200);

    }    



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});



// DELETE



$app->delete('/delete-user/{id}', function (Request $request, Response $response, array $args){

  try{

    //Delete book identified by $id



    $users = Users::where('id_user','=',$args['id'])->delete();



    if($users){

      return $response->withJson(array('success' => true), 200);

    }else{

      return $response->withJson(array('success' => false), 200);

    }    



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});





// validacion 



 // print_r($users->toJson());



$app->post('/login-validation', function (Request $request, Response $response, array $args){

  try{

    

    $datos = $request->getParsedBody();

    

    $users = Users::where('username',$datos['username'])->where('password',md5($datos['password']))->get();

    

    if(count($users)){

      return sendOkResponse($users->toJson(),$response);    

    }else{

      return $response->withJson(array('success' => 'Unauthorized'), 200);

    }     



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }



});







?>