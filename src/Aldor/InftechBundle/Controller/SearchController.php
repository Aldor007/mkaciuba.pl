<?php
namespace Aldor\InftechBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializationContext;


use Aldor\BlogBundle\Entity\Post;
use Aldor\BlogBundle\Form\PostType;
use Redis;
use Aldor\InftechBundle\Utils\CacheManager;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Elastica\Exception\ResponseException;

/*
 * Class SearchController
 * @author Marcin Kaciuba
 */
class SearchController extends Controller
{
    private $m_logger;
    private $m_enityManager;
    private $m_paginator;
    private $m_wasContruct;
    private $m_cacheManager;

    private function construct() {
        if($this->m_wasContruct == true) {
            return;
        }
        $this->m_wasContruct = true;
        $this->m_enityManager = $this->getDoctrine()->getManager();
        $this->m_logger = $this->get('logger');
        $this->m_paginator = $this->get('knp_paginator');
        $this->m_cacheManager = $this->get('aldor.inftech.cachemanager');

    }
    public function indexAction($search, $page=1)
    {
        $this->construct();
        $res = new Response();
        $res->setExpires((new \DateTime())->modify('+ 120 seconds'));
        $finder = $this->container->get('fos_elastica.finder.search.post');
        if (!$search) {
            $search = $this->get('request')->query->get('search');
        }

        $res->setETag(md5($search));
        $posts = $projects = $photoCategories = $photos = array();
        $res->setPublic();
        try {
            $posts = $this->tryCache($search, 'post_search', 'ArrayCollection<Aldor\BlogBundle\Entity\Post>', 'fos_elastica.finder.search.post');
            $projects = $this->tryCache($search, 'projects', 'ArrayCollection<Aldor\PortfolioBundle\Entity\Project>', 'fos_elastica.finder.search.project');
            $photoCategories = $this->tryCache($search, 'photo_catgory', 'ArrayCollection<Aldor\GalleryBundle\Entity\PhotoCategory>', 'fos_elastica.finder.search.photo_category');
            $photos = $this->tryCache($search, 'photos', 'ArrayCollection<Aldor\GalleryBundle\Entity\Photo>', 'fos_elastica.finder.search.photo');

        } catch (\Exception $err) {
            $logger = $this->get('logger');
            $logger->error('Elastica error'. $err);

        }



        $posts = $this->m_paginator->paginate($posts, $this->get('request')->query->get('strona', $page), $this->container->getParameter('max_post_on_page'));
        $projects = $this->m_paginator->paginate($projects, $this->get('request')->query->get('strona', $page), $this->container->getParameter('max_post_on_page'));
        $posts->setUsedRoute('inftech_search');
        $posts->setParam('search', '*'.$search.'*');
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle('Szukaj - mkaciuba.pl')
            ->addMeta('name', 'description', 'Marcin Kaciuba - blog o tematyce IT i fotografii')
            ->addMeta('property', 'og:title', 'Szukaj - mkaciuba.pl')
            ->addMeta('property', 'og:type', 'blog')
            ->addMeta('property', 'og:description', 'Marcin Kaciuba - blog o tematyce IT i fotografii');
        ;

        return $this->render('AldorInftechBundle:Search:index.html.twig', array(
            'posts'      => $posts,
            'photoCategories' => $photoCategories,
            'projects'       => $projects,
            'resultCount' => count($posts) + count($photoCategories) + count($projects) + count($photos),
            'photos' => $photos,
            'search'   => $search
        ), $res);


    }
    private function tryCache($search, $cacheKey, $entity, $finderService) {
        $cacheKey .= $search;
        $objects = $this->m_cacheManager->get($cacheKey, $entity);
        if (!$objects) {
            $finder = $this->container->get($finderService);
            $objects = $finder->find($search);
            $this->m_cacheManager->put($cacheKey, $objects, 600, 'list');
        }
        return $objects;

    }

    public function createFormAction($name = '', $value = null) {
        $form = $this->container->get('form.factory')->createNamed($name, 'form', null, array(
            'method' => 'GET',
            'action' => $this->generateUrl('inftech_search'),
            'csrf_protection' => false
        ))
            ->add('search', 'text', array('label' => 'szukaj'))
            ->add('szukaj', 'submit');
            // ->getForm();
        return $this->render('AldorInftechBundle:Menu:search.html.twig', array(
            'form'      => $form->createView(),
            'value'   => $value,
            'placeholder' => 'szukaj...'
        ));
    }

}

