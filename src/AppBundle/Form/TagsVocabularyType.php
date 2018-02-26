<?php

namespace AppBundle\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class TagsVocabularyType extends AbstractType
{
    protected $em;
    protected $formType;

    /**
     * TagsVocabularyType constructor.
     * @param $em
     * @param string $formType
     */
    public function __construct($em, $formType = 'add')
    {
        $this->em = $em;
        $this->formType = $formType;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add(
                'entityTypeId',
                EntityType::class,
                [
                    'label' => 'Type',
                    'required' => true,
                    'class' => 'AppBundle:EntityTypes',
                    'choice_label' => 'entityName',
                    'property' => 'id',
                    'data' => $options['entity_type'] ? $this->em->getReference(
                        'AppBundle:EntityTypes',
                        $options['entity_type']
                    ) : null,
                    'attr' => [
                        'readonly' => $options['entity_type'] ? true : false,
                    ],
                ]
            )
            ->add(
                'country',
                ChoiceType::class,
                [
                    'label' => 'Country',
                    'choices' => $options['available_countries'],
                    'data' => $options['country'],
                    'attr' => [
                        'readonly' => $options['country'] ? true : false,
                    ],
                ]
            )
            ->add(
                'delete',
                SubmitType::class,
                [
                    'label' => 'Delete',
                    'attr' => [
                        'class' => 'btn obg-form-btn btn-danger',
                    ],
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Save',
                    'attr' => [
                        'class' => 'btn obg-form-btn obg-submit-btn',
                    ],
                ]
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                    $form = $event->getForm();
                    if ('add' !== $this->getFormType()) {
                        $form
                            ->remove('entityTypeId')
                            ->remove('country');
                    }
                }
            );
    }

    /**
     * @return string
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * @param string $formType
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'AppBundle\Entity\TagsVocabulary',
                'entity_type' => null,
                'country' => null,
                'available_countries' => null,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_tagsvocabulary';
    }
}
