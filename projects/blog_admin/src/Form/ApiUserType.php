<?php

namespace App\Form;

use App\Entity\ApiUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiUserType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        $builder
            ->add(
                'token',
                TextType::class,
                [
                    'required'  => true,
                    'label'     => 'Token',
                    'trim'      => true,
                    'help'      => 'Please generate a random token with 128 symbols in length. For example: <code>$ pwgen -s 128</code>',
                    'help_html' => true,
                    'attr'      =>
                    [
                        'minlength' => 128,
                        'maxlength' => 128,
                    ],
                ]
            )
            ->add(
                'status',
                ChoiceType::class,
                [
                    'choices' =>
                    [
                        'Enabled' => \App\Entity\ApiUser::STATUS_ENABLED,
                        'Disabled' => \App\Entity\ApiUser::STATUS_DISABLED,
                    ],
                    'required' => true,
                    'label' => 'Status',
                    'help'     => 'Status of API User',
                ]
            )
            ->add( 'save', SubmitType::class );
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        $resolver->setDefaults(
            [
                'data_class' => ApiUser::class,
            ]
        );
    }
}
