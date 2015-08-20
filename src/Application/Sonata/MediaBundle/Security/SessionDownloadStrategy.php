<?php
namespace Application\Sonata\MediaBundle\Security;

use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sonata\MediaBundle\Security\DownloadStrategyInterface;

class SessionDownloadStrategy implements DownloadStrategyInterface
{
    protected $container;

    protected $translator;

    protected $times;

    protected $sessionKey = 'sonata/media/session/times';

    /**
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param int $times
     */
    public function __construct(TranslatorInterface $translator, ContainerInterface $container, $times)
    {
        $this->times    = $times;
        $this->container = $container;
        $this->translator = $translator;
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function isGranted(MediaInterface $media, Request $request)
    {
        if (!$this->container->has('session')) {
            return false;
        }

        $times = $this->getSession()->get($this->sessionKey, 0);

        if ($times >= $this->times) {
            return false;
        }

        $this->getSession()->set($this->sessionKey, $times++);

        return true;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->translator->trans('description.session_download_strategy', array('%times%' => $this->times), 'SonataMediaBundle');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session
     */
    private function getSession()
    {
        return $this->container->get('session');
    }
}
