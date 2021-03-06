<?php

namespace Aldor\GalleryBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PhotoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PhotoRepository extends EntityRepository
{

    public function getRecent($max)    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.image', 'i')
            ->leftJoin('p.categories', 'c')
            -> add('orderBy', 'p.date DESC')
            ->setMaxResults($max);
        $query = $qb->getQuery();
       $query->useResultCache(true, 360, 'getRecentPhotos'.$max);
        return $query->getResult();

    }
    public function getRecentFromGallery($categories, $gallerySlug, $max = 4)    {

        $categoriesid = array();
        foreach($categories as $cat) {
            $categoriesid[] = $cat->getId();
        }

        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.image', 'i')
            ->innerJoin('p.categories', 'c', 'where', 'c.id in (:categories)')
            -> add('orderBy', 'p.id DESC')
            ->setParameter('categories', $categoriesid)
            ->setMaxResults($max);
        $query = $qb->getQuery();
       $query->useResultCache(true, 360, 'getRecentPhotos'.$max.'-'.$gallerySlug);
        return $query->getResult();

    }
    public function getFromCategory($category)    {
        $qb = $this->createQueryBuilder('p')
            ->select('p', 'i')
            ->leftJoin('p.image', 'i')
            ->leftJoin('p.categories', 't')
            ->innerJoin('p.categories', 'c', 'where', 'c.id in (:categories)')
            ->setParameter('categories', $category)
            -> add('orderBy',  'p.sequence DESC, p.date DESC');
        $query = $qb->getQuery();
       $query->useResultCache(true, 360, 'getPhotos'.$category);
        return $query->getResult();

    }

    public function getMaxSequenceByCategory($categoriesid)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('max(p.sequence)')
            ->innerJoin('p.categories', 'c', 'where', 'c.id in (:categories)')
            ->setParameter('categories', $categoriesid)
            ->setMaxResults(1)
            ;
        $query = $qb->getQuery();
       $query->useResultCache(true, 360, 'getMax'.$categoriesid);
       $result = $query->getResult();
        return  $result[0][1];
        # code...
    }

}
