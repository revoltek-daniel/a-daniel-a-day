<?php

namespace DanielBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('isActive')->add('username');

        $this->addPassword($builder);
        $builder->add('city')->add('street')->add('zip');

        $this->addCountry($builder);

        $builder->add('name');
    }

    protected function addCountry(FormBuilderInterface $builder)
    {
        $builder->add(
            'country',
            CountryType::class,
            [
                'required' => true,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AutodeskBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'autodeskbundle_user';
    }

    protected function addPassword(FormBuilderInterface $builder)
    {
        $builder->add('plainPassword', PasswordType::class);
    }
}
