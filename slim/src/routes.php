<?php

// Routes
function sendOkResponse($message,$response){
	$newResponse = $response->withStatus(200)->withHeader('Content-type','application/json');
	$newResponse->getBody()->write($message);
	return $newResponse;
}

require __DIR__ . '/routes/login.php';

require __DIR__ . '/routes/dashboard.php';

require __DIR__ . '/routes/questions.php';

require __DIR__ . '/routes/steps.php';

require __DIR__ . '/routes/answers.php';

require __DIR__ . '/routes/accounts.php';

require __DIR__ . '/routes/phonedata.php';

require __DIR__ . '/routes/credits.php';
