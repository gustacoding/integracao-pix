<?php

namespace App\Pix;

class Payload {
    // IDs
    const ID_PAYLOAD_FORMAT_INDICATOR = '00';
    const ID_POINT_OF_INITIATION_METHOD = '01';
    const ID_MERCHANT_ACCOUNT_INFORMATION = '26';
    const ID_MERCHANT_CATEGORY_CODE = '52';
    const ID_TRANSACTION_CURRENCY = '53';
    const ID_COUNTRY_CODE = '58';
    const ID_MERCHANT_NAME = '59';
    const ID_MERCHANT_CITY = '60';
    const ID_ADDITIONAL_DATA_FIELD_TEMPLATE = '62';
    const ID_CRC16 = '63';
    const ID_MERCHANT_ACCOUNT_INFORMATION_GUI = '00';
    const ID_MERCHANT_ACCOUNT_INFORMATION_KEY = '01';
    const ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION = '02';
    const ID_MERCHANT_ACCOUNT_INFORMATION_URL = '25';
    const ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID = '05';

    // Chaves PIX
    private $pixKey;
    private $description;
    private $merchantName;
    private $merchantCity;
    private $txid;
    private $amount;

    // Métodos getters e setters
    public function setPixKey($value) {
        $this->pixKey = $value;
        return $this;
    }

    public function setDescription($value) {
        $this->description = $value;
        return $this;
    }

    public function setMerchantName($value) {
        $this->merchantName = $value;
        return $this;
    }

    public function setMerchantCity($value) {
        $this->merchantCity = $value;
        return $this;
    }

    public function setTxid($value) {
        $this->txid = $value;
        return $this;
    }

    public function setAmount($value) {
        $this->amount = number_format($value, 2, '.', '');
        return $this;
    }

    private function getValue($id, $value) {
        $size = str_pad(strlen($value), 2, '0', STR_PAD_LEFT);
        return $id . $size . $value;
    }

    private function getMerchantAccountInformation() {
        $gui = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_GUI, 'br.gov.bcb.pix');
        $key = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_KEY, $this->pixKey);
        $url = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_URL, 'pix.example.com/8b3da2f39a4140d1a91abd93113bd441');

        $value = $gui . $key;
        return $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION, $value);
    }

    public function getPayload() {
        $payload = $this->getValue(self::ID_PAYLOAD_FORMAT_INDICATOR, '01') .
                   $this->getValue(self::ID_POINT_OF_INITIATION_METHOD, '12') .
                   $this->getMerchantAccountInformation() .
                   $this->getValue(self::ID_MERCHANT_CATEGORY_CODE, '0000') .
                   $this->getValue(self::ID_TRANSACTION_CURRENCY, '986') .
                   $this->getValue(self::ID_COUNTRY_CODE, 'BR') .
                   $this->getValue(self::ID_MERCHANT_NAME, $this->merchantName) .
                   $this->getValue(self::ID_MERCHANT_CITY, $this->merchantCity) .
                   $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE, $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID, $this->txid));

        if (!empty($this->amount)) {
            $payload .= $this->getValue('54', $this->amount);
        }

        return $payload . $this->getCRC16($payload);
    }

    private function getCRC16($payload) {
        // Adiciona o sufixo para o cálculo do CRC16
        $payload .= self::ID_CRC16 . '04';

        // DADOS DEFINIDOS PELO POLINÔMIO DE 16 BITS
        $polinomio = 0x1021;
        // INICIA O REGISTRADOR COM O VALOR INICIAL
        $resultado = 0xFFFF;

        // EXECUTA PROCESSAMENTO BIT A BIT
        for ($offset = 0; $offset < strlen($payload); $offset++) {
            $resultado ^= (ord($payload[$offset]) << 8);
            for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                if (($resultado <<= 1) & 0x10000) $resultado ^= $polinomio;
                $resultado &= 0xFFFF;
            }
        }

        // RETORNA VALOR CALCULADO DO CRC16 EM HEXADECIMAL
        return self::ID_CRC16 . '04' . strtoupper(dechex($resultado));
    }
}
?>
