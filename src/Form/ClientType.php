<?php

  namespace App\Form;

  use App\Entity\Client;
  use Symfony\Component\Form\AbstractType;
  use Symfony\Component\Form\Extension\Core\Type\FileType;
  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\OptionsResolver\OptionsResolver;
  use Symfony\Component\Validator\Constraints\File;
  use Symfony\Component\Validator\Constraints\NotBlank;

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
          ],
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter a name.'
            ])
          ]
        ])
        ->add('avatar_path', FileType::class, [
          'mapped' => false,
          'required' => false,
          'constraints' => [
            new File([
              'maxSize' => '10024k',
              'maxSizeMessage' => 'File is over {{ maxSize }}KB',
              'mimeTypes' => [
                'image/gif',
                'image/jpeg',
                'image/png'
              ],
              'mimeTypesMessage' => 'Please upload a valid Image document',
            ])
          ],
          'attr' => [
            'class' => 'slide-form'
          ],
          'label_html' => true,
          'label' => '',
          'label_attr' => [
            'class' => 'label-image flex'
          ],
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
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
      $resolver->setDefaults([
        'data_class' => Client::class,
      ]);
    }
  }
