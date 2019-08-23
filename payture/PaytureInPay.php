<?php
/**
 * This file contains API class for InPay Payture API
 *
 * @author S.Andreev sergey.andreev@payture.com
 * @copyright 2015 Payture
 */

/**
 * Class that provides methods for executing Payture InPay API requests.
 *
 * Uses PHP version 5
 *
 * @category API class
 * @version 1.0
 * @since 1.0 Basic version
 */
class PaytureInPay extends Payture
{
    const INPAY_API_PREFIX = "apim";

    /** @var string Payment ID. It exists after successful response to the Init */
    private static $_sessionId = "";

    /**
     * Initialize a new payment session.
     * It is executed before User is redirected to the Payture payment gateway where User’s card data will be entered.
     *
     * @param array $data Payment parameters
     *
     * @return stdClass Initialization result
     */
    public static function Init( $data )
    {
        $response = self::request(
            "Init", self::INPAY_API_PREFIX,
            array( "Key" => PaytureConfiguration::getMerchantKey(), "Data" => self::stringify( $data ) )
        );

        if ($response && isset($response->SessionId)) {
            self::$_sessionId = $response->SessionId;
        }

        return $response;
    }

    /**
     * Generate link for "Pay" operation
     *
     * @return string Link for "Pay" operation
     */
    public static function generatePayLink()
    {
        return self::generateLink( "Pay", array( "SessionId" => self::$_sessionId ) );
    }

    /**
     * This request charges Customer’s card for the amount of cash previously blocked by Pay command.
     * The request follows the two-step payment scheme. Must be preceded by successful Init command
     * with SessionType key set to “Block”.
     * As a result of the processing of the request, the amount blocked during the execution of the Pay command
     * will be charged from the Customer’s card.
     * Important: User’s card will only be charged if the payment status
     * is Authorized at the moment of request execution.
     *
     * @param string $password Merchant password for executing operations via API.
     * @param string $orderId Payment ID in Merchant system
     *
     * @param int $amount Amount of payment in kopecks (Optional - if empty means total sum)
     *
     * @return stdClass
     */
    public static function Charge( $password, $orderId, $amount = 0  )
    {
        $data = array(
            "Key"      => PaytureConfiguration::getMerchantKey(),
            "Password" => $password,
            "OrderId"  => $orderId
        );
        if ($amount) {
            $data["Amount"] = $amount;
        }
        return self::request( "Charge", self::INPAY_API_PREFIX, $data );
    }

    /**
     *
     * This request modifies the amount blocked on Customer’s card by earlier Pay command,
     * or completely removes the block.
     * The request follows the two-step payment scheme
     * As a result of the processing of the request. the blocked amount on the Customer’s card
     * will be changed accordingly.
     * Important: User’s card will only be charged if the payment status
     * is Authorized at the moment of request execution.
     *
     * @param string $password Merchant password for executing operations via API.
     * @param string $orderId Payment ID in Merchant system
     * @param int $amount Amount of payment in kopecks
     *
     * @return stdClass
     */
    public static function Unblock( $password, $orderId, $amount )
    {
        return self::request( "Unblock", self::INPAY_API_PREFIX, array(
            "Key"      => PaytureConfiguration::getMerchantKey(),
            "Password" => $password,
            "OrderId"  => $orderId,
            "Amount" => $amount
        ) );
    }

    /**
     *
     * This request refunds the amount charged by Pay or Charge command to Customer’s card.
     * The request is used both in one-step and two-step payment schemes.
     * As a result of the processing of the request, the charged amount will be returned (fully or partially)
     * to the Customer’s card.
     * Important: Customer’s card will only be refunded if the payment status is Charged
     * at the moment of request execution.
     *
     * @param string $password Merchant password for executing operations via API.
     * @param string $orderId Payment ID in Merchant system
     * @param int $amount Amount of payment in kopecks
     *
     * @return stdClass
     */
    public static function Refund( $password, $orderId, $amount)
    {
        return self::request( "Refund", self::INPAY_API_PREFIX, array(
            "Key"      => PaytureConfiguration::getMerchantKey(),
            "Password" => $password,
            "OrderId"  => $orderId,
            "Amount"   => $amount
        ) );
    }

    /**
     * This request retrieves information on current status of a payment.
     * It should also be used at discretion in case of Payture gateway not responding,
     * while other payment requests are being processed.
     * The request is used both in one-step and two-step payment schemes.
     * As a result of the request, the current payment status will be received.
     *
     * @param string $orderId Payment ID in Merchant system
     *
     * @return stdClass
     */
    public static function PayStatus( $orderId )
    {
        return self::request( "PayStatus", self::INPAY_API_PREFIX, array(
            "Key"      => PaytureConfiguration::getMerchantKey(),
            "OrderId"  => $orderId
        ) );
    }
}
