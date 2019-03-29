<?php
/**
 * This file contains API class for Cheque Payture API
 *
 * @author Mustlab
 * @copyright 2019 Mustlab
 */

/**
 * Class that provides methods for executing Payture Cheque API requests.
 *
 * Uses PHP version 5
 *
 * @category API class
 * @version 1.0
 * @since 1.0 Basic version
 */
class PaytureCheque extends Payture
{
    const CHEQUE_API_PREFIX = "apicheque";

    public static function Create( $params )
    {
        return self::postJSON( "Create", self::CHEQUE_API_PREFIX, $params );
    }

    public static function CreateCorrection( $params )
    {
        return self::postJSON( "CreateCorrection", self::CHEQUE_API_PREFIX, $params );
    }

    public static function Status( $params )
    {
        return self::postJSON( "Status", self::CHEQUE_API_PREFIX, $params );
    }
}
