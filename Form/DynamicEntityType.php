<?php

namespace DynamicFormBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DynamicFormBundle\Transformer\EntityToIdTransformer;
use DynamicFormBundle\Transformer\EntityToLabelTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\VarDumper;

class DynamicEntityType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EntityToIdTransformer($this->objectManager, $options['class']));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $labelTransformer = new EntityToLabelTransformer($this->objectManager, $options['class'], $options['entity_label']);
        $view->vars['entity_label'] = $labelTransformer->transform($form->getData());

        if(!$view->vars['entity_label']) {
            $view->vars['entity_label'] = $options['placeholder'];
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(array(
                'class', 'entity_label'
            ))
            ->setDefaults(array(
                'invalid_message' => 'The entity does not exist.',
                'placeholder' => 'Geen selectie',
            ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'dynamic_entity';
    }
}