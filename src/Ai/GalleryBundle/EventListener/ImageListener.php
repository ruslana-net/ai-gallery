<?php

namespace Ai\GalleryBundle\EventListener;

use Ai\GalleryBundle\Entity\Image;
use Ai\GalleryBundle\Services\ImageManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ImageListener
{
    private $imageManager;

    /**
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Image) {
            $this->uploadImage($entity);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Image) {
            $this->uploadImage($entity);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Image) {
            //Remove image file
            $this->imageManager->removeFile(ImageManager::TYPE_IMAGE, $entity->getImage());
        }
    }


    /**
     * Upload album image file
     *
     * @param Image $image
     */
    protected function uploadImage(Image $image)
    {
        //Upload album icon from orphanage
        $this->imageManager->orphanageUploads(ImageManager::TYPE_IMAGE, $image->getImage());
    }
}