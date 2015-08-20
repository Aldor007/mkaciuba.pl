<?php
namespace Aldor\InftechBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RedirectingController extends Controller
{
    public function removeTrailingSlashAction(Request $request) {
        $pathInfo = $request->getPathInfo();
        $requestUri = $request->getRequestUri();

        $url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);
        if ($url == '') {
            $url = ' ';
        }
        $url = preg_replace('/\/+/', '/', $url);

        return $this->redirect($url, 301);
    }
}
