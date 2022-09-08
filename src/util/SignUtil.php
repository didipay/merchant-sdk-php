<?php

namespace DidiPay\Util;

class SignUtil
{

    const PRI_PRE = "-----BEGIN RSA PRIVATE KEY-----\n";
    const PRI_TAIL = "\n-----END RSA PRIVATE KEY-----";
    const PUB_PRE = "-----BEGIN PUBLIC KEY-----\n";
    const PUB_TAIL = "\n-----END PUBLIC KEY-----";
    const SIGN_ALG = "sha256WithRSAEncryption";

    /**
     * 1. Initialize signature data
     * @param $aParams
     * @return null|string
     */
    static function prepareSignData($aParams)
    {
        $aExceptKey = array('sign');
        if (!is_array($aParams) || empty($aParams)) {
            return null;
        }
        ksort($aParams, SORT_STRING);
        $aSortArray = array();
        foreach ($aParams as $key => $value) {
            if (in_array($key, $aExceptKey)) {
                continue;
            }
            $aSortArray[] = "{$key}={$value}";
        }
        return implode('&', $aSortArray);
    }

    /**
     * 2. Perform base64 encoding, to obtain the signature
     * @param $sData
     * @param $sPem
     * @return string
     */
    static function getSign($sData, $sPem)
    {
        if (!strstr($sPem, self::PRI_PRE) || !strstr($sPem, self::PRI_TAIL)) {
            $sPem = self::PRI_PRE . $sPem . self::PRI_TAIL;
        }
        $sPriKey = openssl_pkey_get_private($sPem);
        $sSign = '';
        openssl_sign($sData, $sSign, $sPriKey, self::SIGN_ALG);
        if (PHP_VERSION_ID < 80000) {
            openssl_free_key($sPriKey);
        }
        return base64_encode($sSign);
    }

    /**
     * 3. Generate the final required signature
     * @param $aParams
     * @param $sPriContent
     * @return string
     */
    public static function generateSign($aParams, $sPriContent)
    {
        $sSignData = self::prepareSignData($aParams);
        return self::getSign($sSignData, $sPriContent);
    }

    /**
     * Verify sign
     * @param $params
     * @param $publicKey
     * @param $sign
     * @return bool
     */
    public static function verifySign($params, $publicKey, $sign)
    {

        if (!strstr($publicKey, self::PUB_PRE) || !strstr($publicKey, self::PUB_TAIL)) {
            $publicKey = self::PUB_PRE . $publicKey . self::PUB_TAIL;
        }
        $data = self::prepareSignData($params);

        return openssl_verify($data, base64_decode($sign), $publicKey, self::SIGN_ALG);
    }
}
