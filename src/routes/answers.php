<?php  

use Slim\Http\Request;

use Slim\Http\Response;



//AGREGAR RESPUESTA POR DEFECTO 

$app->post('/add-default-answers', function (Request $request, Response $response, array $args){

  try{

    

    $datos = $request->getParsedBody();

    

    $answer = new DefaultAnswers();



    $answer->id_questions = $datos['id_questions'];

    $answer->answers = $datos['answers'];

    $answer->status = 1;

     

    

    $answer->save();



    if($answer){

      return $response->withJson(array('success' => true), 200);

    }else{

        return $response->withJson(array('success' => false), 200);

    }    



  }catch(\Exception $ex){

    return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});



// MODIFICAR RESPUESTA POR DEFECTO 

$app->put('/edit-default-answers', function (Request $request, Response $response, array $args){

  try{



    $datos = $request->getParsedBody();



    $answer = DefaultAnswers::find($datos['id_default_answers']);



    $answer->answers = $datos['answers'];



    $answer->save();

    

    if($answer){

      return $response->withJson(array('success' => true), 200);

    }else{

      return $response->withJson(array('success' => false), 200);

    }   



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});







// BORRAR RESPUESTA POR DEFECTO 

$app->delete('/delete-default-answers/{id}', function (Request $request, Response $response, array $args){

  try{

    $answer = DefaultAnswers::where('id_default_answers','=',$args['id'])->delete();

    if($answer){

      return $response->withJson(array('success' => true), 200);

    }else{

      return $response->withJson(array('success' => false), 200);

    }    

  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});






// AGREGAR RESPUESTA POR EL USUARIO 



$app->post('/add-answers-user', function (Request $request, Response $response, array $args){

  try{

    

    $datos = $request->getParsedBody();

    

    $answer = new AnswersUser();



    $answer->id_accounts = $datos['id_accounts'];

    $answer->id_step = $datos['id_step'];

    $answer->id_questions = $datos['id_questions'];

    $answer->answers = $datos['answers'];

     

    

    $answer->save();



  if($answer){

    	return $response->withJson(array('success' => true), 200);

	}else{

	    return $response->withJson(array('success' => false), 200);

	}    



  }catch(\Exception $ex){

    return $response->withJson(array('error' => $ex->getMessage()),422);

  }

});







//  PREGUNTAS Y RESPUESTAS DEL USUARIO POR PASOS



$app->get('/all-answers/{account}/{step}', function (Request $request, Response $response, array $args){

  try{



  	$answer = AnswersUser::join('questions', 'answers_steps.id_questions', '=', 'questions.id_questions')

  			->where('answers_steps.id_accounts',$args['account'])

  			->where('answers_steps.id_step',$args['step'])

            ->select('answers_steps.id_answers_steps', 'answers_steps.id_accounts', 'answers_steps.id_step', 'questions.id_questions', 'questions.questions', 'answers_steps.answers', 'answers_steps.date')

            ->orderBy('questions.id_questions', 'ASC')

            ->get();



    return sendOkResponse($answer->toJson(),$response);



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }



});





// RESUMEN DE TODAS LAS RESPUESTAS DEL USUARIO

$app->get('/resume-answers/{account}', function (Request $request, Response $response, array $args){

  try{



  	$answer = AnswersUser::join('questions', 'answers_steps.id_questions', '=', 'questions.id_questions')

  			->where('answers_steps.id_accounts',$args['account'])

            ->select('answers_steps.id_answers_steps', 'answers_steps.id_accounts', 'answers_steps.id_step', 'questions.id_questions', 'questions.questions', 'answers_steps.answers', 'answers_steps.date')

            ->orderBy('answers_steps.id_step', 'ASC')

            ->get();



   return sendOkResponse($answer->toJson(),$response);



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }



});





// PREGUNTAS Y RESPUESTAS (DEFAULT) POR ETAPAS

$app->get('/answers-question/{step}', function (Request $request, Response $response, array $args){

  try{



  $questions = Questions::where('step','=',$args['step'])->orderBy('id_questions', 'ASC')->get();

  $answers =  DefaultAnswers::orderBy('id_questions', 'ASC')->get();

  $answers_question = [];
  foreach ($questions as $qkey => $q){
    $answ = [];
    foreach ($answers as $akey => $a) {
      if($q['id_questions'] == $a['id_questions']){
        $answ[$akey] = $a['answers'];  
      }
    }
    // print_r($questions);
    // echo $qkey."<br>";
    $answers_question[$qkey] = array(
                                'id_questions' => $q['id_questions'],
                                'questions' => $q['questions'],
                                'answers' => $answ,
                                'type_question' => $q['type_question'],
                                'step' => $q['step'],
                                'parent' => $q['parent']
                              );
  }


   return $response->withJson(array($answers_question), 200);



  }catch(\Exception $ex){

     return $response->withJson(array('error' => $ex->getMessage()),422);

  }



});





?>



