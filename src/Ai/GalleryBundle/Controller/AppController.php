<?php

namespace Ai\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\DiExtraBundle\Annotation\Inject;

class AppController extends Controller
{
    protected $status=200;

    protected $noCache=false;

    protected $cacheTime=30;

    protected $lastModified;

    /**
     * @Route("/")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function indexAction()
    {
        $session = $this->container->get('session');
        $session->start();

        //Set status
        $response = new Response('');
        $request = $this->get('request');
        $response->setStatusCode($this->status);
        $enviroment = $this->container->get( 'kernel' )->getEnvironment();

        //Set cache-control
        if ($enviroment == 'dev' || $this->noCache || $request->getMethod() == 'POST') {
            $response->setPrivate();
            $response->setMaxAge(0);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->headers->addCacheControlDirective('no-store', true);
        } else {
            $response->setPrivate();
            $response->setMaxAge($this->cacheTime);
            $response->setLastModified($response->getDate($this->lastModified));
            $response->headers->addCacheControlDirective('must-revalidate', true);
        }

        return $this->render('AiGalleryBundle:Default:index.html.twig', array(), $response);
    }
}
