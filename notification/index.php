<?php

include("../config.php");

$paymentId = $_REQUEST['id'] ?? null;

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
$valuePayment = (float) $response['transaction_amount'];

if($status=="approved"){
    file_put_contents("../transactions/$externalReference", "approved;$paymentId");
}

echo json_encode($response);
