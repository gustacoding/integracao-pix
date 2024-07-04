# Integração Pix com PHP

Este repositório contém uma implementação em PHP para gerar o payload e o QR Code estático do Pix, facilitando as transações via Pix em aplicações PHP.

## Funcionalidades

- Geração de payload Pix conforme os padrões estabelecidos pelo Banco Central do Brasil.
- Criação de um QR Code estático que pode ser utilizado para realizar pagamentos via Pix.

## Como Usar

### Pré-requisitos

Para utilizar este repositório, você precisa ter o PHP instalado em seu ambiente. Além disso, este projeto utiliza a biblioteca `mpdf/qrcode` para gerar os QR Codes. Certifique-se de ter o Composer instalado para gerenciar as dependências.

### Instalação

Clone o repositório e instale as dependências com o Composer:

```bash
git clone https://github.com/gustacoding/integracao-pix.git
cd integracao-pix
composer install
