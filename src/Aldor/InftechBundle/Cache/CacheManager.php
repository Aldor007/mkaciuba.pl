<?php
namespace Aldor\InftechBundle\Cache;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\RequestStack;
use Liuggio\StatsDClient\Factory\StatsdDataFactory;
use Liuggio\StatsDClientBundle\Service\StatsDCollectorService;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\Common\Cache\RedisCache as RedisCache;
use Symfony\Bridge\Monolog\Logger;

class CacheManager {

    public function setRequest(RequestStack $request_stack) {
        $this->request = $request_stack->getCurrentRequest();
    }

    public function __construct(Container $container) {
        $this->cache = $container->get('doctrine_cache.providers.backend_cache');
        $this->serializer = $container->get('serializer');
        $this->logger = $container->get('logger');
        $this->statsdFactory = $container->get('liuggio_stats_d_client.factory');
        $this->statsd = $container->get('liuggio_stats_d_client.service');

    }
    public function get($cache_key, $entity_name = null) {
        $json = $entity = $metric =  false;

        $this->logger->info('CacheManager get key='.$cache_key);
        $metric = $this->statsdFactory->increment('cachemanager.keys.get.'.$this->cleanString($cache_key));
        $this->statsd->send($metric);
        if ($this->request->headers->has('x-regenerate'))  {
            $this->logger->info('CacheManager get key='.$cache_key.' regenerate');
            $metric = $this->statsdFactory->increment('cachemanager.regenerate');
            $this->statsd->send($metric);
            return $json;
        }

        try {
            $json = $this->cache->fetch($cache_key);
            if ($entity_name == null)
                return $json;
        }   catch (\Exception $er) {
            $this->logger->error("CacheManager Exception fetch from cache ".$er);
            $metric = $this->statsdFactory->increment('cachemanager.error');
            $this->statsd->send($metric);
            $this->delete($cache_key);
        }

        if ($json) {
            try {
                $entity = $this->serializer->deserialize($json, $entity_name, 'json');
                $this->logger->info("CacheManager Cache=Hit");
                $metric = $this->statsdFactory->increment('cachemanager.hit');
                $this->statsd->send($metric);
                return $entity;
            } catch (\Exception $exc) {
                $this->logger->error('CacheManager Exception deserialize '.$exc);
                $metric = $this->statsdFactory->increment('cachemanager.error');
                $this->statsd->send($metric);
                $this->delete($cache_key);
            }
        }
        $metric = $this->statsdFactory->increment('cachemanager.miss');
        $this->statsd->send($metric);
        $this->logger->info("CacheManager Cache=Miss");
        return false;

    }


    public function put($cache_key, $entity, $cache_time = 600, $groups = null, $context = null) {
        $this->logger->info('CacheManager set key='.$cache_key);
            $metric = $this->statsdFactory->increment('cachemanager.set');
            $this->statsd->send($metric);
            $metric = $this->statsdFactory->increment('cachemanager.keys.set.'.$this->cleanString($cache_key));
            $this->statsd->send($metric);
        if ($groups == null)
            $groups = "list";
        if ($context == null) {
            $context = SerializationContext::create();
            $context->setGroups(array($groups));
            $context->setSerializeNull(true);
        }
        $serializedEntity = $this->serializer->serialize($entity, 'json', $context);
        $this->cache->save($cache_key, $serializedEntity, $cache_time);
        return $serializedEntity;

    }

    public function delete($cache_key) {

        $this->cache->delete($cache_key);
        return null;
    }
    private function cleanString($stringIn) {
        $string = str_replace(' ', '_', $stringIn); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '_', $string); // Replaces multiple hyphens with single one.
    }
    protected $cache;
    protected $serializer;
    protected $statsdFactory;
    protected $statsd;
    protected $logger;
    protected $request;
    const KEY_POSTS_ALL = 'all_posts';
    const KEY_POSTS_CATEGORY = 'posts_category';
    const KEY_RECENT_POSTS_CATEGORY = 'recent_posts_category';
    const KEY_RECENT_POSTS = 'recent_posts';
    const KEY_POST = 'post_';
    const KEY_POSTCATEGORY = 'postcategory';
    const KEY_POSTS_TAG = 'posts_tag';
    const KEY_POSTS_ARCHIVE = 'posts_archive';
    const KEY_PROJECT_ALL = 'project_all';
    const KEY_PHOTOS = 'photos_';
    const KEY_PHOTOSCATEGORY_API = 'photosapi_';
}


