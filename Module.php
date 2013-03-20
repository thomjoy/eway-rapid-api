<?php
namespace Eway;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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
