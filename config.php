<?php
// Código por: github.com/mths1901

header('Access-Control-Allow-Origin: *'); // permita receber requisições de qualquer dominio para todos os arquivos /create /verify etc...

date_default_timezone_set('America/Sao_Paulo'); // seta o timezone para America/Sao_Paulo

// https://www.mercadopago.com.br/settings/account/credentials
$acess_token = "SEU_ACESS_TOKEN";

// o mercadopago envia uma notifição toda vez que houver uma mudança na transação, este seria seu URL com o script para receber essa notificação
$notification_url = "CAMINHO_PARA_NOTIFICATION"; // ***obrigatorio*** - exemplo "https://seudominio.com/api/notification/"
?>
