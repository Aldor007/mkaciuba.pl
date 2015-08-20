<?php

namespace Aldor\GalleryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Aldor\GalleryBundle\Entity\Gallery;
use Aldor\GalleryBundle\Form\GalleryType;
use Symfony\Component\HttpFoundation\Response;
use Aldor\InftechBundle\Controller\BaseController as BaseController;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;


/**
 * Gallery controller.
 *
 */
    /**
     * @Cache(expires="tomorrow", public=true)
     */

class GalleryController extends BaseController
{
    /**
     * Lists all Post entities.
     *
     */
    /**
     *  @Cache(expires="+600 seconds", public=true)
     */
    public function indexAction($slug)
    {
        $this->construct();

        $gallery =  $this->m_em->getRepository('AldorGalleryBundle:Gallery')->findOneBySlug($slug);
        if (!$gallery) {
            throw $this->createNotFoundException('Unable to find gallery .');
        }

        $categories = $this->m_em->getRepository('AldorGalleryBundle:PhotoCategory')->getFromGallery($gallery);

        if (!$categories) {
            throw $this->createNotFoundException('Unable to find categories .');
        }

        $description = "Galeria zdjeć ".$gallery->getName();
        if ($gallery->getDescription()) {
            $description = $gallery->getDescription();
        }

        $title = $gallery->getName()." | Galeria fotografii";
        $keywords = '';

        if ($gallery->getKeywords()) {
            $keywords = $gallery->getKeywords();
        } else {
            foreach ($categories as $key) {
                $keywords .= ' '.$key->getName();
            }
        }
        $this->_setSeoData($title, $description, $keywords);

        $countCategories = count($categories);

        if ($countCategories) {
            $etag = md5("photos:".$countCategories.':{:{'.$gallery->getSlug());
            $res = $this->_prepareResponse($etag, null, '+20000 seconds', 2000);
            if ($this->_isNotModified($res)) {
                return $res;
            }
        }

        return $this->render('AldorGalleryBundle:Gallery:index.html.twig', array(
            'categories'      => $categories,
            'gallery' => $gallery,
        ), $res);
    }
}
