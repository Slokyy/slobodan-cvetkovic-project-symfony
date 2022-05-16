<?php

  namespace App\Form;

  use App\Entity\Client;
  use App\Entity\Task;
  use Symfony\Bridge\Doctrine\Form\Type\EntityType;
  use Symfony\Component\Form\AbstractType;
  use Symfony\Component\Form\Extension\Core\Type\DateType;
  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\Extension\Core\Type\TimeType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\OptionsResolver\OptionsResolver;

  class TaskType extends AbstractType
  {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      $yearNow = (int)date('Y');
      $yearNext = (int)date('Y', strtotime($yearNow . ' + 1 year'));
      $builder
        ->add('month', DateType::class, [
          'widget' => 'choice',
          'input' => 'datetime_immutable',
          'years' => range($yearNow, $yearNext)
        ])
        ->add('client', EntityType::class, [
          'class' => Client::class,
          'choice_label' => 'name'
        ])
        ->add('time', TimeType::class)
        ->add('description', TextType::class, [
          'attr' => [
            'class' => 'timer-field',
            'placeholder' => 'Task Description'
          ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
      $resolver->setDefaults([
        'data_class' => Task::class,
      ]);
    }
  }
