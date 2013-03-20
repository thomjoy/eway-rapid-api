<?php
namespace Eway;

class Module
{
    public function getConfig()
    {
        $configFile = __DIR__ . '/config/module.config.php';
        
        if( file_exists($configFile) )
        {
            return include_once($configFile);
        }
        else
        {
            die("Please fill in eWay API credentials in " . __DIR__ . "/config/module.config.dist.php.<br />
                Once done, rename file to <strong>module.config.php</strong> and hit refresh.");
        }
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Eway\RapidAPI' => function($sm) {
                    return new \Eway\RapidAPI($this->getConfig());
                },
                'Eway\CreateAccessCodeRequest' => function() {
                    return new \Eway\CreateAccessCodeRequest();
                },
                'Eway\GetAccessCodeResultRequest' => function() {
                    return new \Eway\GetAccessCodeResultRequest();
                },
            ),
        );
    }
}
