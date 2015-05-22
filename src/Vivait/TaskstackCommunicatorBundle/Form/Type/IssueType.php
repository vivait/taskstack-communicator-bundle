<?php


namespace Vivait\TaskstackCommunicatorBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'taskstack_issue';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject')
            ->add('description', 'textarea', [
                'attr' => [
                    'rows' => 7
                ]
            ])
            ->add('submit', 'submit', [
                'attr' => [
                    'class' => 'pull-right'
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Vivait\TaskstackCommunicatorBundle\Model\Issue',
            'attr' => [
                'class' => 'ajaxForm'
            ]
        ]);
    }


}