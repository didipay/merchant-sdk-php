## Didipay payment merchant php SDK

Official website (https://didipay.didiglobal.com)

## Dependencies
Php 5.6.0 or higher is required

ext-openssl is required

ext-curl is required

ext-json is required

## Signature process

Get all request parameters, excluding byte-type parameters, such as files and byte streams, remove the sign field, and sort them in ascending order according to the key-value ASCII code of the first character (in ascending alphabetical order). The two-character key-value ASCII codes are sorted in ascending order, and so on.

Filter and sort

Splicing: Combine the sorted parameters and their corresponding values ​​into the format of "parameter=parameter value", and connect these parameters with the & character, and the generated string is the string to be signed.

Call the signature algorithm: Use the SHA256WithRSA signature function corresponding to the respective language to use the merchant's private key to sign the signature string to be signed, and perform Base64 encoding.
Assign the generated signature to the sign parameter and concatenate it into the request parameter.

Key format issue

The private key used in Java language is in PKCS8 encoding format, and the private key used in non-Java language is in PKCS1 format.
The Java language needs to remove the BEGIN, END lines, line breaks, and spaces in the key. Non-Java languages ​​retain the original key format.

## Getting Started
We recommend managing third-party dependencies from Packagist using Composer, which allows you to add new libraries and include them in your PHP projects.

### Install Composer

From the command-line, download Composer.

### Install the library
We recommend installing the library with Composer, a package manager for PHP:

### Command Line

composer require didipay/merchant-php-sdk
After you install the library with Composer, the library is added automatically as a dependency in your project’s composer.json file. For example:

  ```shell
  {
    "require": {
        "didipay/merchant-php-sdk": "^${merchant-sdk-version}"
    }
  }
  ```
To use the bindings, use Composer’s autoload. For example:
 ```shell
require_once('vendor/autoload.php');
```
You can use composer to install this package.For example:
 ```shell
composer require didipay/merchant-php-sdk
```
### Run your first request:
Now that you have the PHP SDK installed, you can create API requests. 
 ```php
require_once 'vendor/autoload.php';

use DidiPay\client\merchantClient;

class MerchantClientTest
{

    public function test_pay_query()
    {
        
        $params = ['merchant_order_id' => $merchantOrderId,
            'pay_order_id' => $payOrderId];


        $domain = "https://api.99pay.com.br";
        $defaultOption = ['app_id' => $appId, 'merchant_id' => $merchantId, 'private_key' => $privateKeyContent, 'domain' => $domain];

        $client = new merchantClient($defaultOption);
        $ret = $client->payQuery($params);
        echo $ret;
    }
}
 ```
Save the file as merchant_client_test.php. From the command-line, cd to the directory containing the file you just saved then run:
 ```shell
php merchant_client_test.php
 ```
If everything worked, the command-line shows the following response. Save these identifiers so you can use them while building your integration.
This wraps up the quickstart. See the link below for a few different ways to process a payment for the product you just created.

[Document](https://didipay.didiglobal.com/developer/docs/en/)
