<?php

namespace Aldor\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializationContext;


use Aldor\BlogBundle\Entity\Post;
use Aldor\BlogBundle\Form\PostType;
use Redis;
use Aldor\InftechBundle\Cache\CacheManager;
use Aldor\InftechBundle\Controller\BaseController as BaseController;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;



/**
 * Blog controller.
 *
 */
class BlogController extends BaseController
{
    /**
     * Lists all Post entities.
     *
     */
    private function _getCategory($slug) {

        $category = null;
        $categoryString = null;
        $cache_key = CacheManager::KEY_POSTCATEGORY.$slug;
        $category = $this->m_cacheManager->get($cache_key, 'Aldor\BlogBundle\Entity\PostCategory');
        if (!$category) {
            $category = $this->m_em->getRepository('AldorBlogBundle:PostCategory')->findOneBySlug($slug);
            $this->m_cacheManager->put($cache_key, $category, 6000, 'list');
        }
        return $category;

    }

    private function _getAllPostsFromCategory($category) {
        $postString = false;
        $cache_key = CacheManager::KEY_POSTS_CATEGORY.$category->getSlug();
        if (!$category) {
            throw $this->createNotFoundException('Unable to find Post entity.');
            return;
        }
        $query_result = $this->m_cacheManager->get($cache_key, 'ArrayCollection<Aldor\BlogBundle\Entity\Post>', 'json');
        if (!$query_result){
            $query_result = $this->m_em->getRepository('AldorBlogBundle:Post')->getAllPostsFromCategory($category);
            $this->m_cacheManager->put($cache_key, $query_result, 6000, 'list');
        }
        return $query_result;

    }

    public function postAction($year, $month, $title)
    {
        $this->construct();
        $etag = 'cas';
        $res = new Response();
        $cache_key  = CacheManager::KEY_POST.$year.'/'.$month.'/'.$title;
        $jsonData = $this->m_cacheManager->get($cache_key);
        if ($jsonData) {
            $post = $this->get('serializer')->deserialize($jsonData, 'Aldor\BlogBundle\Entity\Post', 'json');

        } else {
            $post = $this->m_em->getRepository('AldorBlogBundle:Post')->findOneByUrl($year.'/'.$month.'/'.$title);
            if ($post && $post->getPublic()) {
                $this->m_cacheManager->put($cache_key, $post, 6000, 'details');
            } else {
                throw $this->createNotFoundException('Unable to find Post entity '.$title );
            }
        }
        $etag = md5("post:$year:$month:{$post->getModified()->format('Y-m-d H:i:s')}:");
        $res = $this->_prepareResponse($etag, $post->getModified(), "+1080 seconds", 1080);

        $title = $post->getTitle().' - '.$post->getCategory()->getName().' | Blog';

        $url = split('/', $post->getUrl());
        $request = $this->container->get('request');
        $canonical = $this->get('router')->generate($request->attributes->get('_route'), array('year' => $url[0], 'month' => $url[1],'title' => $url[2]), true);
        $this->_setSeoData($title, $post->getDescription(), $post->getKeywords(), $canonical, $post);
        if ($this->_isNotModified($res)) {
            return $res;
        }
        return $this->render('AldorBlogBundle:Blog:post.html.twig', array(
            'post'      => $post,
        ), $res);
    }


    public function postCategoryAction($slug, $page=0)
    {
        $this->construct();
        $etag = 'cas';
        $category = $this->_getCategory($slug);
        if(!$category) {
            throw $this->createNotFoundException('Unable to find category entity '.$slug);
        }
        $res = new Response();
        $posts = $this->_getAllPostsFromCategory($category);

        $description = 'Lista postów z kategorii '.$category->getName();
        $title = $category->getName(). ' | Blog';
        $request = $this->container->get('request');

        $canonical = $this->get('router')->generate($request->attributes->get('_route'), array('slug' => $slug), true);

        $this->_setSeoData($title, $description, null, $canonical);

        $cache_key = CacheManager::KEY_POSTS_CATEGORY.$category->getSlug();
        if (count($posts)) {
            $etag = md5("post:$slug:$page:".$cache_key);
            $res = $this->_prepareResponse($etag, null, "+1880 seconds",1080);
            if ($this->_isNotModified($res)) {
                return $res;
            }

        }
        $posts = $this->m_paginator->paginate($posts, $this->get('request')->query->get('strona', $page), $this->container->getParameter('max_post_on_page'));

        $posts->setUsedRoute('blog_category');

        return $this->render('AldorBlogBundle:Blog:index.html.twig', array(
            'posts'      => $posts,
            'category' => $category,

            ), $res);
    }

