<?php

namespace DidiPay\Util;

use function PHPUnit\Framework\containsEqual;

class SignUtil
{

    const PRI_PRE = "-----BEGIN RSA PRIVATE KEY-----\n";
    const PRI_TAIL = "\n-----END RSA PRIVATE KEY-----";

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
     * @return string
     */
    static function getSign($sData, $sPem)
    {
        if(strstr($sPem,self::PRI_PRE)){
            $sPem = str_replace(self::PRI_PRE, "", $sPem);
        }
        if(strstr($sPem,self::PRI_TAIL)){
            $sPem = str_replace(self::PRI_TAIL, "", $sPem);
        }
        $sPem = self::PRI_PRE . $sPem . self::PRI_TAIL;
        $sPriKey = openssl_pkey_get_private($sPem);
        $sSign = '';
        openssl_sign($sData, $sSign, $sPriKey, 'sha256WithRSAEncryption');
        openssl_free_key($sPriKey);
        return base64_encode($sSign);
    }

    /**
     * 3. Generate the final required signature
     * @param $aParams
     * @return string
     */
    public static function generateSign($aParams, $sPriContent)
    {
        $sSignData = self::prepareSignData($aParams);
        return self::getSign($sSignData, $sPriContent);
    }
}
