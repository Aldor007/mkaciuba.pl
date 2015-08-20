<?php

namespace Aldor\PortfolioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Aldor\InftechBundle\Controller\BaseController as BaseController;

use Aldor\PortfolioBundle\Entity\Technology;
use Aldor\PortfolioBundle\Form\TechnologyType;

class TechnologyController extends BaseController
{

    public function indexAction($slug, $page = 1)
    {

        $this->construct();
        $res = new Response();
        $etag = 'cas';
        $tech  = $this->m_em->getRepository('AldorPortfolioBundle:Technology')->findOneBySlug($slug);
        if (!$tech) {
            throw $this->createNotFoundException("Tech not found!");
        }
        $projects = $this->m_em->getRepository('AldorPortfolioBundle:Project')->getAllProjectsFromTechnology($tech);
        $projects = $this->m_paginator->paginate($projects, $this->get('request')->query->get('strona', $page), $this->container->getParameter('max_post_on_page'));
        $projects->setUsedRoute('portfolio_home');

        $title = $tech->getName().' | Projekty';
        $this->_setSeoData($title, 'List projektów w technologi'.$tech->getName());

        return $this->render('AldorPortfolioBundle:Project:index.html.twig', array(
            'projects'      => $projects,
            'technology'   => $tech
        ), $res);
}

}