    public function indexAction($page=1)
    {
        $this->construct();
        $etag = 'cas';
        $res = new Response();
        $cacheKey = CacheManager::KEY_POSTS_ALL;
        $posts = $this->m_cacheManager->get($cacheKey, 'ArrayCollection<Aldor\BlogBundle\Entity\Post>');
        if(!$posts) {
            $posts = $this->m_em->getRepository('AldorBlogBundle:Post')->getAllPostsFromCategories();
            $this->m_cacheManager->put($cacheKey, $posts, 6000, 'list');
        }

        $request = $this->container->get('request');
        $canonical = $this->get('router')->generate($request->attributes->get('_route'), array('page' => $page), true);

        $title  = 'Blog';
        $description = 'Marcin Kaciuba | blog o tematyce IT i fotografii';

        $this->_setSeoData($title, $description, null, $canonical);

        if (count($posts)) {
            $mobileAgent = ($this->m_deviceDetector->isMobile() || $this->m_deviceDetector->isTablet());
            $etag = md5($mobileAgent."post:$page:".$this->m_cacheManager->get($cacheKey));
            $res = $this->_prepareResponse($etag, null, "+1980 seconds", 1980);
            if ($this->_isNotModified($res)) {
                return $res;
            }
        }
        $posts = $this->m_paginator->paginate($posts, $this->get('request')->query->get('strona', $page), $this->container->getParameter('max_post_on_page'));
        $posts->setUsedRoute('blog_home');


        return $this->render('AldorBlogBundle:Blog:index.html.twig', array(
            'posts'      => $posts,
        ), $res);

    }
    public function tagPostsAction($slug, $page) {

        $this->construct();
        $etag = 'cas';
        $res = new Response();
        $tag = $this->m_em->getRepository('AldorBlogBundle:Tag')->findOneBySlug($slug);
        if(!$tag) {
            throw $this->createNotFoundException('Unable to find tag entity '.$slug);
        }

        $cacheKey = CacheManager::KEY_POSTS_TAG.$slug;
        $posts = $this->m_cacheManager->get($cacheKey, 'ArrayCollection<Aldor\BlogBundle\Entity\Post>');
        if(!$posts) {
            $posts = $this->m_em->getRepository('AldorBlogBundle:Post')->getAllPostsFromTag($tag);
            $this->m_cacheManager->put($cacheKey, $posts, 6000, 'list');
        }

        $seoPage = $this->container->get('sonata.seo.page');

        $title = 'Tag '. $tag->getName().' | Blog';
        $description = 'Lista postów z tagu - '.$tag->getName();

        $request = $this->container->get('request');
        $canonical = $this->get('router')->generate($request->attributes->get('_route'), array('slug' => $slug, 'page' => $page), true);

        $this->_setSeoData($title, $description, null, $canonical);

        if (count($posts)) {
            $etag = md5("post:$slug:$page".$this->m_cacheManager->get($cacheKey));
            $res = $this->_prepareResponse($etag, null, "+2080 seconds", 2080);
            if ($this->_isNotModified($res)) {
                return $res;
            }

        }
        $posts = $this->m_paginator->paginate($posts, $this->get('request')->query->get('strona', $page), $this->container->getParameter('max_post_on_page'));
        $posts->setUsedRoute('blog_tag');


        return $this->render('AldorBlogBundle:Blog:index.html.twig', array(
            'posts'     => $posts,
            'tag'       => $tag
        ), $res);


    }
    public function archiveAction($year, $month, $page) {

        $this->construct();
        $res = new Response();
        $res->setExpires((new \DateTime())->modify('+ 3000 seconds'));
        $cacheKey = CacheManager::KEY_POSTS_ARCHIVE.$month.$year;
        $posts = $this->m_cacheManager->get($cacheKey, 'ArrayCollection<Aldor\BlogBundle\Entity\Post>');
        if (!$posts) {
            $posts = $this->m_em->getRepository('AldorBlogBundle:Post')->getPostsArchive($month, $year);
            $this->m_cacheManager->put($cacheKey, $posts, 6000, 'list');

        }

        $title = "Blog | Archiwum  $month $year ";
        $description =  "Blog - Archiwum z $month $year";
        $this->_setSeoData($title, $description);
        if (count($posts)) {
            $etag = md5("post:$year:$month:$page".$this->m_cacheManager->get($cacheKey));
            $res = $this->_prepareResponse($etag, null, "+2080 seconds", 2080);
            if ($this->_isNotModified($res)) {
                return $res;
            }
        }
        $posts = $this->m_paginator->paginate($posts, $this->get('request')->query->get('strona', $page), $this->container->getParameter('max_post_on_page'));
        $posts->setUsedRoute('blog_archive');




        return $this->render('AldorBlogBundle:Blog:archive.html.twig', array(
            'posts'  => $posts,
            'year'   => $year,
            'month'  => $month,
        ), $res);

    }

    public function feedAction() {

        $this->construct();
        $res = new Response();
        $etag = 'cas';
        $cacheKey = CacheManager::KEY_POSTS_ALL;
        $posts = $this->m_cacheManager->get($cacheKey, 'ArrayCollection<Aldor\BlogBundle\Entity\Post>');
        if(!$posts) {
            $posts = $this->m_em->getRepository('AldorBlogBundle:Post')->getAllPostsFromCategories();
            $this->m_cacheManager->put($cacheKey, $posts, 6000, 'list');
        }
        if (count($posts)) {
            $etag = md5("post:{".count($posts)."}:".$this->m_cacheManager->get($cacheKey));
            $res->setETag($etag);
            $res->setPublic();
            if ($res->isNotModified($this->get('request'))) {
                $res->headers->set('Content-Type', 'application/rss+xml');
                return $res;
            }
        }
        $feed = $this->get('eko_feed.feed.manager')->get('posts');
        $feed->addFromArray($posts);
        $res->setContent($feed->render('atom'));
        $res->setCharset('UTF-8');
        $res->headers->set('Content-Type', 'application/xml');
        return $res;
    }
}
