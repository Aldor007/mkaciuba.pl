<?php

namespace Aldor\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
    function getRecentPosts($max = 5)
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.media', 'i')
            ->leftJoin('p.category', 'c')
             ->leftJoin('p.user', 'u')
             ->leftJoin('p.tags', 't')
            ->where('p.publicationDateStart < :pubDate')
            ->andWhere('p.public = true')
            -> add('orderBy', 'p.date DESC')
            ->setParameter('pubDate', new \DateTime())
            ->distinct()
            ->setMaxResults($max);
        $query = $qb->getQuery();
        $query->useResultCache(true, 360, 'getRecentPostsSservice'.$max);
        return $query->getResult();

    }
    function getPostsCount()
    {
        $qb = $this->createQueryBuilder('p')->where('p.date< :date')
            ->setParameter('date',date("Y-m-d H:i:s"))
            ->andWhere('p.publicationDateStart < :pubDate')
            ->andWhere('p.public = true')
            ->setParameter('pubDate', new \DateTime());
        $query = $qb->getQuery();
        $query->useResultCache(true, 360, 'getLastPosts');
        return count($query->getResult());

    }
     function getRecentPostsFromCategory($category, $max = 4)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p', 'i', 'c', 'u', 't')
            ->leftJoin('p.media', 'i')
            ->leftJoin('p.category', 'c')
             ->leftJoin('p.user', 'u')
             ->leftJoin('p.tags', 't')
            ->where('p.category = :category')
            ->andWhere('p.publicationDateStart < :pubDate')
            ->andWhere('p.public = true')
            ->setParameter('pubDate', new \DateTime())
            ->setParameter('category', $category->getId())
            ->distinct()
            -> add('orderBy', 'p.date DESC')
            ->setMaxResults($max);
        $query = $qb->getQuery();
        $query->useResultCache(true, 360, 'getRecentgry_'.$category->getSlug().'_'.$max);
        return $query->getResult();

    }
    function getAllPostsFromCategory($category) {

        $qb = $this->createQueryBuilder('p')
            ->select('p', 'i', 'c', 'u', 't')
            ->leftJoin('p.media', 'i')
             ->leftJoin('p.user', 'u')
             ->leftJoin('p.category', 'c')
             ->leftJoin('p.tags', 't')
            ->where('p.category = :category')
            ->andWhere('p.publicationDateStart < :pubDate')
            ->andWhere('p.public = true')
            ->setParameter('pubDate', new \DateTime())
            ->setParameter('category', $category->getId())
            -> add('orderBy', 'p.date DESC');
        $query = $qb->getQuery();
        $query->useResultCache(true, 360, 'getAllPostsFromCategory_'.$category->getId());
        return $query->getResult();


    }
    function getOneByUrl($url) {

        $qb = $this->createQueryBuilder('p')
            ->select('p', 'i', 'c', 'u', 't')
            ->leftJoin('p.media', 'i')
             ->leftJoin('p.user', 'u')
             ->leftJoin('p.category', 'c')
             ->leftJoin('p.tags', 't')
            ->where('p.url = :url')
            ->andWhere('p.publicationDateStart < :pubDate')
            ->andWhere('p.public = true')
            ->setParameter('pubDate', new \DateTime())
        ->setParameter('url', $url)
            ->distinct()
                ->setMaxResults(1);
        $query = $qb->getQuery();
        $query->useResultCache(true, 360, 'findPostsByURL_'.$url);
        return $query->getResult();


    }
    function getAllPostsFromCategories() {

        $qb = $this->createQueryBuilder('p')
            ->select('p', 'i', 'c', 'u', 't')
            ->leftJoin('p.media', 'i')
             ->leftJoin('p.user', 'u')
             ->leftJoin('p.category', 'c')
             ->leftJoin('p.tags', 't')
            ->andWhere('p.publicationDateStart < :pubDate')
            ->andWhere('p.public = true')
            ->setParameter('pubDate', new \DateTime())
            -> add('orderBy', 'p.date DESC');
        $query = $qb->getQuery();
        $query->useResultCache(true, 360, 'getAllPostsFromCategories');
        return $query->getResult();


    }

    public function getAllPostsFromTag($tag) {
        $qb = $this->createQueryBuilder('p')
            ->select('p', 'i', 'c', 'u', 't')
            ->leftJoin('p.media', 'i')
             ->leftJoin('p.user', 'u')
             ->leftJoin('p.category', 'c')
             ->innerJoin('p.tags', 't', 'WITH', 't.id = :tag')
            ->setParameter('tag', $tag->getId())
            ->andWhere('p.publicationDateStart < :pubDate')
            ->andWhere('p.public = true')
            ->setParameter('pubDate', new \DateTime())
            -> add('orderBy', 'p.date DESC');
        $query = $qb->getQuery();
        $query->useResultCache(true, 360, 'getAllPostsFromTag_'.$tag->getId());
        return $query->getResult();
    }

    public function getPostsFromTags($tags) {
        $tagsid = array();
        foreach($tags as $tag) {
            $tagsid[] = $tag->getId();
        }
        $qb = $this->createQueryBuilder('p')
            ->select('p', 'i', 'c', 'u', 't')
            ->leftJoin('p.media', 'i')
             ->leftJoin('p.user', 'u')
             ->leftJoin('p.category', 'c')
             ->innerJoin('p.tags', 't', 'WITH', 't.id in (:tag)')
            ->setParameter('tag', $tagsid)
            ->andWhere('p.publicationDateStart < :pubDate')
            ->andWhere('p.public = true')
            ->setParameter('pubDate', new \DateTime())
            -> add('orderBy', 'p.date DESC')
            ->setMaxResults(4);

        $query = $qb->getQuery();
        $query->useResultCache(true, 760, 'getPostFromTags_'.implode($tagsid));
        return $query->getResult();
    }
    public function getRelatedPosts($tags, $url) {
        $tagsid = array();
        foreach($tags as $tag) {
            $tagsid[] = $tag->getId();
        }

        $qb = $this->createQueryBuilder('p')
            ->select('p', 'i', 'c', 'u', 't')
            ->distinct('p.tags')
            ->leftJoin('p.media', 'i')
             ->leftJoin('p.user', 'u')
             ->leftJoin('p.category', 'c')
             ->innerJoin('p.tags', 't', 'WITH', 't.id in (:tag)')
            ->setParameter('tag', $tagsid)
            ->andWhere('p.publicationDateStart < :pubDate and p.url != :url')
            ->andWhere('p.public = true')
            ->setParameter('pubDate', new \DateTime())
            ->setParameter('url', $url)
            -> add('orderBy', 'p.date DESC')
            -> add('groupBy', 'p.id')
            ->setMaxResults(4);

        $query = $qb->getQuery();
        $query->useResultCache(true, 760, 'getRelatedFromTags_'.implode($tagsid));
        return $query->getResult();
    }
    public function getPostsArchive($month, $year) {
        $postDate = new \DateTime();
        $postDate->setDate($year, $month, 1);
        $postDate2 = new \DateTime();
        $postDate2->setDate($year, $month + 1, '1');
        $qb = $this->createQueryBuilder('p')
            ->select('p', 'i', 'c', 'u', 't')
            ->leftJoin('p.media', 'i')
            ->leftJoin('p.user', 'u')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tags', 't')
            ->where('p.date > :postDate and p.date < :postDate2')
            ->setParameter('postDate', $postDate)
            ->andWhere('p.publicationDateStart < :pubDate')
            ->andWhere('p.public = true')
            ->setParameter('pubDate', new \DateTime())
            ->setParameter('postDate2', $postDate2)
            -> add('orderBy', 'p.date DESC');

        $query = $qb->getQuery();
        $query->useResultCache(true, 360, 'getPostsArchive_'.$year.$month);
        return $query->getResult();


    }
}