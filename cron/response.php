<?php 

require __DIR__ . '/twilio-php-master/Twilio/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

// Your Account Sid and Auth Token from twilio.com/user/account
$account_sid = "ACee1e8f185c486e6a7e10b5697890a5c4";
$auth_token = "008c6b4c7a483c6ad2064406730dbaa4";

$twilio_phone_number = "+18084003739";

$mysqli = new mysqli('96.30.42.83', 'toolbox_diimou', 'ENnHY{HX3MCO', 'toolbox_diimov1');

// verificamos que sea correcta 
if ($mysqli->connect_error) {
    die('Error de Conexión (' . $mysqli->connect_errno . ') '
        . $mysqli->connect_error);
}else{
    // echo 'Connected successfully';
}

$sql = "select id_accounts, first_name, last_name, phone, DATEDIFF(curdate(), date) as dias, status,comment from accounts";

$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
         if($row['dias'] >  1){                 
//////////////////////////////////////////////////////////////////// twilio
            $message = null;
            if($row['status'] == 2 || $row['status'] == 3){
                
                $to_number = $row['phone'];

                if($row['status'] == 2){
                    $message = $row['first_name'].' '.$row['last_name']." Su solicitud ha sido aprobada!";
                }else{
                    $message = $row['first_name'].' '.$row['last_name']." Lo sentimos su solicitud fue rechazada: ".$row['comment'];
                }

                $client = new Client($account_sid, $auth_token);
                try {
                    $client->messages->create(
                    $to_number,
                    array(
                        "from" => $twilio_phone_number,
                        "body" => $message
                    )
                );

                } catch ( \Services_Twilio_RestException $e ) {
                    elog( 'EACT', $e->getMessage(  ) , __FUNCTION__ );  
                }
            }
//////////////////////////////////////////////////////////////////// twilio
        }
    }
} else {
echo "0 results";
}


?>

