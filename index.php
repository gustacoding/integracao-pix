<?php

require __DIR__.'/vendor/autoload.php';

use App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

// INSTANCIA PRINCIPAL DO PAYLOAD PIX
$obPayload = (new Payload)->setPixKey('CHAVE PIX AQUI')
                          ->setDescription('DESCRIÇÃOAQUI')
                          ->setMerchantName('NOME')
                          ->setMerchantCity('CIDADE')
                          ->setAmount('120.00')
                          ->setTxid('GERAL');

// CÓDIGO DE PAGAMENTO PIX
$payloadQrCode = $obPayload->getPayload();

// QR CODE
$obQrCode = new QrCode($payloadQrCode);

// IMAGEM DO QRCODE
$image = (new Output\Png)->output($obQrCode, 400);

?>

<h1>QR CODE PIX</h1>

<br>

<img src="data:image/png;base64, <?=base64_encode($image)?>">

<br><br>

Código pix:<br>

<strong><?=$payloadQrCode?></strong>
