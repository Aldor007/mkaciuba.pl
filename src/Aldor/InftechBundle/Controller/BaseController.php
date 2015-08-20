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
class BaseController extends Controller
{
    /**
     * Lists all Post entities.
     *
     */

    protected function construct() {
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

    protected function _prepareResponse($etag, $modifed = null, $expires = " +300 seconds", $s_max_age = 600, $max_age = 300 ) {
        $response = new Response();
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

    protected function _isNotModified($response) {
        $req = $this->getRequest();
       
        if (!$this->getRequest()->headers->has('x-regenerate') && $response->isNotModified($this->get('request'))) {
            return true;
        }
        return false;

    }

    protected  function _setSeoData($title, $description, $keywords = null, $canonical = null, $entry = null) {

        $description = html_entity_decode($description, ENT_QUOTES, 'utf-8');
        $description = preg_replace('/<.*?>/', '', $description);
        $description = str_replace("\xC2\xA0",' ',$description);

        $seoPage = $this->container->get('sonata.seo.page');
        $defualtMetas = $seoPage->getMetas();
        if (strlen($description) < 30) {
            $description .= $defualtMetas['name']['description'][0];
        }
        $seoPage
            ->setTitle($title.' | mkaciuba.pl')
            ->addMeta('name', 'description', $description)
            ->addMeta('property', 'og:title', $title.' | mkaciuba.pl')
            ->addMeta('property', 'og:type', 'blog')
            ->addMeta('property', 'og:description', $description);
           

        if ($entry) {
             $seoType = $entry->getSeoType();
            if ($seoType == 'gallery') {
                $seoPage->addMeta('property', 'og:type', 'website');
                $i = 1;
                $seoPage->addMeta('name', 'twitter:card', 'gallery');
                $images = $entry->getPhotos();
                foreach ($images as $img) {
                    if ($i  > 4) {
                        break;
                    }
                    $provider = $this->container->get($img->getImage()->getProviderName());
                    $seoPage->addMeta('name', 'twitter:image'.$i++, $provider->generatePublicUrl($img->getImage(), $provider->getFormatName($img->getImage(), 'big')));

                }
            } else {
                $seoPage->addMeta('name', 'twitter:card', 'summary');
                $tags = $entry->getTags();
                $tagsName = array();
                foreach ($tags as $tag) {
                    $tagsName[] = $tag->getName();
                }
                $seoPage->addMeta('property', 'article:tag', $tagsName);
                $seoPage->addMeta('property', 'article:section', $entry->getCategory());
                $seoPage->addMeta('property', 'article:published_time', $entry->getDate()->format('Y-m-dh:i:sT'));



                if ($entry->getMedia()) {
                    $provider = $this->container->get($entry->getMedia()->getProviderName());
                    $imgUrl = $provider->generatePublicUrl($entry->getMedia(), $provider->getFormatName($entry->getMedia(), 'big'));
                    $seoPage->addMeta('property', 'og:image', $imgUrl);
                    $seoPage->addMeta('name', 'twitter:image', $imgUrl);
                }
            }
            $seoPage->addMeta('name', 'twitter:site', '@mkaciuba_pl');
            $seoPage->addMeta('name', 'twitter:title', $title);
            $seoPage->addMeta('name', 'twitter:decription', $description);
            $seoPage->addMeta('name', 'twitter:creator', '@mkaciuba_pl');

            
        }

        if ($canonical) {
            $seoPage->setLinkCanonical($canonical);
            $seoPage->addMeta('property', 'og:url', $canonical);
        }

        if ($keywords) {
            $seoPage->addMeta('name', 'keywords', $keywords);
        }
    }

    protected function tryCache($cacheKey, $entity, $ttl = 600) {
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
