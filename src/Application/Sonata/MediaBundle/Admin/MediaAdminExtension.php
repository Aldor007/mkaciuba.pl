<?php

/*
 * This file is part of the CKEditorSonataMediaBundle package.
 *
 * (c) La Coopérative des Tilleuls <contact@les-tilleuls.coop>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Admin;

use Sonata\AdminBundle\Admin\AdminExtension;

/**
 * Adds browser and upload routes to the Admin
 *
 * @author Kévin Dunglas <kevin@les-tilleuls.coop>
 */
class MediaAdminExtension extends AdminExtension
{
    /**
     * {@inheritDoc}
     */
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'id'
    );
    public function configureRoutes(\Sonata\AdminBundle\Admin\AdminInterface $admin, \Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        $collection->add('browser', 'browser');
        $collection->add('upload', 'upload');
    }
}
