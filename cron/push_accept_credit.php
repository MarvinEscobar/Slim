<?php  

$mysqli = new mysqli('96.30.42.83', 'toolbox_diimou', 'ENnHY{HX3MCO', 'toolbox_diimov1');

// verificamos que sea correcta 
if ($mysqli->connect_error) {
    die('Error de Conexión (' . $mysqli->connect_errno . ') '
        . $mysqli->connect_error);
}else{
    // echo 'Connected successfully';
}


$sql = "SELECT * FROM credits";
$result = $mysqli->query($sql);

function expiredDate($date){
    $date2 = new DateTime(date("Y-m-d 00:00:00")); // hoy
    $dateEnd = $date2->diff($date);
    return $dateEnd;   
}

$day = null;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    	if($row["status"] == 2){
    		$date = new DateTime(date(substr($row["date"], 0, -9)." 00:00:00"));
    		$expired = expiredDate($date);

    		if($expired->d <= 3){ // 3 dias para aceptar el prestamo
				$day =  $expired->d;
    		}
    	}
    }
} else {
echo "0 results";
}




function sendMessage($data,$target){
//FCM api URL
	$url = 'https://fcm.googleapis.com/fcm/send';
	$server_key = 'AAAA9xS-8vI:APA91bFZj99KEaScSCKUP5tQ0qj6Y2seNSDrnkTllrY8gaxck3bj73JuFLZVwRzf_eHGio5y6u8KyKJiSiZ3HVzs5eNWG1q3-5UCR2DqY8uVypr6S-Rwe3izctMVwfDjJT5yX-cWXmcA';
	
	$fields = array();
	$fields['priority'] = "high";
	$fields['data'] = $data;
	if(is_array($target)){
		$fields['registration_ids'] = $target;
	}else{
		$fields['to'] = $target;
	}
	//header with content_type api key
	$headers = array(
		'Content-Type:application/json',
	  	'Authorization:key='.$server_key
	);
				
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	if ($result === FALSE) {
		die('FCM Send Error: ' . curl_error($ch));
	}
	curl_close($ch);

	return $result;

}

	$target = "/topics/notifications";
	$title = "Tu Prestamo ha sido aprobado!";
	$body = "Tienes hasta ".$day." para aceptar tu prestamo!";
	$badge = 0;
	$sound = 1;

	$datafinal = array(
		'title' 	=> "$title",
		'body' 		=> "$body",
		'badge' 	=> "$badge", 
		'sound' 	=> $sound
	);

	// $datafinal["type"] = "new";
	// $datafinal["newID"] = "141757";
	// $datafinal["newTitle"] = "Estos son los tres puntos claves donde Trump iniciará el muro con México";
	// $datafinal["newUrl"] = "http://www.elsalvador.com/articulo/internacional/estos-son-los-tres-puntos-claves-donde-trump-iniciara-muro-con-mexico-141757";
	
		
	$result =  sendMessage($datafinal,$target);

	echo $result;

?>