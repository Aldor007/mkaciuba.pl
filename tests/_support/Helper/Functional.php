<?php
namespace Helper;
// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    public function setHost() {
        $service = $this->getModule('Symfony2')->grabServiceFromContainer('request');
        $service->headers->setHeader('Host', 'mkaciuba.dev');

    }
}
