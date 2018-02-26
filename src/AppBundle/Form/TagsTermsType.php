<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagsTermsType extends AbstractType
{
    protected $em;
    protected $formType;

    /**
     * TagsTermsType constructor.
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
                'tagsVocabularyId',
                EntityType::class,
                [
                    'class' => 'AppBundle:TagsVocabulary',
                    'choice_label' => 'name',
                    'property' => 'id',
                    'required' => true,
                    'data' => $options['vocabularyId'] ?
                        $this->em->getReference('AppBundle:TagsVocabulary', $options['vocabularyId']) :
                        null,
                    'label' => 'Vocabulary',
                    'disabled' => true,
                    'attr' => [
                        'readonly' => $options['vocabularyId'] ? true : false,
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
                            ->remove('tagsVocabularyId');
                    }
                }
            );

        if ($this->getFormType() === 'add') {
            //@todo: Need to move it to EventListener
            $builder->getData()->setTagsVocabularyId($builder->getOption('vocabularyId'));
        }

    }

    /**
     * @return mixed
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * @param mixed $formType
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
                'data_class' => 'AppBundle\Entity\TagsTerms',
                'vocabularies' => null,
                'vocabularyId' => null,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }

}
