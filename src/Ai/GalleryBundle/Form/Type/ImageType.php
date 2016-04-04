<?php

namespace Ai\GalleryBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Form\Transformer\EntityToIdObjectTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\FormType;

/**
 * @FormType
 */
class ImageType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $albumTransformer = new EntityToIdObjectTransformer($this->om, "Ai\GalleryBundle\Entity\Album");
        $builder
            ->add('id')
            ->add('name')
            ->add('image')
            ->add('album', 'text', array(
                'invalid_message' => 'That is not a valid album id',
            ))
            ->add(
                $builder->create('album', 'text')->addModelTransformer($albumTransformer)
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ai\GalleryBundle\Entity\Image',
            'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'gallery_api_image';
    }
}
