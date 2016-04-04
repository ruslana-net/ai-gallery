<?php

namespace Ai\GalleryBundle\EventListener;

use Ai\GalleryBundle\Entity\Album;
use Ai\GalleryBundle\Services\ImageManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class AlbumListener
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

        if ($entity instanceof Album) {
            $this->uploadAlbumIcon($args->getEntityManager(), $entity);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Album) {
            $this->uploadAlbumIcon($args->getEntityManager(), $entity);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Album) {
            //Remove album icon file
            $this->imageManager->removeFile(ImageManager::TYPE_ALBUM, $entity->getIcon());
        }
    }

    /**
     * Upload album icon file and remove old
     *
     * @param EntityManagerInterface $em
     * @param Album $album
     */
    protected function uploadAlbumIcon(EntityManagerInterface $em, Album $album)
    {
        //Upload album icon from orphanage
        $this->imageManager->orphanageUploads(ImageManager::TYPE_ALBUM, $album->getIcon());

        //Get entity changeset
        $uow = $em->getUnitOfWork();
        $uow->computeChangeSets();
        $changeset = $uow->getEntityChangeSet($album);

        //Remove the old icon file if it was changed
        if(array_key_exists('icon', $changeset)) {
            if($oldIcon = $changeset['icon'][0]){
                $this->imageManager->removeFile(ImageManager::TYPE_ALBUM, $oldIcon);
            }
        }
    }

}