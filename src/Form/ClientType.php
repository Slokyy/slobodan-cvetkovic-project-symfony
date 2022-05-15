<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
              'attr' => [
                'class' => 'slide-form'
              ],
              'label_html' => true,
              'label' => '<span class="content-name">Company name:</span>',
              'label_attr' => [
                'class' => 'label-name'
              ]
            ])
            ->add('avatar_path', TextType::class, [
              'attr' => [
                'class' => 'slide-form'
              ],
              'label_html' => true,
              'label' => '<span class="content-name">Image path:</span>',
              'label_attr' => [
                'class' => 'label-name'
              ]
            ])
            ->add('email', TextType::class, [
              'attr' => [
                'class' => 'slide-form'
              ],
              'label_html' => true,
              'label' => '<span class="content-name">Company email:</span>',
              'label_attr' => [
                'class' => 'label-name'
              ]
            ])
            ->add('payment_method', TextType::class, [
              'attr' => [
                'class' => 'slide-form'
              ],
              'label_html' => true,
              'label' => '<span class="content-name">Payment method:</span>',
              'label_attr' => [
                'class' => 'label-name'
              ]
            ])
            ->add('bank_acc', TextType::class, [
              'attr' => [
                'class' => 'slide-form'
              ],
              'label_html' => true,
              'label' => '<span class="content-name">Bank account:</span>',
              'label_attr' => [
                'class' => 'label-name'
              ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
