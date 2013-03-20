<?php

/**
 * eWay RapidAPI
 * Sample code reworked and trimmed down slightly
 * 
 * @author eWAY
 */
namespace Eway;

class RapidAPI {

    private $config;
    private $credentials;
    private $url;
    private $format;
    private $method;

    const FORMAT_XML = "XML";
    const FORMAT_JSON = "JSON";

    public function __construct($config)
    {
        $this->config = $config;
        $this->baseUrl = isset($_SERVER['ENVIRONMENT']) ? $config['baseUrl'][$_SERVER('ENVIRONMENT')] : $config['baseUrl']['DEVELOPMENT'];
        $this->credentials = $config['credentials']['username'] . ":" . $config['credentials']['password'];
        $this->format = $config['format'];
        $this->method = $config['method'];
        $this->debug = $config['debug'];
    }

    /*
    *   Useful for a view helper.
    *   I don't love this...   
    */
    public function getJavascriptSrc()
    {
        return $this->baseUrl . $this->config['jsonpScript'];
    }

    /**
     * Description: Create Access Code
     * @param CreateAccessCodeRequest $request
     * @return StdClass An PHP Ojbect 
     */
    public function CreateAccessCode($request)
    {
        //Convert An Object to Target Formats
        if( $this->format === self::FORMAT_XML )
        {
            $request = \Eway\Parser::Obj2XML($request);
        }
        else 
        {
            $request = \Eway\Parser::Obj2JSON($request);
        }
           
        $method = 'CreateAccessCode' . $this->method;
        $response = $this->$method($request);

        //Convert Response Back TO An Object
        if( $this->format === self::FORMAT_XML )
        {
            $result = \Eway\Parser::XML2Obj($response);
        }
        else
        {
             $result = \Eway\Parser::JSON2Obj($response);
        }

        return $result;
    }

    /**
     * Description: Get Result with Access Code
     * @param GetAccessCodeResultRequest $request
     * @return StdClass An PHP Ojbect 
     */
    public function GetAccessCodeResult($request)
    {
        if( true === $this->debug )
        {
            echo "GetAccessCodeResult Request Object";
            var_dump($request);
        }

        //Convert An Object to Target Formats       
        if( $this->format === self::FORMAT_XML )
        {
             $request = \Eway\Parser::Obj2XML($request);
        }
        else
        {
            $request = \Eway\Parser::Obj2JSON($request);   
        }
                
        //Build method name
        $method = 'GetAccessCodeResult' . $this->method;
        
        //Is Debug Mode
        if( true === $this->debug )
        {
            echo "GetAccessCodeResult Request String";
            var_dump($request);
        }

        //Call to the method
        $response = $this->$method($request);
        
        //Is Debug Mode
        if( true === $this->debug )
        {
            echo "GetAccessCodeResult Response String";
            var_dump($response);
        }

        if( $this->format === self::FORMAT_XML )
        {
            $result = \Eway\Parser::XML2Obj($response);
        }
        else
        {
            $result = \Eway\Parser::JSON2Obj($response);

            //Tweak the Options Obj to $obj->Options->Option[$i]->Value instead of $obj->Options[$i]->Value
            if( isset($result->Options) )
            {
                $i = 0;
                $tempClass = new \stdClass();
                foreach ($result->Options as $Option)
                {
                    $tempClass->Option[$i]->Value = $Option->Value;
                    $i++;
                }
                $result->Options = $tempClass;
            }
        }
                
        if( true === $this->debug )
        {
            echo "GetAccessCodeResult Response Object";
            var_dump($result);
        }

        return $result;
    }

    /**
     * Description: Create Access Code Via HTTP POST
     * @param XML/JSON Format $request
     * @return XML/JSON Format Response 
     */
    public function CreateAccessCodePOST($request)
    {
        return $this->PostToRapidAPI(($this->baseUrl . $this->config['POST'][$this->format]['CreateAccessCode']), $request);
    }

    /**
     * Description: Get Result with Access Code Via HTTP POST
     * @param XML/JSON Format $request
     * @return XML/JSON Format Response 
     */
    public function GetAccessCodeResultPOST($request)
    {
        return $this->PostToRapidAPI(($this->baseUrl . $this->config["POST"][$this->format]["GetAccessCodeResult"]), $request);
    }

    /*
     * Description A Function for doing a Curl GET/POST
     */
    private function PostToRapidAPI($url, $request, $isPost = true)
    {
        $ch = curl_init($url);

        if( $this->format === self::FORMAT_XML )
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        }
        else
        {
             curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
        }
           
        curl_setopt($ch, CURLOPT_USERPWD, $this->credentials);
        
        if( $isPost )
        {
           curl_setopt($ch, CURLOPT_POST, true); 
        }   
        else
        {
           curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); 
        }
            
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

/**
 * Description of CreateAccessCodeRequest
 * 
 * 
 */
class CreateAccessCodeRequest {

    /**
     * @var Customer $Customer
     */
    public $Customer;

    /**
     * @var ShippingAddress $ShippingAddress
     */
    public $ShippingAddress;
    public $Items;
    public $Options;

    /**
     * @var Payment $Payment
     */
    public $Payment;
    public $RedirectUrl;
    public $Method;
    private $CustomerIP;
    private $DeviceID;

    function __construct()
    {
        $this->Customer = new \Eway\Customer();
        $this->ShippingAddress = new ShippingAddress();
        $this->Payment = new \Eway\Payment();
        $this->CustomerIP = $_SERVER["REMOTE_ADDR"];
    }
}

