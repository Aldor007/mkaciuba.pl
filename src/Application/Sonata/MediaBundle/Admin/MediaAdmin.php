<?php

namespace Application\Sonata\MediaBundle\Admin;

use Sonata\MenuAdmin\Admin\BaseAdmin;

class MediaAdmin extends BaseAdmin
{
// setup the default sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'id'
  );
