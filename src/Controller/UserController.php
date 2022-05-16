<?php

  namespace App\Controller;

  use App\Entity\Client;
  use App\Entity\Task;
  use App\Entity\User;
  use App\Form\EditUserType;
  use App\Form\TaskType;
  use Doctrine\ORM\EntityManagerInterface;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\Security\Core\Security;
  use Symfony\Component\Security\Core\User\UserInterface;

  #[Route('$2a$12$99iZHSovZPM6xvwAMeFeoONS69pt45Udgplt4DAdT7fDQvX12nBte', name: "dashboard_")]
  class UserController extends AbstractController
  {
    private $security;
    private $userRepository;
    private $clientRepository;
    private $taskRepository;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
      $this->security = $security;
      $this->userRepository = $entityManager->getRepository(User::class);
      $this->clientRepository = $entityManager->getRepository(Client::class);
      $this->taskRepository = $entityManager->getRepository(Task::class);
    }


    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    #[Route('/dashboard/my-profile', name: 'my_profile')]
    public function index(Request $request): Response
    {

      $loggedUser = $this->security->getUser();
      $user = $this->userRepository->findOneBy(['email' => $loggedUser->getUserIdentifier()]);
      $userId = $user->getId();

      // User Task
      $userTasks = $user->getTasks();

      // Task object

      $task = new Task();


      $clients = $this->clientRepository->findAll();

//      dd($user);

      // Edit form
      $editForm = $this->createForm(EditUserType::class, $user, [
        'method' => 'PUT',
        'action' => $this->generateUrl('dashboard_my_profile')
      ]);

      $editForm->handleRequest($request);

      if($editForm->isSubmitted() && $editForm->isValid() && $request->isMethod("PUT"))
      {
        $editedUser = $editForm->getData();
        $this->userRepository->add($editedUser, true);
        return $this->redirectToRoute('dashboard_my_profile');
      }

      // Insert Hours Form


      $insertTaskForm = $this->createForm(TaskType::class, $task, [
        'method' => 'POST',
        'action' => $this->generateUrl('dashboard_my_profile')
      ]);

      $insertTaskForm->handleRequest($request);

      if($insertTaskForm->isSubmitted() && $insertTaskForm->isValid() && $request->isMethod("POST"))
      {
        $taskData = $insertTaskForm->getData();
        $taskData->setUser($user);
        $this->taskRepository->add($taskData, true);

        return $this->redirectToRoute('dashboard_my_profile');
      }




      return $this->render('user/index.html.twig', [
        'user' => $user,
        'userTasks' => $userTasks,
        'clients' => $clients,
        'insertTaskForm' => $insertTaskForm->createView(),
        'editForm' => $editForm->createView()
      ]);
    }

  }
