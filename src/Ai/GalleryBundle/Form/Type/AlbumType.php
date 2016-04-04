<?php

namespace Ai\GalleryBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\FormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * @FormType
 */
class AlbumType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var ImageType
     */
    private $imageType;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om, ImageType $imageType)
    {
        $this->om = $om;
        $this->imageType = $imageType;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('name')
            ->add('descr')
            ->add('icon')
//            ->add('images', CollectionType::class, array(
//                'entry_type' => ImageType::class
//            ))
            ->add('images', 'collection', array(
                'type' => $this->imageType,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'options' => array(
                    'required' => false,
                ),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ai\GalleryBundle\Entity\Album',
            'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'gallery_api_album';
    }
}
