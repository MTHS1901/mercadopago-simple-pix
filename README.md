# mercadopago-pix-api-simplificada

API para receber pagamentos via PIX pelo Mercado Pago, sistema simplificado que gera um QR CODE para pagamento.


## /create
Cria o pedido de pagamento e envia ao Mercado Pago, dessa forma recebe os dados para pagamento do PIX.

## /transactions
Pasta onde ficara registrado as transações feitas

## /verify
Verificar se o pagamento do pix foi realizado, o mesmo deve ser consultado manualmente.

## /notification
Neste caso não houve integração.

## config.php
Token (credencial) da conta Mercado Pago e pode ser solicitado aqui: https://www.mercadopago.com.br/developers/pt/docs/your-integrations/credentials

## Como usar?
Baixe o os arquivos do repositorio, pegue a credencial no mercado pago, coloque a credencial no config.php, efetue o teste pelo index.html
