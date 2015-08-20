<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\FormatterBundle\SonataFormatterBundle(),
            new Sonata\MediaBundle\SonataMediaBundle(),
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\ClassificationBundle\SonataClassificationBundle(),



            new Sonata\SeoBundle\SonataSeoBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),


            new Sonata\NotificationBundle\SonataNotificationBundle(),
            new Sonata\PageBundle\SonataPageBundle(),

            new SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new Bmatzner\FontAwesomeBundle\BmatznerFontAwesomeBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle(),


            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),


            new Snc\RedisBundle\SncRedisBundle(),
            new \Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Eko\FeedBundle\EkoFeedBundle(),
            new FOS\HttpCacheBundle\FOSHttpCacheBundle(),

            new Nelmio\SecurityBundle\NelmioSecurityBundle(),
            new Liuggio\StatsDClientBundle\LiuggioStatsDClientBundle(),

            new Webfactory\Bundle\ExceptionsBundle\WebfactoryExceptionsBundle(),
            new SunCat\MobileDetectBundle\MobileDetectBundle(),
            new Socloz\MonitoringBundle\SoclozMonitoringBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new Happyr\CloudFlareBundle\HappyrCloudFlareBundle(),
            new Leezy\PheanstalkBundle\LeezyPheanstalkBundle(),




            new FOS\RestBundle\FOSRestBundle(),



            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
            // new Symfony\Cmf\Bundle\CoreBundle\CmfCoreBundle(),
            // new Symfony\Cmf\Bundle\MenuBundle\CmfMenuBundle(),
            // new Symfony\Cmf\Bundle\ContentBundle\CmfContentBundle(),
            // new Symfony\Cmf\Bundle\TreeBrowserBundle\CmfTreeBrowserBundle(),
            // new Symfony\Cmf\Bundle\BlockBundle\CmfBlockBundle(),
            new Application\Sonata\NotificationBundle\ApplicationSonataNotificationBundle(),
            new Application\Sonata\PageBundle\ApplicationSonataPageBundle(),
            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
            new Application\Sonata\ClassificationBundle\ApplicationSonataClassificationBundle(),
            new Aldor\InftechBundle\AldorInftechBundle(),
            new Aldor\BlogBundle\AldorBlogBundle(),
            new Aldor\GalleryBundle\AldorGalleryBundle(),
            new Aldor\PortfolioBundle\AldorPortfolioBundle(),
            new Aldor\BackendBundle\AldorBackendBundle(),
            new Aldor\ApiBundle\AldorApiBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();

            if (in_array($this->getEnvironment(), array('dev')))
                 $bundles[] = new JMS\DebuggingBundle\JMSDebuggingBundle($this);

        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
    protected function getContainerBaseClass()
    {
        if (in_array($this->getEnvironment(), array('dev'))) {
                return '\JMS\DebuggingBundle\DependencyInjection\TraceableContainer';
            }

        return parent::getContainerBaseClass();
    }
}
