<?php

namespace Aldor\InftechBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializationContext;

use SunCat\MobileDetectBundle\Helper\DeviceView;


use Aldor\BlogBundle\Entity\Post;
use Aldor\BlogBundle\Form\PostType;
use Redis;
use Aldor\InftechBundle\Cache\CacheManager;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * Base controller.
 *
 */
interface BaseControllerInterface
{
    /**
     * Lists all Post entities.
     *
     */

     function construct() {
        if($this->m_wasContruct == true) {
            return;
        }
        $this->m_wasContruct = true;
        $this->m_em = $this->getDoctrine()->getManager();
        $this->m_logger = $this->get('logger');
        $this->m_paginator = $this->get('knp_paginator');
        $this->m_cacheManager = $this->get('aldor.inftech.cachemanager');
        $this->m_deviceDetector = $this->get('mobile_detect.mobile_detector');
        $this->m_deviceView = $this->get('mobile_detect.device_view');

    }

     function _prepareResponse($etag, $modifed = null, $expires = " +300 seconds", $response = null, $s_max_age = 600, $max_age = 300 ) {
        if ($response == null) {
            $response = new Response();
        }
        $currentTime = new \DateTime();
        $req = $this->getRequest();
        $deviceView =$this->m_deviceView->getViewType(); 
        $response->setExpires($currentTime->modify($expires));
        $response->setETag(md5($etag.$deviceView.$req->headers->get('x-forwarded-proto')));
        $response->setPublic();
        $response->setMaxAge($max_age);
        $response->setSharedMaxAge($s_max_age);
        if ($modifed) {
            $response->setLastModified($modifed);
        }
        $response->setVary(array('x-device-type', 'x-forwarded-proto'));
        return $response;

    }

     function _isNotModified($response) {
        $deviceView =$this->m_deviceView->getViewType(); 
        $req = $this->getRequest();
        $cookies = $req->cookies;
        if ($cookies->has(DeviceView::COOKIE_KEY)) {
            if ($deviceView != $cookies->get(DeviceView::COOKIE_KEY)) {
                return false;
            }

        } else {
            return false;
        }


        if (!$this->getRequest()->headers->has('x-regenerate') && $response->isNotModified($this->get('request'))) {
            return true;
        }
        return false;

    }

      function _setSeoData($title, $description, $keywords = null, $canonical = null) {

        $description = html_entity_decode($description, ENT_QUOTES, 'utf-8');
        $description = preg_replace('/<.*?>/', '', $description);
        $description = str_replace("\xC2\xA0",' ',$description);

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($title)
            ->addMeta('name', 'description', $description)
            ->addMeta('property', 'og:title', $title)
            ->addMeta('property', 'og:type', 'blog')
            ->addMeta('property', 'og:description', $description);

        if ($canonical) {
            $seoPage->setLinkCanonical($canonical);
        }

        if ($keywords) {
            $seoPage->addMeta('name', 'keywords', $keywords);
        }
    }

     function tryCache($cacheKey, $entity, $ttl = 600) {
        $objects = $this->m_cacheManager->get($cacheKey, $entity);
        if (!$objects) {
            $this->m_cacheManager->put($cacheKey, $objects, 600, 'list');
        }
        return $objects;

    }

    protected $m_logger;
    protected $m_em;
    protected $m_paginator;
    protected $m_wasContruct;
    protected $m_cacheManager;
    protected $m_deviceDetector;

};
