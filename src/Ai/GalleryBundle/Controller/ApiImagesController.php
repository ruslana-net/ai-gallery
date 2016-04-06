<?php

namespace Ai\GalleryBundle\Controller;

use Ai\GalleryBundle\Entity\Image;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\DiExtraBundle\Annotation\Inject;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;

class ApiImagesController extends Controller
{
    const numItemsPerPage=8;

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
     * @ApiDoc(
     *     description="Gets all images",
     *     filters={
     *         {"name"="search", "dataType"="string"},
     *         {"name"="limit", "dataType"="integer"}
     *     },
     *     statusCodes={
     *         200="When successful"
     *     }
     * )
     * @Get("/images", name="gallery_rest_images_getall", defaults={"_format" = "json"})
     * @View
     */
    public function cgetAction(Request $request)
    {
        /** @var \Ai\GalleryBundle\Repository\ImageRepository $repository */
        $repository = $this->doctrine->getRepository('AiGalleryBundle:Image');
        if(!$album = $this
                        ->doctrine
                        ->getRepository('AiGalleryBundle:Album')
                        ->find((int) $request->query->get('albumId')))
        {
            return [];
        }

        $query = $repository->findBySearchQuery(
            $album,
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
     *     description="Gets the image",
     *     statusCodes={
     *         404="When the image does not exist",
     *         200="When successful"
     *     }
     * )
     * @Get("/image/{id}", name="gallery_rest_image_get",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View
     */
    public function getAction(Image $image)
    {
        return [
            'id' => $image->getId(),
            'name' => $image->getName(),
            'image' => $image->getImage()
        ];
    }

    /**
     * @ApiDoc(
     *     description="Deletes an image",
     *     statusCodes={
     *         404="When the image does not exist",
     *         204="When successful"
     *     }
     * )
     * @Delete("/image/{id}", name="gallery_rest_image_delete",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View(statusCode=204)
     */
    public function deleteAction(Image $image)
    {
        //Remove image entity
        $em = $this->doctrine->getManager();
        $em->remove($image);
        $em->flush();
    }

    /**
     * @ApiDoc(
     *     description="Creates an image",
     *     parameters={
     *         {"name"="name", "dataType"="string", "required"=true, "description"="The image name"},
     *         {"name"="image", "dataType"="string", "required"=true, "description"="The image fileName"}
     *      },
     *      statusCodes={
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Post("/image", name="gallery_rest_image_create", defaults={"_format" = "json"})
     */
    public function createAction(Request $request)
    {
        $image = new Image();

        return $this->processForm($image, $request);
    }

    /**
     * @ApiDoc(
     *     description="Updates a image",
     *     parameters={
     *         {"name"="name", "dataType"="string", "required"=true, "description"="The image name"},
     *         {"name"="image", "dataType"="string", "required"=true, "description"="The image fileName"}
     *      },
     *      statusCodes={
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Put("/image/{id}", name="gallery_rest_image_update", defaults={"_format" = "json"})
     */
    public function updateAction(Image $image, Request $request)
    {
        return $this->processForm($image, $request);
    }

    /**
     * @param Image $image
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    private function processForm(Image $image, Request $request)
    {
        $isNew = null === $image->getId();
        $statusCode = $isNew ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
        $formType = $this->get('ai_gallery.form.type.image');
        $form = $this->formFactory->createNamed('', $formType, $image);
        $form->submit($request, true);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($image);
            $em->flush();

            $headers = array();
            if ($isNew) {
                $headers['Location'] = $this->router->generate(
                    'gallery_rest_image_get',
                    array('id' => $image->getId()),
                    true
                );
            }

            $data = [
                'id' => $image->getId(),
                'name' => $image->getName(),
                'image' => $image->getImage(),
                'album' => $image->getAlbum() ? $image->getAlbum()->getId() : null
            ];

            return \FOS\RestBundle\View\View::create($data, $statusCode, $headers);
        }

        return \FOS\RestBundle\View\View::create($form, Response::HTTP_BAD_REQUEST);
    }
}
