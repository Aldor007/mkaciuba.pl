<?php
namespace Application\Sonata\PageBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Model\PageInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Tests\Unit\Doctrine\Orm\ContentRepositoryTest;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
class LoadPageData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;
    public function getOrder()
    {
        return 4;
    }
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        $site = $this->createSite();
        $this->createGlobalPage($site);
        $this->createHomePage($site);
        $this->createContactPage($site);
        $this->createCookiesPage($site);
        $this->create404ErrorPage($site);
        $this->create500ErrorPage($site);
        $this->createAboutPage($site);
    }
    /**
     * @return SiteInterface $site
     */
    public function createSite()
    {
        $site = $this->getSiteManager()->create();
        $site->setHost('mkaciuba.dev');
        $site->setEnabled(true);
        $site->setName('mkaciuba.dev');
        $site->setEnabledFrom(new \DateTime('now'));
        $site->setEnabledTo(new \DateTime('+10 years'));
        $site->setRelativePath("");
        $site->setIsDefault(true);
        $this->getSiteManager()->save($site);
        return $site;
    }
    /**
     * @param SiteInterface $site
     */
    public function createAboutPage(SiteInterface $site)    {
        $pageManager = $this->getPageManager();
        $blogIndex = $pageManager->create();
        $blogIndex->setSlug('omnie');
        $blogIndex->setUrl('/omnie');
        $blogIndex->setName('O mnie');
        $blogIndex->setTitle('O mnie');
        $blogIndex->setEnabled(true);
        $blogIndex->setDecorate(0);
        $blogIndex->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $blogIndex->setTemplateCode('default-inftech');
        $blogIndex->setRouteName(PageInterface::PAGE_ROUTE_CMS_NAME);
        $blogIndex->setPageAlias('_page_alias_about');
        $blogIndex->setParent($this->getReference('page-homepage'));
        $blogIndex->setSite($site);
        $pageManager->save($blogIndex);
    }
    /**
     * @param SiteInterface $site
     */
    public function createContactPage(SiteInterface $site)   {
        $pageManager = $this->getPageManager();
        $blogIndex = $pageManager->create();
        $blogIndex->setSlug('kontakt');
        $blogIndex->setUrl('/kontakt');
        $blogIndex->setName('Kontakt');
        $blogIndex->setTitle('Kontakt');
        $blogIndex->setEnabled(true);
        $blogIndex->setDecorate(0);
        $blogIndex->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $blogIndex->setTemplateCode('default-inftech');
        $blogIndex->setRouteName(PageInterface::PAGE_ROUTE_CMS_NAME);
        $blogIndex->setPageAlias('_page_alias_contact');
        $blogIndex->setParent($this->getReference('page-homepage'));
        $blogIndex->setSite($site);
        $pageManager->save($blogIndex);
    }
    /**
     * @param SiteInterface $site
     */
    public function createCookiesPage(SiteInterface $site)   {
        $pageManager = $this->getPageManager();
        $blogIndex = $pageManager->create();
        $blogIndex->setSlug('cookies');
        $blogIndex->setUrl('/cookies');
        $blogIndex->setName('Cookies');
        $blogIndex->setTitle('Polityka cookies');
        $blogIndex->setEnabled(true);
        $blogIndex->setDecorate(0);
        $blogIndex->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $blogIndex->setTemplateCode('default-inftech');
        $blogIndex->setPageAlias('_page_alias_cookies');
        $blogIndex->setRouteName(PageInterface::PAGE_ROUTE_CMS_NAME);
        $blogIndex->setParent($this->getReference('page-homepage'));
        $blogIndex->setSite($site);
        $pageManager->save($blogIndex);
    }
    public function createGlobalPage(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();
        $blockManager = $this->getBlockManager();
        $blockInteractor = $this->getBlockInteractor();
        $global = $pageManager->create();
        $global->setName('global');
        $global->setRouteName('_page_internal_global');
        $global->setSite($site);
        $pageManager->save($global);
        // CREATE A HEADER BLOCK
        $global->addBlocks($header = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page' => $global,
            'code' => 'header',
        )));
        $header->setName('The header container');
        $header->addChildren($text = $blockManager->create());
        $text->setType('sonata.block.service.text');
        $text->setSetting('content', '<h2><a href="/">Sonata Demo</a></h2>');
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($global);
        $global->addBlocks($headerTop = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page' => $global,
            'code' => 'header-top',
        ), function ($container) {
            $container->setSetting('layout', '<div class="pull-right">{{ CONTENT }}</div>');
        }));
        $headerTop->setPosition(1);
        $header->addChildren($headerTop);
        $headerTop->addChildren($account = $blockManager->create());
        $account->setType('sonata.user.block.account');
        $account->setPosition(1);
        $account->setEnabled(true);
        $account->setPage($global);
        $global->addBlocks($headerMenu = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page' => $global,
            'code' => 'header-menu',
        )));
        $headerMenu->setPosition(2);
        $header->addChildren($headerMenu);
        $headerMenu->setName('The header menu container');
        $headerMenu->setPosition(3);
        $headerMenu->addChildren($menu = $blockManager->create());
        $menu->setType('sonata.block.service.menu');
        $menu->setSetting('menu_name', "AldorInftechBundle:Builder:mainMenu");
        $menu->setSetting('safe_labels', true);
        $menu->setPosition(3);
        $menu->setEnabled(true);
        $menu->setPage($global);
        $global->addBlocks($footer = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page'    => $global,
            'code'    => 'footer'
        ), function ($container) {
            $container->setSetting('layout', '<div class="row page-footer well">{{ CONTENT }}</div>');
        }));
        $footer->setName('The footer container');
        // Footer : add 3 children block containers (left, center, right)
        $footer->addChildren($footerLeft = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page'    => $global,
            'code'    => 'content'
        ), function ($container) {
            $container->setSetting('layout', '<div class="col-sm-3">{{ CONTENT }}</div>');
        }));
        $footer->addChildren($footerLinksLeft = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page'    => $global,
            'code'    => 'content',
        ), function ($container) {
            $container->setSetting('layout', '<div class="col-sm-2 col-sm-offset-3">{{ CONTENT }}</div>');
        }));
        $footer->addChildren($footerLinksCenter = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page'    => $global,
            'code'    => 'content'
        ), function ($container) {
            $container->setSetting('layout', '<div class="col-sm-2">{{ CONTENT }}</div>');
        }));
        $footer->addChildren($footerLinksRight = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page'    => $global,
            'code'    => 'content'
        ), function ($container) {
            $container->setSetting('layout', '<div class="col-sm-2">{{ CONTENT }}</div>');
        }));
        // Footer left: add a simple text block
        $footerLeft->addChildren($text = $blockManager->create());
        $text->setType('sonata.block.service.text');
        $text->setSetting('content', '<h2>Sonata Demo</h2><p class="handcraft">HANDCRAFTED IN PARIS<br />WITH MIXED HERITAGE</p><p><a href="http://twitter.com/sonataproject" target="_blank">Follow Sonata on Twitter</a></p>');
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($global);
        // Footer left links
        $footerLinksLeft->addChildren($text = $blockManager->create());
        $text->setType('sonata.block.service.text');
        $text->setSetting('content', <<<CONTENT
<h4>PRODUCT</h4>
<ul class="links">
    <li><a href="/bundles">Sonata</a></li>
    <li><a href="/api-landing">API</a></li>
    <li><a href="/faq">FAQ</a></li>
</ul>
CONTENT
        );
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($global);
        // Footer middle links
        $footerLinksCenter->addChildren($text = $blockManager->create());
        $text->setType('sonata.block.service.text');
        $text->setSetting('content', <<<CONTENT
<h4>ABOUT</h4>
<ul class="links">
    <li><a href="http://www.sonata-project.org/about" target="_blank">About Sonata</a></li>
    <li><a href="/legal-notes">Legal notes</a></li>
    <li><a href="/shop/payment/terms-and-conditions">Terms</a></li>
</ul>
CONTENT
        );
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($global);
        // Footer right links
        $footerLinksRight->addChildren($text = $blockManager->create());
        $text->setType('sonata.block.service.text');
        $text->setSetting('content', <<<CONTENT
<h4>COMMUNITY</h4>
<ul class="links">
    <li><a href="/blog">Blog</a></li>
    <li><a href="http://www.github.com/sonata-project" target="_blank">Github</a></li>
    <li><a href="/contact-us">Contact us</a></li>
</ul>
CONTENT
        );
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($global);
        $pageManager->save($global);
    }




    public function create404ErrorPage(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();
        $blockManager = $this->getBlockManager();
        $blockInteractor = $this->getBlockInteractor();
        $page = $pageManager->create();
        $page->setName('_page_internal_error_not_found');
        $page->setTitle('Error 404');
        $page->setEnabled(true);
        $page->setDecorate(1);
        $page->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $page->setTemplateCode('default');
        $page->setRouteName('_page_internal_error_not_found');
        $page->setSite($site);
        $page->addBlocks($block = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page'    => $page,
            'code'    => 'content_top',
        )));
        // add the breadcrumb
        $block->addChildren($breadcrumb = $blockManager->create());
        $breadcrumb->setType('sonata.page.block.breadcrumb');
        $breadcrumb->setPosition(0);
        $breadcrumb->setEnabled(true);
        $breadcrumb->setPage($page);
        // Add text content block
        $block->addChildren($text = $blockManager->create());
        $text->setType('sonata.block.service.text');
        $text->setSetting('content', '<h2>Error 404</h2><div>Page not found.</div>');
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($page);
        $pageManager->save($page);
    }
    public function create500ErrorPage(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();
        $blockManager = $this->getBlockManager();
        $blockInteractor = $this->getBlockInteractor();
        $page = $pageManager->create();
        $page->setName('_page_internal_error_fatal');
        $page->setTitle('Error 500');
        $page->setEnabled(true);
        $page->setDecorate(1);
        $page->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $page->setTemplateCode('default');
        $page->setRouteName('_page_internal_error_fatal');
        $page->setSite($site);
        $page->addBlocks($block = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page'    => $page,
            'code'    => 'content_top',
        )));
        // add the breadcrumb
        $block->addChildren($breadcrumb = $blockManager->create());
        $breadcrumb->setType('sonata.page.block.breadcrumb');
        $breadcrumb->setPosition(0);
        $breadcrumb->setEnabled(true);
        $breadcrumb->setPage($page);
        // Add text content block
        $block->addChildren($text = $blockManager->create());
        $text->setType('sonata.block.service.text');
        $text->setSetting('content', '<h2>Error 500</h2><div>Internal error.</div>');
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($page);
        $pageManager->save($page);
    }
  /**
     * @param SiteInterface $site
     */
    public function createHomePage(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();
        $blockManager = $this->getBlockManager();
        $blockInteractor = $this->getBlockInteractor();
        $this->addReference('page-homepage', $homepage = $pageManager->create());
        $homepage->setSlug('/');
        $homepage->setUrl('/');
        $homepage->setName('Homepage');
        $homepage->setTitle('Homepage');
        $homepage->setEnabled(true);
        $homepage->setDecorate(0);
        $homepage->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $homepage->setTemplateCode('homepage');
        $homepage->setRouteName(PageInterface::PAGE_ROUTE_CMS_NAME);

        $homepage->setSite($site);
        $pageManager->save($homepage);
        // CREATE A HEADER BLOCK
        $homepage->addBlocks($contentTop = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page' => $homepage,
            'code' => 'top_content',
        )));
        $contentTop->setName('Ostatnie Posty');
        $blockManager->save($contentTop);
        // add a block text
        $contentTop->addChildren($text = $blockManager->create());
        $text->setType('aldor.block.service.recentposts');
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($homepage);

        $pageManager->save($homepage);
    }

     /**
     * @return \Sonata\PageBundle\Model\SiteManagerInterface
     */
    public function getSiteManager()
    {
        return $this->container->get('sonata.page.manager.site');
    }
    /**
     * @return \Sonata\PageBundle\Model\PageManagerInterface
     */
    public function getPageManager()
    {
        return $this->container->get('sonata.page.manager.page');
    }
    /**
     * @return \Sonata\BlockBundle\Model\BlockManagerInterface
     */
    public function getBlockManager()
    {
        return $this->container->get('sonata.page.manager.block');
    }
    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->container->get('faker.generator');
    }
    /**
     * @return \Sonata\PageBundle\Entity\BlockInteractor
     */
    public function getBlockInteractor()
    {
        return $this->container->get('sonata.page.block_interactor');
    }
}
