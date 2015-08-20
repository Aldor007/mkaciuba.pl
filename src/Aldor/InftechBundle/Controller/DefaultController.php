<?php

namespace Aldor\InftechBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
  * @Cache(expires="tomorrow", public="true")
  */
class DefaultController extends Controller

{
    /**
    * @Cache(expires="tomorrow", public="true")
    */
    public function indexAction()
    {
      $content = $this->renderView('AldorInftechBundle:Default:index.html.twig', array());
      return new Response($content);
    }
}