/**
 * Description of Customer
 */
class Customer {
    public $TokenCustomerID;
    public $Reference;
    public $Title;
    public $FirstName;
    public $LastName;
    public $CompanyName;
    public $JobDescription;
    public $Street1;
    public $Street2;
    public $City;
    public $State;
    public $PostalCode;
    public $Country;
    public $Email;
    public $Phone;
    public $Mobile;
    public $Comments;
    public $Fax;
    public $Url;
}

class ShippingAddress {
    public $FirstName;
    public $LastName;
    public $Street1;
    public $Street2;
    public $City;
    public $State;
    public $Country;
    public $PostalCode;
    public $Email;
    public $Phone;
    public $ShippingMethod;
}

class Items {
    public $LineItem = array();

}

class LineItem {
    public $SKU;
    public $Description;
}

class Options {
    public $Option = array();
}

class Option {
    public $Value;
}

class Payment {
    public $TotalAmount;
    /// <summary>The merchant's invoice number</summary>
    public $InvoiceNumber;
    /// <summary>merchants invoice description</summary>
    public $InvoiceDescription;
    /// <summary>The merchant's invoice reference</summary>
    public $InvoiceReference;
    /// <summary>The merchant's currency</summary>
    public $CurrencyCode;
}

class GetAccessCodeResultRequest {
    public $AccessCode;
}

/*
 * Description A Class for conversion between different formats
 */

class Parser {

    public static function Obj2JSON($obj)
    {
        return json_encode($obj);
    }

    public static function Obj2JSONRPC($APIAction, $obj)
    {
        if ($APIAction == "CreateAccessCode")
        {
            //Tweak the request object in order to generate a valid JSON-RPC format for RapidAPI.
            $obj->Payment->TotalAmount = (int) $obj->Payment->TotalAmount;
        }

        $tempClass = new \stdClass();
        $tempClass->id = 1;
        $tempClass->method = $APIAction;
        $tempClass->params->request = $obj;

        return json_encode($tempClass);
    }

    public static function Obj2ARRAY($obj)
    {
        return get_object_vars($obj);
    }

    public static function Obj2XML($obj)
    {
        $xml = new \XmlWriter();
        $xml->openMemory();
        $xml->setIndent(TRUE);

        $xml->startElement(get_class($obj));
        $xml->writeAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $xml->writeAttribute("xmlns:xsd", "http://www.w3.org/2001/XMLSchema");

        self::getObject2XML($xml, $obj);

        $xml->endElement();
        $xml->endElement();

        return $xml->outputMemory(true);
    }

    public static function Obj2RPCXML($APIAction, $obj)
    {
        if( $APIAction == "CreateAccessCode" )
        {
            //Tweak the request object in order to generate a valid XML-RPC format for RapidAPI.
            $obj->Payment->TotalAmount = (int) $obj->Payment->TotalAmount;
            $obj->Items = $obj->Items->LineItem;
            $obj->Options = $obj->Options->Option;
            $obj->Customer->TokenCustomerID = (float) (isset($obj->Customer->TokenCustomerID) ? $obj->Customer->TokenCustomerID : null);

            return str_replace("double>", "long>", xmlrpc_encode_request($APIAction, get_object_vars($obj)));
        }

        if( $APIAction == "GetAccessCodeResult" )
        {
            return xmlrpc_encode_request($APIAction, get_object_vars($obj));
        }
    }

    public static function JSON2Obj($obj)
    {
        return json_decode($obj);
    }

    public static function JSONRPC2Obj($obj)
    {
        $tempClass = json_decode($obj);
        
        if (isset($tempClass->error))
        {
            $tempClass->Errors = $tempClass->error->data;
            return $tempClass;
        }

        return $tempClass->result;
    }

    public static function XML2Obj($obj)
    {
        //Strip the empty JSON object
        return json_decode(str_replace("{}", "null", json_encode(simplexml_load_string($obj))));
    }

    public static function RPCXML2Obj($obj)
    {
        return json_decode(json_encode(xmlrpc_decode($obj)));
    }

    public static function HasProperties($obj)
    {
        if (is_object($obj))
        {
            $reflect = new \ReflectionClass($obj);
            $props = $reflect->getProperties();
            return !empty($props);
        }
        else
            return TRUE;
    }

    private static function getObject2XML(XMLWriter $xml, $data)
    {
        foreach( $data as $key => $value )
        {
            if( $key == "TokenCustomerID" && $value == "" )
            {
                $xml->startElement("TokenCustomerID");
                $xml->writeAttribute("xsi:nil", "true");
                $xml->endElement();
            }

            if( is_object($value) )
            {
                $xml->startElement($key);
                self::getObject2XML($xml, $value);
                $xml->endElement();
                continue;
            } 
            else if( is_array($value) )
            {
                self::getArray2XML($xml, $key, $value);
            }

            if( is_string($value) )
            {
                $xml->writeElement($key, $value);
            }
        }
    }

    private static function getArray2XML(XMLWriter $xml, $keyParent, $data)
    {
        foreach( $data as $key => $value )
        {
            if( is_string($value) ) 
            {
                $xml->writeElement($keyParent, $value);
                continue;
            }

            if( is_numeric($key) )
            {
                $xml->startElement($keyParent);
            }

            if( is_object($value) )
            {
                self::getObject2XML($xml, $value);
            } 
            else if( is_array($value) )
            {
                $this->getArray2XML($xml, $key, $value);
                continue;
            }

            if( is_numeric($key) )
            {
                $xml->endElement();
            }
        }
    }
}