<?php

namespace Ai\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\DiExtraBundle\Annotation\Inject;

class ImageController extends Controller
{
    /**
     * @Inject("ai_gallery.image_manager")
     * @var \Ai\GalleryBundle\Services\ImageManager
     */
    private $imageManager;

    /**
     * @param string $type ImageManager::TYPE_*
     * @param string $filename image file name
     *
     * @Route("/thumb/{type}/{filename}")
     * @Method({"GET"})
     *
     * @return RedirectResponse
     */
    public function imageThumbAction($type, $filename)
    {
        $thumbSrc = $this->imageManager->getImageThumb($type, $filename);

        return new RedirectResponse($thumbSrc, Response::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * @param string $type ImageManager::TYPE_*
     * @param string $filename image file name
     *
     * @Route("/data/{type}/{filename}", defaults={"_format" = "json"})
     * @Method({"GET"})
     *
     * @return json
     */
    public function imageDataAction($type, $filename)
    {
        return $this->imageManager->getImagesData(
            [['type' => $type, 'filename' => $filename]]
        );
    }
}
