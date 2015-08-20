<?php
// src/Aldor/Inftech/Utils/Jobeet.php
namespace Aldor\InftechBundle\Utils;
use Cocur\Slugify\Slugify;


class Inftech
{
 static public function slugify($text)
 {
     $slugify = new Slugify();
     return $slugify->slugify($text);

 }
}

?>
