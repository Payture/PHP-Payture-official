<?php
/**
 * This file contains API class for standard Payture API
 *
 * @author S.Andreev sergey.andreev@payture.com
 * @copyright 2015 Payture
 */

/**
 * Class that provides methods for executing standard Payture API requests.
 *
 * Uses PHP version 5
 *
 * @category API class
 * @version 1.0
 * @since 1.0 Basic version
 */
class PaytureAPI extends Payture
{
    const STANDARD_API_PREFIX = "api";

    /**
     * This request is used for quick one-step client payments.
     * The request follows the one-step payment scheme.
     * As a result of the processing of the request, the specified amount will be charged from the User card.
     * The charged amount can be (fully or partially) refunded to User’s card via Refund command.
     *
     * @param string $orderId Payment ID in Merchant system
     * @param int $amount Amount of payment in kopecks
     * @param array $payInfo Request parameters
     *
     * @param string $paytureId Payment ID in Payture AntiFraud system (Optional)
     * @param string $customerKey Customer ID in Payture AntiFraud system (Optional)
     * @param array $customFields Additional transaction fields (Optional)
     *
     * @return stdClass
     */
    public static function Pay( $orderId, $amount, $payInfo, $paytureId = "", $customerKey = "", $customFields = array() )
    {
        $data = array(
            "Key"     => PaytureConfiguration::getMerchantKey(),
            "PayInfo" => self::stringify( $payInfo ),
            "OrderId" => $orderId,
            "Amount"  => $amount
        );
        if ( ! empty( $paytureId )) {
            $data["PaytureId"] = $paytureId;
        }
        if ( ! empty( $customerKey )) {
            $data["CustomerKey"] = $customerKey;
        }
        if ( ! empty( $customFields )) {
            $data["CustomFields"] = self::stringify( $customFields );
        }

        return self::request( "Pay", self::STANDARD_API_PREFIX, $data );
    }

    /**
     * This request blocks the specified amount of cash on User’s card.
     * The request follows the two-step payment scheme.
     * As a result of the processing of the request, the funds will be blocked on the User card.
     * The blocked funds can be changed via Charge command, or unblocked via Unblock.
     *
     * @param string $orderId Payment ID in Merchant system
     * @param int $amount Amount of payment in kopecks
     * @param array $payInfo Request parameters
     *
     * @param string $paytureId Payment ID in Payture AntiFraud system (Optional)
     * @param string $customerKey Customer ID in Payture AntiFraud system (Optional)
     * @param array $customFields Additional transaction fields (Optional)
     *
     * @return stdClass
     */
    public static function Block( $orderId, $amount, $payInfo, $paytureId = "", $customerKey = "", $customFields = array() )
    {
        $data = array(
            "Key"     => PaytureConfiguration::getMerchantKey(),
            "PayInfo" => Payture::stringify( $payInfo ),
            "OrderId" => $orderId,
            "Amount"  => $amount
        );
        if ( ! empty( $paytureId )) {
            $data["PaytureId"] = $paytureId;
        }
        if ( ! empty( $customerKey )) {
            $data["CustomerKey"] = $customerKey;
        }
        if ( ! empty( $customFields )) {
            $data["CustomFields"] = self::stringify( $customFields );
        }

        return self::request( "Block", self::STANDARD_API_PREFIX, $data );
    }

    /**
     * This request charges User’s card for the amount of cash previously blocked by Block command.
     * The request follows the two-step payment scheme.
     * As a result of the processing of the request, the amount blocked during the execution of the Block command
     * will be charged from the User card.
     * Important: User’s card will only be charged if the payment status
     * is Authorized at the moment of request execution.
     *
     * @param string $orderId Payment ID in Merchant system
     *
     * @return stdClass
     */
    public static function Charge( $orderId )
    {
        return self::request( "Charge", self::STANDARD_API_PREFIX, array(
            "Key"     => PaytureConfiguration::getMerchantKey(),
            "OrderId" => $orderId
        ) );
    }

    /**
     * This request modifies the amount blocked on User’s card by earlier Block command,
     * or completely removes the block.
     * The request follows the two-step payment scheme.
     * As a result of the processing of the request the blocked amount on the User card will be changed
     * Important: User’s card will only be charged if the payment status
     * is Authorized at the moment of request execution.
     *
     * @param string $orderId Payment ID in Merchant system
     * @param int $amount Amount in kopecks that is to be unblocked
     *
     * @return stdClass
     */
    public static function Unblock( $orderId, $amount )
    {
        return self::request( "Unblock", self::STANDARD_API_PREFIX, array(
            "Key"     => PaytureConfiguration::getMerchantKey(),
            "OrderId" => $orderId,
            "Amount"  => $amount
        ) );
    }

    /**
     * This request refunds the amount charged by Pay or Charge command to User’s card.
     * he request is used both in one-step and two-step payment schemes.
     * As a result of the processing of the request, the charged amount will be returned (fully or partially)
     * to the User card.
     * Important: User’s card will only be charged if the payment status is Charged at the moment of request execution.
     *
     * @param string $orderId Payment ID in Merchant system
     * @param int $amount Amount in kopecks that is to be returned
     *
     * @return stdClass
     */
    public static function Refund( $orderId, $amount )
    {
        return self::request( "Refund", self::STANDARD_API_PREFIX, array(
            "Key"     => PaytureConfiguration::getMerchantKey(),
            "OrderId" => $orderId,
            "Amount"  => $amount
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
    public static function GetState( $orderId )
    {
        return self::request( "GetState", self::STANDARD_API_PREFIX, array(
            "Key"     => PaytureConfiguration::getMerchantKey(),
            "OrderId" => $orderId
        ) );
    }
}