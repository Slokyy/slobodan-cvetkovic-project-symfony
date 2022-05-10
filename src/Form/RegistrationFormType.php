<?php

  namespace App\Form;

  use App\Entity\User;
  use Symfony\Component\Form\AbstractType;
  use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
  use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
  use Symfony\Component\Form\Extension\Core\Type\FileType;
  use Symfony\Component\Form\Extension\Core\Type\PasswordType;
  use Symfony\Component\Form\Extension\Core\Type\SubmitType;
  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\OptionsResolver\OptionsResolver;
  use Symfony\Component\Validator\Constraints\IsTrue;
  use Symfony\Component\Validator\Constraints\Length;
  use Symfony\Component\Validator\Constraints\NotBlank;

  class RegistrationFormType extends AbstractType
  {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      $builder
        ->add('first_name', TextType::class)
        ->add('last_name', TextType::class)
        ->add('street', TextType::class)
        ->add('city', TextType::class)
        ->add('country', TextType::class)
        ->add('email', TextType::class)
        ->add('plainPassword', PasswordType::class, [
          // instead of being set onto the object directly,
          // this is read and encoded in the controller
          'mapped' => false,
          'attr' => ['autocomplete' => 'new-password'],
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
          'choices' => [
            'Developer' => 'ROLE_DEVELOPER',
            'Admin' => 'ROLE_ADMIN',
          ],
          'expanded' => true,
          'multiple' => true
        ])
        ->add('status', ChoiceType::class, [
          'choices' => [
            'ACTIVE' => 'ACTIVE',
            'INACTIVE' => 'INACTIVE'
          ],
          'expanded' => false,
          'multiple' => false
        ])
        ->add('bank_acc', TextType::class)
        ->add('avatar_path', TextType::class)
      ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
      $resolver->setDefaults([
        'data_class' => User::class,
      ]);
    }
  }
