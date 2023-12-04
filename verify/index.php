<?php

include("../config.php");

$reference = $_POST['ref'];

// $status = explode(";", "../transactions/$reference")[0];
$paymentId = explode(";", file_get_contents("../transactions/$reference"))[1];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/' . $paymentId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => [
        'accept: application/json',
        'content-type: application/json',
        'Authorization: Bearer ' . $acess_token
    ]
]);

$response = curl_exec($curl);
$response = json_decode($response, true);

curl_close($curl);

$externalReference = $response['external_reference'];
$status = $response['status'];

//$valuePayment = (float) $response['transaction_amount'];

if($status=="approved"){
    file_put_contents("../transactions/$externalReference", "approved;$paymentId");
}

$arr = ['status'=> $status];
echo json_encode($arr);