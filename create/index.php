<?php

// Chama o arquivo CONFIG que possui o token e usa a variavel $acess_token
include("../config.php");

// Dados recebidos via POST
$name = strtolower($_POST['name']);
$email = strtolower($_POST['email']);
$value = $_POST['value'];
// fixes
$value = str_replace(",", ".", $value);
$value = floatval($value);

$description = "PRODUTO #1";

// pagador
$pagador = [
    "first_name" => $name,
    "last_name" => "",
    "email" => $email
];

// Gera um id de compra (referencia para ser usada no seu sistema)
$externalReference = geraString(24);

// função para gerar a string
function geraString($length) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


// Informações sobre o pagamento:
$infos = [
    "notification_url" => $notification_url, // config.php
    "description" => $description,
    "external_reference" => $externalReference,
    "transaction_amount" => $value,
    "payment_method_id" => "pix"
];

// encoda as informações em json
$payment = array_merge(["payer" => $pagador], $infos);
$payment = json_encode($payment);

// 
$uuid = generateUUID();
function generateUUID()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff), // 8 caracteres
        mt_rand(0, 0xffff), // 4 caracteres
        mt_rand(0, 0x0fff) | 0x4000, // 4 caracteres para a versão 4
        mt_rand(0, 0x3fff) | 0x8000, // 4 caracteres para a variante
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff) // 12 caracteres
    );
}

// faz o request para o mercado pago usando CURL
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.mercadopago.com/v1/payments/",
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => $payment,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $acess_token,
        'X-Idempotency-Key: ' . $uuid,
        'Content-Type: application/json'
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

// decoda a response
$data = json_decode($response, true);
$response = json_decode($response, true);

// simplifica o caminho para a key com os dados da transação
$response = $response['point_of_interaction']['transaction_data'];

// cria uma array apenas com as informações
$arr = array('qr_code' => $response['qr_code'], 'qr_code_base64' => $response['qr_code_base64'], 'payment_url' => $response['ticket_url'], 'id' => $data['id'], 'ref' => $externalReference, 'full_info_for_developer' => $data);

// cria um arquivo com a referencia da transação para verificar se o pagamento foi aprovado depois
$paymentId = $data['id'];
file_put_contents("../transactions/$externalReference", "pending;$paymentId");

// exibe array
echo json_encode($arr);
