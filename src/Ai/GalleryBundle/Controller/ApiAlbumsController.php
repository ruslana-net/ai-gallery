<?php

namespace Ai\GalleryBundle\Controller;

use Ai\GalleryBundle\Entity\Album;
use Ai\GalleryBundle\Services\ImageManager;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\DiExtraBundle\Annotation\Inject;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;

class ApiAlbumsController extends FOSRestController
{
    const numItemsPerPage=6;

    /**
     * @Inject("doctrine")
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * @Inject("form.factory")
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * @Inject("router")
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @Inject("ai_gallery.image_manager")
     * @var \Ai\GalleryBundle\Services\ImageManager
     */
    private $imageManager;

    /**
     * @ApiDoc(
     *     description="Gets all albums",
     *     filters={
     *         {"name"="search", "dataType"="string"},
     *         {"name"="limit", "dataType"="integer"}
     *     },
     *     statusCodes={
     *         200="When successful"
     *     }
     * )
     * @Get("/albums", name="gallery_rest_album_getall", defaults={"_format" = "json"})
     * @View
     */
    public function cgetAction(Request $request)
    {
        /** @var \Ai\GalleryBundle\Repository\AlbumRepository $repository */
        $repository = $this->doctrine->getRepository('AiGalleryBundle:Album');

        $query = $repository->findBySearchQuery(
            $request->query->get('search')
        );

        $paginator  = $this->get('knp_paginator');
        /** @var SlidingPagination $pagination */
        $pagination = $paginator->paginate(
            $query,
            (int) $request->query->get('page'),
            self::numItemsPerPage
        );

        return [
            'items' => $pagination->getItems(),
            'paginationData' => $pagination->getPaginationData(),
        ];
    }

    /**
     * @ApiDoc(
     *     description="Gets the album",
     *     statusCodes={
     *         404="When the album does not exist",
     *         200="When successful"
     *     }
     * )
     * @Get("/album/{id}", name="gallery_rest_album_get",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View
     */
    public function getAction(Album $album)
    {
        return $album;
    }

    /**
     * @ApiDoc(
     *     description="Deletes an album",
     *     statusCodes={
     *         404="When the album does not exist",
     *         204="When successful"
     *     }
     * )
     * @Delete("/album/{id}", name="gallery_rest_album_delete",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View(statusCode=204)
     */
    public function deleteAction(Album $album)
    {
        //Remove album entity
        $em = $this->doctrine->getManager();
        $em->remove($album);
        $em->flush();
    }

    /**
     * @ApiDoc(
     *     description="Creates an album",
     *     parameters={
     *         {"name"="name", "dataType"="string", "required"=true, "description"="The album name"},
     *         {"name"="descr", "dataType"="string", "required"=false, "description"="The album description"},
     *         {"name"="icon", "dataType"="string", "required"=true, "description"="The album icon fileName"},
     *         {"name"="images", "dataType"="array", "required"=true, "description"="The album icon fileName"}
     *      },
     *      statusCodes={
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Post("/album", name="gallery_rest_album_create", defaults={"_format" = "json"})
     */
    public function createAction(Request $request)
    {
        $album = new Album();

        return $this->processForm($album, $request);
    }

    /**
     * @ApiDoc(
     *     description="Updates a album",
     *     parameters={
     *         {"name"="name", "dataType"="string", "required"=true, "description"="The album name"},
     *         {"name"="descr", "dataType"="string", "required"=false, "description"="The album description"},
     *         {"name"="icon", "dataType"="string", "required"=true, "description"="The album icon fileName"},
     *         {"name"="images", "dataType"="array", "required"=true, "description"="The album icon fileName"}
     *      },
     *      statusCodes={
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Put("/album/{id}", name="gallery_rest_album_update", defaults={"_format" = "json"})
     */
    public function updateAction(Album $album, Request $request)
    {
        return $this->processForm($album, $request);
    }

    /**
     * @param Album $album
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    private function processForm(Album $album, Request $request)
    {
        $isNew = null === $album->getId();
        $statusCode = $isNew ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
        $formType = $this->get('ai_gallery.form.type.album');
        $form = $this->formFactory->createNamed('', $formType, $album);
        $form->submit($request, false);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($album);
            $em->flush();

            $headers = array();
            if ($isNew) {
                $headers['Location'] = $this->router->generate(
                    'gallery_rest_album_get',
                    array('id' => $album->getId()),
                    true
                );
            }

            $data = [
                'id' => $album->getId(),
                'name' => $album->getName(),
                'descr' => $album->getDescr(),
                'icon' => $album->getIcon(),
                'images' => $album->getImages(),
            ];

            return \FOS\RestBundle\View\View::create($data, $statusCode, $headers);
        }

        return \FOS\RestBundle\View\View::create($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *     description="Gets the album images",
     *     statusCodes={
     *         200="When successful"
     *     }
     * )
     * @Get("/album/images/{id}", name="gallery_rest_album_images_get",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View
     */
    public function getImagesAction(Album $album)
    {
        $imgData = [];
        foreach($album->getImages() as $image)
        {
            $data = $this->imageManager->getImageData(ImageManager::TYPE_IMAGE, $image->getImage());
            $data['uuid'] = $image->getId();
            $data['imageName'] = $image->getName();
            $imgData[] = $data;
        }

        return $imgData;
    }
}
