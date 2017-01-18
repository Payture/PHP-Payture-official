<?php
/**
 * This file contains basic abstract class for extension in real API classes
 *
 * @author S.Andreev sergey.andreev@payture.com
 * @copyright 2015 Payture
 */

/**
 * Payture base class.
 *
 * Uses PHP version 5
 *
 * @category Main class
 * @version 1.0
 * @since 1.0 Basic version
 */
abstract class Payture
{
    /**
     * Executes request to Payture gateway and return answer, converted to stdClass
     *
     * @param string $operation Operation name, i.e. "Pay"
     * @param string $apiPrefix Prefix for api type in URL
     * @param array $params Array of pairs kay => value, that will be used as GET parameters
     *
     * @return stdClass
     */
    protected static function request( $operation, $apiPrefix, $params = array(), $post=false )
    {
        PaytureConfiguration::setApiPrefix($apiPrefix);

        $ch = curl_init();
        
        $requestLink = self::generateLink($operation, $params);
        
        curl_setopt( $ch, CURLOPT_URL,  $requestLink);
        curl_setopt( $ch, CURLOPT_POST, $post );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

        $result = curl_exec( $ch );
        
        curl_close( $ch );
        return self::_convertResponse( $result );
    }

    /**
     * Generate link for server request
     *
     * @param string $operation Operation name, i.e. "Pay"
     * @param array $params Array of pairs kay => value, that will be used as GET parameters
     *
     * @return string
     */
    protected static function generateLink($operation, $params )
    {
        $link = "https://".self::_getDomain()."/".PaytureConfiguration::getApiPrefix()."/" . $operation."?".self::stringify($params, "&"); 
        return $link;
    }

    /**
     * Convert XML string with answer from Payture gateway to stdClass
     *
     * @param string $XMLString Valid XML string, that consists from 1 element with attributes
     *
     * @return stdClass
     */
    private static function _convertResponse($XMLString)
    {
        echo $XMLString;

        $xml = new SimpleXMLIterator($XMLString);

        $resultObject = self::_XMLNodeToArray($xml);

        return (object)$resultObject;
    }

    /**
     * Recursively convert SimpleXMLIterator to array
     *
     * @param SimpleXMLIterator $XMLNode
     *
     * @return array
     */
    private static function _XMLNodeToArray($XMLNode)
    {

        $result = array();
        foreach($XMLNode->attributes() as $k => $v){
            $val = (string)$v;
            if($val == "True" || $val == "False") $val = (bool)$val;
            $result[$k] = $val;
        }
        foreach($XMLNode->children() as $chK =>  $chNode){
            $result[$chK] = self::_XMLNodeToArray($chNode);
        }

        return $result;
    }

    /**
     * Return domain, depending of environment
     *
     * @return string
     *
     * @throws Exception
     */
    private static function _getDomain()
    {
        if (PaytureConfiguration::getEnvironment() == PaytureConfiguration::ENV_PRODUCTION) {
            return "secure.payture.com";
        } elseif (PaytureConfiguration::getEnvironment() == PaytureConfiguration::ENV_DEVELOPMENT) {
            return "sandbox.payture.com";
        } else {
            throw new Exception("Environment is not set!");
        }
    }

    /**
     * Helper, that converts array of parameters to string with provided glue
     *
     * @param array $array Array of pairs kay => value, will be converted to key=value
     * @param string $glue String that used as glue between pairs
     *
     * @return string
     */
    protected static function stringify( $array, $glue = ";" )
    {
        $mergedArray = array();
        foreach ($array as $k => $v) {
            $mergedArray[] = $k . "=" . $v;
        }

        return implode( $glue, $mergedArray );
    }
}