<?php

namespace Aldor\GalleryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Aldor\GalleryBundle\Entity\PhotoCategory;
use Aldor\GalleryBundle\Form\PhotoCategoryType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Response;
use Aldor\InftechBundle\Cache\CacheManager;
use Aldor\InftechBundle\Controller\BaseController as BaseController;

/**
 * photo controller.
 *
 */
class PhotoCategoryController extends BaseController
{
    /**
     * Lists all photo entities.
     *
     */
    public function indexAction($galleryslug, $photocategoryslug)
    {
        $this->construct();
        $res = null;


        $gallery =  $this->m_em->getRepository('AldorGalleryBundle:Gallery')->findOneBySlug($galleryslug);

        if (!$gallery) {
            throw $this->createNotFoundException('Unable to find gallery .');
        }
        $category = $this->m_em->getRepository('AldorGalleryBundle:PhotoCategory')->findOneBy(array('gallery' => $gallery, 'slug' => $photocategoryslug));

        if (!$category) {
            throw $this->createNotFoundException('Unable to find category .');
        }
        $mobileAgent = ($this->m_deviceView->isMobileView() || $this->m_deviceView->isTabletView());

        $cacheKey = CacheManager::KEY_PHOTOS.$category->getId();
        $photos = $this->m_cacheManager->get($cacheKey, 'ArrayCollection<Aldor\GalleryBundle\Entity\Photo>');

        if(!$photos) {
            $photos = $this->m_em->getRepository('AldorGalleryBundle:Photo')->getFromCategory($category);
            $this->m_cacheManager->put($cacheKey, $photos, 6000, 'list');
        }

        $template = 'AldorGalleryBundle:PhotoCategory:index.html.twig';
        if ($mobileAgent) {
            $template = 'AldorGalleryBundle:PhotoCategory:index-mobile.html.twig';
        }

        $photosCount = count($photos);
        if ($photosCount > 100) {
            $template = 'AldorGalleryBundle:PhotoCategory:index-mobile.html.twig';
        }

        $description = "Galeria zdjeć ".$gallery->getName()." z kategorii ".$category->getName();

        if ($category->getDescription()) {
            $description = $category->getDescription();
        }

        $title = $category->getName()." | Galeria ".$gallery->getName();

        $request = $this->container->get('request');
        $canonical = $this->get('router')->generate($request->attributes->get('_route'), $request->attributes->get('_route_params'), true);

        $this->_setSeoData($title, $description, $category->getKeywords(), $canonical, $category);
        
        if ($photosCount) {
            $etag = md5($cacheKey.$category->getSlug().$mobileAgent.$template.$photosCount);
            $res = $this->_prepareResponse($etag, null, '+20000 seconds', null, 20000);
            if ($this->_isNotModified($res)) {
                return $res;
            }
            
        }

        return $this->render($template, array(
            'category'      => $category,
            'gallery' => $gallery,
            'photos' => $photos
        ), $res);
    }
}
