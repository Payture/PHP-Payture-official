<?php
/**
 * This file contains basic configuration class
 *
 * @author S.Andreev sergey.andreev@payture.com
 * @copyright 2015 Payture
 */

/**
 * Payture basic configuration class
 *
 * Uses PHP version 5
 *
 * @category Main class
 * @version 1.0
 * @since 1.0 Basic version
 */
abstract class PaytureConfiguration
{

    const ENV_PRODUCTION = 1;
    const ENV_DEVELOPMENT = 2;

    /** @var int Environment */
    private static $_env = self::ENV_PRODUCTION;
    /** @var string Merchant ID in Payture system */
    private static $_merchantKey = "";
    /** @var string API prefix for URL */
    private static $_apiPrefix = "";

    /**
     * Set API prefix for URL creation
     *
     * @param string $prefix API Prefix
     */
    public static function setApiPrefix( $prefix )
    {
        self::$_apiPrefix = $prefix;
    }

    /**
     * Return API prefix for URL creation
     *
     * @return string API Prefix
     */
    public static function getApiPrefix()
    {
        return self::$_apiPrefix;
    }

    /**
     * Set environment for domain choose
     *
     * @param int $environment Environment
     */
    public static function setEnvironment( $environment )
    {
        self::$_env = $environment;
    }

    /**
     * Return environment for domain choose
     *
     * @return int Environment
     */
    public static function getEnvironment()
    {
        return self::$_env;
    }

    /**
     * Set Merchant ID
     *
     * @param string $merchantKey Merchant ID, issued with test/production access parameters
     */
    public static function setMerchantKey( $merchantKey )
    {
        self::$_merchantKey = $merchantKey;
    }

    /**
     * Return Merchant ID
     *
     * @return string Merchant ID, issued with test/production access parameters if set
     */
    public static function getMerchantKey()
    {
        return self::$_merchantKey;
    }
}