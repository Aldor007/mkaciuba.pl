<?php
// src/Ens/JobeetBundle/Admin/CategoryAdmin.php

namespace Aldor\BackendBundle\Admin;


use Sonata\MediaBundle\Admin\BaseMediaAdmin;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\MediaBundle\Provider\Pool;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection as RouteCollection;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;

class BaseAdmin extends Admin
{
    public $supportsPreviewMode = true;


    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'id'
    );

    protected function _prepareParams() {
        $params = array();
        $params[] = array('x-forwarded-proto' => 'https', 'accept-encoding' => 'gzip', 'User-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36');
        $params[] = array('accept-encoding' => 'gzip', 'User-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36');
        $params[] = array('x-forwarded-proto' => 'https', 'User-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36');
        $params[] = array('x-forwarded-proto' => 'https', 'accept-encoding' => 'gzip', 'User-agent' => 'Mozilla/5.0 (Linux; U; Android 4.0.3; de-ch; HTC Sensation Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30');
        $params[] = array('x-forwarded-proto' => 'https',  'User-agent' => 'Mozilla/5.0 (Linux; U; Android 4.0.3; de-ch; HTC Sensation Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30');
        return $params;

    }

    protected function clearReverseProxyCache($object)
    {
        $fosCacheManager = $this->getConfigurationPool()->getContainer()->get('fos_http_cache.cache_manager');
        $router =  $this->getConfigurationPool()->getContainer()->get('router');
        $this->fosCacheManager = $fosCacheManager;
        $this->cloudFlare = $this->getConfigurationPool()->getContainer()->get('happyr.clourflare.service.cloudflare');

        $params = $this->_prepareParams();
        foreach ($params as $param) {
            $this->_clearReverseProxyCache($param, $object, $router);
        }

        try {
            $fosCacheManager->flush();
        } catch (\Exception $e) {

        }

    }

    protected function _clearReverseProxyCache($param, $object, $router) {
        throw \Exception('Not implemented');
         
    }

    protected function _invalidatePath($url, $headers) {
        $queue = $this->getConfigurationPool()->getContainer()->get('sonata.notification.backend');
        $queue->createAndPublish('aldor_cache_purge', array(
            'url' => $url,
            'headers' => $headers
        ));
    }

}