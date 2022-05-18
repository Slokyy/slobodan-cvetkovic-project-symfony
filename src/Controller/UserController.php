<?php

  namespace App\Controller;

  use App\Entity\Client;
  use App\Entity\Task;
  use App\Entity\User;
  use App\Form\EditUserType;
  use App\Form\TaskType;
  use Doctrine\ORM\EntityManagerInterface;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\File\Exception\FileException;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\Security\Core\Security;
  use Symfony\Component\Security\Core\User\UserInterface;
  use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
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
        $originalPassword = $user->getPassword();

        $editedUser = $editForm->getData();


        $plainPassword = $editForm->get('plainPassword')->getData();
//        dd($originalPassword, $plainPassword);
        if($plainPassword !== null) {
//          $plainPassword = $originalPassword;
          $hashedPassword = $passwordHasher->hashPassword(
            $editedUser,
            $plainPassword
          );
          $editedUser->setPassword($hashedPassword);
        }
        if($plainPassword === null) {
          $editedUser->setPassword($originalPassword);
        }




//        dd($originalPassword, $editedUser);

        // Ovo je zapravo ceo avatar fajl slike
        $userImage = $editForm->get('avatar_path')->getData();

        if($userImage) {
          $originalFilename = pathinfo($userImage->getClientOriginalName(), PATHINFO_FILENAME);
          $safeFilename = $slugger->slug($originalFilename);
          $newFilename = $safeFilename.'-'.uniqid().'.'.$userImage->guessExtension();

          // Move file where profile images should be stored
          try {
            $userImage->move(
              $this->getParameter('user_images'),
              $newFilename
            );
          } catch (FileException $e) {
            return new Response("File Upload Error: $e");
          }

          $editedUser->setAvatarPath($newFilename);
          $editedUser->setAvatarAlt($editForm->get('first_name')->getData() . " " .$editForm->get('last_name')->getData());
        }


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


    #[Route('/dashboard/my-profile/edit-task/{taskId}', name: 'my_profile_edit_task')]
    public function editTask($taskId, Request $request): Response
    {
      $loggedUser = $this->security->getUser();
      $user = $this->userRepository->findOneBy(["email" => $loggedUser->getUserIdentifier()]);
      $clients = $this->clientRepository->findAll();
      $task = $this->taskRepository->find($taskId);

      $editTaskForm = $this->createForm(TaskType::class, $task, [
        'method' => "PUT",
        'action' => $this->generateUrl('dashboard_my_profile_edit_task', ['taskId' => $taskId])
      ]);

      $editTaskForm->handleRequest($request);

      if($editTaskForm->isSubmitted() && $editTaskForm->isValid() && $request->isMethod("PUT"))
      {
        $editedTask = $editTaskForm->getData();
        $this->taskRepository->add($editedTask, true);

        return $this->redirectToRoute('dashboard_my_profile');
      }


      return $this->renderForm('user/edit-task.html.twig', [
        'user' => $user,
        'client' => $clients,
        'editTaskForm' => $editTaskForm
      ]);
    }

    #[Route('/dashboard/my-profile/delete-task', name: 'my_profile_delete_task')]
    public function delete(Request $request): Response
    {
      // Get the user
      $loggedUser = $this->security->getUser();
      // Getting the user through the db
      $user = $this->userRepository->findOneBy(['email' => $loggedUser->getUserIdentifier()]);

      $taskId = $request->request->get('taskId');
      dd($taskId);
      // Get task and check its user id
      $task = $this->taskRepository->find($taskId);
      $taskUserId = $task->getUser()->getId();
      // check if user is owner of the task
      $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

      if($user->getId() === $taskUserId) {
        $this->taskRepository->remove($task, true);
        return $this->redirectToRoute('dashboard_my_profile');
      }

//      dd($loggedUser, $user, $taskId, $task, $taskUserId);

      return $this->redirectToRoute('dashboard_my_profile');
    }

  }
