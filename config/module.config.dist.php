<?php
return array(
    'debug' => false,
    'method' => 'POST', // POST, REST, XML-RPC, SOAP
    'format' => 'JSON', // JSON, XML
    'jsonpScript' => 'JSONP/v3/js',
    
    'baseUrl' => array(
        'DEVELOPMENT' => 'https://api.sandbox.ewaypayments.com/',
        'LIVE' => 'https://api.ewaypayments.com/'
    ),
    
    'POST' => array(
        'JSON' => array(
            'CreateAccessCode' => 'CreateAccessCode.json',
            'GetAccessCodeResult' => 'GetAccessCodeResult.json'
        ),
        'XML' => array(
            'CreateAccessCode' => 'CreateAccessCode.xml',
            'GetAccessCodeResult' => 'GetAccessCodeResult.xml'
        )
    ),

    'credentials' => array(
        'username' => 'FILL_ME_IN',
        'password' => 'FILL_ME_IN'
    ),

);
