mkaciuba.pl - HomePage
=========================

Introduction
------------
This repository contains a custom homepage made using Symfony2. Feel free to download and modify. Comments and suggestions appreciated.
Assets like less aren't included becasue I don't have rights to them.

Features
-----

* Use http headers to cache html
* Doctrine ORM cache
* Redis cache for most used objects
* Search form uses ElastiSearch
* SonataNotification with beanstald backend. Used for:
 * cache purge with FOSHttpCacheBundle and in my case cloudflare cache
 * generating thumbnails for images with SonataMediaBundle
* Admin panel with SonataAdminBudle
* Rss with EkoFeedBundle
* custom error pages due to WebFactorExceptionBundle
* Mobile detect with ua-lua (evrything is done in nginx)
* gathering stats using statsd 
* custom seo date for page (SonataSeoBundle)
* supersized gallery for photos
* ajax gallery for photos in blog post
* zip photo upload and then unzip and add to photocategory

License
-------

This page is released under the MIT license. See the included
[LICENSE](LICENSE) file for more information.

