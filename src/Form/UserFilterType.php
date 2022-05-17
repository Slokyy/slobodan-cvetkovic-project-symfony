<?php

  namespace App\Form;


  use App\Entity\Task;
  use App\Entity\User;
  use App\Entity\Client;
  use Symfony\Bridge\Doctrine\Form\Type\EntityType;
  use Symfony\Component\Form\AbstractType;
  use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
  use Symfony\Component\Form\Extension\Core\Type\SubmitType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\OptionsResolver\OptionsResolver;

  class UserFilterType extends AbstractType
  {
    public function buildForm(FormBuilderInterface $builder, array $option): void
    {
      $builder
        ->add('client', EntityType::class, [
          'label_html' => false,
          'label' => false,
          'class' => Client::class,
          'choice_label' => 'name'
        ])
        ->add('month', ChoiceType::class, [
          'label' => false,
          'choices' => [
            'January' => new \DateTime('January'),
            'February'=> new \DateTime('February'),
            'March' => new \DateTime('March'),
            'April' => new \DateTime('April'),
            'May' => new \DateTime('May'),
            'June' => new \DateTime('June'),
            'July' => new \DateTime('July'),
            'August' => new \DateTime('August'),
            'September' => new \DateTime('September'),
            'October' => new \DateTime('October'),
            'November' => new \DateTime('November'),
            'December' => new \DateTime('December')
          ]
        ])
        ->add('save', SubmitType::class, [
          'label' => 'Search',
          'attr' => [
            'class' => 'btn btn-blue btn-small-w'
          ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
      $resolver->setDefaults([
        'data_class' => null,
      ]);
    }
  }