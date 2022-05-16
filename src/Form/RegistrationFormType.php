<?php

  namespace App\Form;

  use App\Entity\User;
  use Symfony\Component\Form\AbstractType;
  use Symfony\Component\Form\CallbackTransformer;
  use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
  use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
  use Symfony\Component\Form\Extension\Core\Type\EmailType;
  use Symfony\Component\Form\Extension\Core\Type\FileType;
  use Symfony\Component\Form\Extension\Core\Type\PasswordType;
  use Symfony\Component\Form\Extension\Core\Type\SubmitType;
  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\OptionsResolver\OptionsResolver;
  use Symfony\Component\Validator\Constraints\Email;
  use Symfony\Component\Validator\Constraints\File;
  use Symfony\Component\Validator\Constraints\IsTrue;
  use Symfony\Component\Validator\Constraints\Length;
  use Symfony\Component\Validator\Constraints\NotBlank;

  class RegistrationFormType extends AbstractType
  {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      $builder
        ->add('first_name', TextType::class, [
          'attr' => [
            'class' => 'slide-form'
          ],
          'label_html' => true,
          'label' => '<span class="content-name">First name:</span>',
          'label_attr' => [
            'class' => 'label-name'
          ],
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter a first name',
            ]),
          ],
        ])
        ->add('last_name', TextType::class, [
          'attr' => [
            'class' => 'slide-form'
          ],
          'label_html' => true,
          'label' => '<span class="content-name">Last name:</span>',
          'label_attr' => [
            'class' => 'label-name'
          ],
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter a last name',
            ]),
          ],
        ])
        ->add('street', TextType::class, [
          'attr' => [
            'class' => 'slide-form'
          ],
          'label_html' => true,
          'label' => '<span class="content-name">Street:</span>',
          'label_attr' => [
            'class' => 'label-name'
          ],
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter a street name',
            ]),
          ],
        ])
        ->add('city', TextType::class, [
          'attr' => [
            'class' => 'slide-form'
          ],
          'label_html' => true,
          'label' => '<span class="content-name">City:</span>',
          'label_attr' => [
            'class' => 'label-name'
          ],
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter a city name',
            ]),
          ],
        ])
        ->add('country', TextType::class, [
          'attr' => [
            'class' => 'slide-form'
          ],
          'label_html' => true,
          'label' => '<span class="content-name">Country:</span>',
          'label_attr' => [
            'class' => 'label-name'
          ],
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter a country name',
            ]),
          ],
        ])
        ->add('email', EmailType::class, [
          'attr' => [
            'class' => 'slide-form'
          ],
          'label_html' => true,
          'label' => '<span class="content-name">Email:</span>',
          'label_attr' => [
            'class' => 'label-name'
          ],
          'constraints' => [
            new Email([
              'message' => 'Please enter a valid email address.'
            ]),
            new NotBlank([
              'message' => 'Please enter a valid email address.',
            ]),
          ],
        ])
        ->add('plainPassword', PasswordType::class, [
          // instead of being set onto the object directly,
          // this is read and encoded in the controller
          'mapped' => false,
          'attr' => [
            'autocomplete' => 'new-password',
            'class' => 'slide-form'
          ],
          'label_html' => true,
          'label' => '<span class="content-name">Password:</span>',
          'label_attr' => [
            'class' => 'label-name'
          ],
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter a password',
            ]),
            new Length([
              'min' => 6,
              'minMessage' => 'Your password should be at least {{ limit }} characters',
              // max length allowed by Symfony for security reasons
              'max' => 4096,
            ]),
          ],
        ])
        ->add('roles', ChoiceType::class, [
          'attr' => [
            'class' => 'slide-form'
          ],
//          'label_html' => true,
//          'label' => '<span class="label-name">Role:</span>',
          'label' => false,
          'choices' => [
            'Developer' => 'ROLE_DEVELOPER',
            'Admin' => 'ROLE_ADMIN',
          ],
          'expanded' => false,
          'multiple' => false
        ])
        ->add('status', ChoiceType::class, [
          'attr' => [
            'class' => 'slide-form'
          ],
//          'label_html' => true,
//          'label' => '<span class="label-name">Status:</span>',
          'label' => false,
          'choices' => [
            'ACTIVE' => 'ACTIVE',
            'INACTIVE' => 'INACTIVE'
          ],
          'expanded' => false,
          'multiple' => false
        ])
        ->add('bank_acc', TextType::class, [
          'attr' => [
            'class' => 'slide-form'
          ],
          'label_html' => true,
          'label' => '<span class="content-name">Bank Account:</span>',
          'label_attr' => [
            'class' => 'label-name'
          ],
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter a bank account nubmer',
            ]),
            new Length([
              'min' => 8,
              'minMessage' => 'Your bank account should be at least {{ limit }} characters',
              // max length allowed by Symfony for security reasons
              'max' => 4096,
            ]),
          ],
        ])
        ->add('avatar_path', FileType::class, [
          'mapped' => false,
          'required' => true,
          'constraints' => [
            new File([
              'maxSize' => '10024k',
              'maxSizeMessage' => 'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}',
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
          'label' => '<span class="content-name flex">Image path:</span>',
          'label_attr' => [
            'class' => 'label-name'
          ],
        ]);

      // Roles filed data transformer
      $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
          function ($rolesArray) {
            // Transform the array to a string
            return count($rolesArray) ? $rolesArray[0] : 0;
          },
          function ($rolesString) {
            // Transform the string back to array
            return [$rolesString];
          }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
      $resolver->setDefaults([
        'data_class' => User::class,
      ]);
    }
  }
