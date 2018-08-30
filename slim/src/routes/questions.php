<?php  
use Slim\Http\Request;
use Slim\Http\Response;

// AGREGAR NUEVA PREGUNTA
$app->post('/add-question', function (Request $request, Response $response, array $args){
  try{
    
    $datos = $request->getParsedBody();
    $question = new Questions();
    $question->questions = $datos['questions'];
    $question->type_question = $datos['type_question']; //tipo 1 open, 2 multiple, 3 dependiente
    $question->step = $datos['step']; 
    $question->parent = (empty($datos['parent'])?0:$datos['parent']); // 0 no tiene padre
    $question->status = 1; // 1 on 2 off
    $question->save();

    return $response->withJson(array('success' => true, $question), 200);

  }catch(\Exception $ex){
    return $response->withJson(array('error' => $ex->getMessage()),422);
  }
});


// EDITAR PREGUNTA
$app->put('/edit-question', function (Request $request, Response $response, array $args){
  try{

    $datos = $request->getParsedBody();
    
    $questions = Questions::find($datos['id_question']);
    $questions->questions = $datos['questions'];
    $questions->type_question = $datos['type_question']; //tipo 1 open, 2 close, 3 dependiente, 4 multiple
    $questions->parent = (empty($datos['parent'])?0:$datos['parent']); // 0 no tiene padre

    $questions->save();

    if($questions){
      return $response->withJson(array('success' => true), 200);
    }else{
      return $response->withJson(array('success' => false), 200);
    }  

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }
});


// BORRAR UNA PREGUNTA
$app->delete('/delete-question/{id}', function (Request $request, Response $response, array $args){
  try{
    $questions = Questions::where('id_questions','=',$args['id'])->delete();
    if($questions){
      return $response->withJson(array('success' => true), 200);
    }else{
      return $response->withJson(array('success' => false), 200);
    }    
  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }
});





// PREGUNTAS POR PASO
$app->get('/question_step/{id}', function (Request $request, Response $response, array $args){
  try{

  	$questions = Questions::where('step','=',$args['id'])->get();
    return sendOkResponse($questions->toJson(),$response);

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }

});


// TRAER TODAS LAS PREGUNTAS
$app->get('/all-question', function (Request $request, Response $response, array $args){
  try{

    $questions = Questions::orderBy('step', 'ASC')->
                            orderBy('id_questions', 'ASC')->get();

    return sendOkResponse($questions->toJson(),$response);

  }catch(\Exception $ex){
     return $response->withJson(array('error' => $ex->getMessage()),422);
  }

});


?>