<?php  



// -- 0 pendiente

// -- 1 faltan 3 dias

// -- 2 vencido

// -- 3 pagado



$mysqli = new mysqli('96.30.42.83', 'toolbox_diimou', 'ENnHY{HX3MCO', 'toolbox_diimov1');



// verificamos que sea correcta 

if ($mysqli->connect_error) {

    die('Error de Conexión (' . $mysqli->connect_errno . ') '

        . $mysqli->connect_error);

}else{

    // echo 'Connected successfully';

}



// TODAS LAS FECHAS DEL CREDITO PAGADAS -> CREDITO FINALIZADO

$payment_date = "SELECT * FROM credit_payment_date ORDER BY id_credits ASC"; 



$credit = "SELECT * FROM credits WHERE status = 4 ORDER BY id_credits ASC";



$result_payment_date = $mysqli->query($payment_date);



$result_credit = $mysqli->query($credit);



foreach ($result_credit as $key => $c) {

	$end = 1;

	foreach ($result_payment_date as $key => $pd) {

		if($c['id_credits'] == $pd['id_credits']){

			if($pd['status'] < 3){

				$end = 0;

			}

		}

	}



	if($end == 1){

		$sql = "UPDATE credits SET status = 5 WHERE id_credits =".$c['id_credits'];

		$mysqli->query($sql) or die(mysql_error());

	} 

}





// SE PAGO LA CUOTA 

$sql = "SELECT payments.id_credits, payments.id_credit_payment_date, credit_payment_date.date, credit_payment_date.status FROM payments, credit_payment_date WHERE payments.id_credit_payment_date = credit_payment_date.id_credit_payment_date AND status <= 2";

$result = $mysqli->query($sql);



if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {

    	$sql = "UPDATE credit_payment_date SET status = 3 WHERE id_credit_payment_date = ".$row['id_credit_payment_date'];

		$mysqli->query($sql) or die(mysql_error());

    }

} else {

// echo "0 results";

}







// VENCIDO O DIAS FALTANTES

$sql = "SELECT * FROM credit_payment_date";

$result = $mysqli->query($sql);



function expiredDate($date){

    $date2 = new DateTime(date("Y-m-d 00:00:00")); // hoy

    $dateEnd = $date2->diff($date);

    return $dateEnd;   

}



if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {

    	if($row["status"] <= 1){

    		$date = new DateTime(date($row["date"]." 00:00:00"));

    		$expired = expiredDate($date);

    		if($expired->invert == 1){ // vencido

    			$sql = "UPDATE credit_payment_date SET status = 2 WHERE id_credit_payment_date = ".$row['id_credit_payment_date'];

				$mysqli->query($sql) or die(mysql_error());

    		}else{

    			if($expired->d <= 3){ // 3 dias antes de vencer

    				$sql = "UPDATE credit_payment_date SET status = 1 WHERE id_credit_payment_date = ".$row['id_credit_payment_date'];

					$mysqli->query($sql) or die(mysql_error());

    			}

    		}

    	}

    }

} else {

echo "0 results";

}





$mysqli->close();







?>