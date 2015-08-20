<?php

namespace Aldor\PortfolioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;


use Aldor\InftechBundle\Cache\CacheManager;
use Aldor\InftechBundle\Controller\BaseController as BaseController;

use Aldor\PortfolioBundle\Entity\Project;
use Aldor\PortfolioBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * Project controller.
 *
 */
class ProjectController extends BaseController
{


    public function indexAction($page=1) {

        $this->construct();
        $res = new Response();
        $etag = 'cas';
        $cacheKey = CacheManager::KEY_PROJECT_ALL;
        $projects = $this->m_cacheManager->get($cacheKey, 'ArrayCollection<Aldor\PortfolioBundle\Entity\Project>');

        if(!$projects) {
            $projects = $this->m_em->getRepository('AldorPortfolioBundle:Project')->getAllProjects();
            $this->m_cacheManager->put($cacheKey, $projects, 6000, 'list');
        }

        $title = 'Projekty';
        $this->_setSeoData($title, 'Lista wykonanych projektów. Technologie nodejs, web, c, python');

        if (count($projects)) {
            $etag = md5("project:$page:{$projects[0]->getId()}:{$projects[0]->getModified()->format('Y-m-d H:i:s')}");
            $res = $this->_prepareResponse($etag, $projects[0]->getModified(), "+1080 seconds", 1080);
            if ($this->_isNotModified($res)) {
                return $res;
            }
        }

        $projects = $this->m_paginator->paginate($projects, $this->get('request')->query->get('strona', $page), $this->container->getParameter('max_post_on_page'));
        $projects->setUsedRoute('portfolio_home');
       

        return $this->render('AldorPortfolioBundle:Project:index.html.twig', array(
            'projects'      => $projects,
        ), $res);
    }


    public function showAction($slug)
    {
        $this->construct();

        $res = new Response();
        $pro = $this->m_em->getRepository('AldorPortfolioBundle:Project')->getOne($slug);

        if (!$pro) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }
        
        $title = $pro->getName().' | Projekty';
        $request = $this->container->get('request');
        $canonical = $this->get('router')->generate($request->attributes->get('_route'), array('slug' => $slug), true);
        $this->_setSeoData($title, $pro->getDescription(), $pro->getKeywords(), $canonical, $pro);

        $etag = md5("project:$slug:{$pro->getModified()->format('Y-m-d H:i:s')}");
        $res = $this->_prepareResponse($etag, $pro->getModified(), "+1080 seconds", 1080);
        if ($this->_isNotModified($res)) {
            return $res;
        }
      

        return $this->render('AldorPortfolioBundle:Project:show.html.twig', array(
            'project'      => $pro,
            'post' => $pro
       ), $res);
    }

    public function feedAction() {
        $this->construct();
        $res = new Response();
        $etag = 'cas';
        $cacheKey = CacheManager::KEY_PROJECT_ALL;
        $projects = $this->m_cacheManager->get($cacheKey, 'ArrayCollection<Aldor\PortfolioBundle\Entity\Project>');
        if(!$projects) {
            $projects = $this->m_em->getRepository('AldorPortfolioBundle:Project')->getAllProjects();
            $this->m_cacheManager->put($cacheKey, $projects, 6000, 'list');
        }
        $countProjects = count($projects);
        if (countProjects) {
            $etag = md5("project:$countProjects");
            $res = $this->_prepareResponse($etag, $projects[0]->getModified(), "+10800 seconds", 1080);
            if ($this->_isNotModified($res)) {
                $res->headers->set('Content-Type', 'application/rss+xml');
                return $res;
            }
        }
        $feed = $this->get('eko_feed.feed.manager')->get('projects');
        $feed->addFromArray($projects);
        $res->setContent($feed->render('atom'));
        $res->setCharset('UTF-8');
        $res->headers->set('Content-Type', 'application/xml');
        return $res;
    }
}
