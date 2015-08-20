<?php

namespace Aldor\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;

use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Aldor\InftechBundle\Cache\CacheManager;

class PhotoController extends FOSRestController 
{
    public function getPhotosAction($category_id)
    {
        $this->construct();
        $em = $this->getDoctrine()->getManager();
        
        $req = $this->getRequest();
        $cacheKey = CacheManager::KEY_PHOTOSCATEGORY_API.$category_id.$req->headers->get('x-device-type');
        $photos = $this->m_cacheManager->get($cacheKey);
        if (!$photos) {
            $photos =  $em->getRepository('AldorGalleryBundle:Photo')->getFromCategory($category_id);
            $photos = $this->m_cacheManager->put($cacheKey, $photos, 1000, 'api');
        }

        $etag = md5("photos::".$cacheKey);
        $res = $this->_prepareResponse($etag, null, "+600 seconds", null, 600);
        if ($this->_isNotModified($res)) {
            return $res;
        }
        $res->setContent($photos);
        
        return $res;
    }    

     protected function construct() {
        if($this->m_wasContruct == true) {
            return;
        }
        $this->m_wasContruct = true;
        $this->m_em = $this->getDoctrine()->getManager();
        $this->m_cacheManager = $this->get('aldor.inftech.cachemanager');
        $this->m_deviceDetector = $this->get('mobile_detect.mobile_detector');
    }

    protected function _prepareResponse($etag, $modifed = null, $expires = " +300 seconds", $response = null, $s_max_age = 600, $max_age = 300 ) {
        if ($response == null) {
            $response = new Response();
        }
        $currentTime = new \DateTime();
        $req = $this->getRequest();
        $response->setExpires($currentTime->modify($expires));
        $response->setETag(md5($etag.$req->headers->get('x-device-type').$req->headers->get('x-forwarded-proto')));
        $response->setPublic();
        $response->setMaxAge($max_age);
        $response->setSharedMaxAge($s_max_age);
        if ($modifed) {
            $response->setLastModified($modifed);
        }
        // $response->setVary(array('x-device-type', 'x-forwarded-proto'));
        return $response;
    }

    protected function _isNotModified($response) {
        $req = $this->getRequest();
        if (!$this->getRequest()->headers->has('x-regenerate') && $response->isNotModified($this->get('request'))) {
            return true;
        }
        return false;
    }

    protected $m_em;
    protected $m_wasContruct;
    protected $m_cacheManager;
}