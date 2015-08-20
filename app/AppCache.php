<?php

require_once __DIR__.'/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use FOS\HttpCacheBundle\SymfonyCache\EventDispatchingHttpCache;
use FOS\HttpCache\SymfonyCache\UserContextSubscriber;


class AppCache extends EventDispatchingHttpCache

{

    protected function getOptions()
    {
        return array(
            'debug'                  => false,
            'default_ttl'            => 60,
            'private_headers'        => array('Authorization', 'Cookie', 'x-device-type'),
            'allow_reload'           => false,
            'allow_revalidate'       => true,
            'stale_while_revalidate' => 2,
            'stale_if_error'         => 60,
        );
    }
}
