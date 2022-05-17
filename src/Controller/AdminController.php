<?php

  namespace App\Controller;

  use App\Entity\Task;
  use App\Entity\User;
  use App\Form\EditUserType;
  use App\Form\RegistrationFormType;
  use App\Form\UserFilterType;
  use App\Repository\TaskRepository;
  use Doctrine\ORM\EntityManagerInterface;
  use Doctrine\Persistence\ManagerRegistry;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\Form\Extension\Core\Type\PasswordType;
  use Symfony\Component\HttpFoundation\File\Exception\FileException;
  use Symfony\Component\HttpFoundation\File\UploadedFile;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\String\Slugger\SluggerInterface;

  #[Route('/$2a$12$99iZHSovZPM6xvwAMeFeoONS69pt45Udgplt4DAdT7fDQvX12nBte/admin', name: 'dashboard_')]
  class AdminController extends AbstractController
  {
    private $_em;

    public function __construct(ManagerRegistry $doctrine)
    {
      $this->_em = $doctrine;
    }


    #[Route('/users', name: 'index')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
      if (!$this->isGranted('ROLE_ADMIN')) {
        if (!$this->isGranted('ROLE_DEVELOPER')) {
          return $this->redirectToRoute('dashboard_logout');
        } else {
          return $this->redirectToRoute('dashboard_my_profile');
        }
      }


      // Slide form handler for adding users
      unset($user);
      unset($form);
      $user = new User();
      $form = $this->createForm(RegistrationFormType::class, $user);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

        // Ovo je zapravo ceo avatar fajl slike
        $userImage = $form->get('avatar_path')->getData();

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

          $user->setAvatarPath($newFilename);
          $user->setAvatarAlt($form->get('first_name')->getData() . " " .$form->get('last_name')->getData());
        }

        $user->setPassword(
          $userPasswordHasher->hashPassword(
            $user,
            $form->get('plainPassword')->getData()
          )
        );

        $entityManager->persist($user);
        $entityManager->flush();
        // do anything else you need here, like send an email

        return $this->redirectToRoute('dashboard_index');
      }

      // User repository for getting all users
      $userRepository = $entityManager->getRepository(User::class);
      $users = $userRepository->findAll();

//      (dd($userRepository->findDevelopers()));

      // Search query top of table


      if($request->query->has('submitSearch') && !empty($request->query->get('user-table-search'))) {
        $searchParam = $request->query->get('user-table-search');
        if($searchParam == "Developer") {
          $searchParam = "ROLE_DEVELOPER";
        }
        if($searchParam == "Administrator") {
          $searchParam = "ROLE_ADMIN";
        }
        $users = $userRepository->getSearchedUsersQuery($searchParam);

        return $this->render('admin/index.html.twig', [
          'registrationForm' => $form->createView(),
          'users' => $users
        ]);
      }

      return $this->render('admin/index.html.twig', [
        'registrationForm' => $form->createView(),
        'users' => $users
      ]);
    }

    #[Route('/users/{id}', name: 'admin_view_user', methods: ['GET', 'PUT'])]
    public function viewUser($id, Request $request, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher): Response
    {
      if (!$this->isGranted('ROLE_ADMIN')) {
        if (!$this->isGranted('ROLE_DEVELOPER')) {
          return $this->redirectToRoute('dashboard_logout');
        } else {
          return $this->redirectToRoute('dashboard_my_profile');
        }
      }
      unset($user);

      $userRepository = $this->_em->getRepository(User::class);
      $user = $userRepository->find($id);
      $userTasks = $user->getTasks();

      // Getting total hours
      $hourArray = [];
      $minuteArray = [];
      foreach($userTasks as $userTask) {
        $hourArray[] = gmdate("H", date_timestamp_get($userTask->getTime()));
        $minuteArray[] = gmdate("i", date_timestamp_get($userTask->getTime()));
      }

      $totalHours = 0;
      $totalMinutes = 0;
      foreach($hourArray as $hours) {
        $totalHours += $hours;
      }
      $totalMinutes = $totalHours * 60;
      foreach($minuteArray as $minutes) {
        $totalMinutes += $minutes;
      }
      $totalHoursFormated = intdiv($totalMinutes, 60).':'. ($totalMinutes % 60);

      $editForm = $this->createForm(EditUserType::class, $user, [
        'method' => 'PUT',
        'action' => $this->generateUrl('dashboard_admin_view_user', ['id' => $id])
      ]);


      $editForm->handleRequest($request);

      if($editForm->isSubmitted() && $editForm->isValid() && $request->request->get("_method") == "PUT")
      {
        $originalPassword = $user->getPassword();
        $editedUser = $editForm->getData();

        // Password Handle
        $plainPassword = $editForm->get('plainPassword')->getData();
        if($plainPassword !== null) {
          $hashedPassword = $passwordHasher->hashPassword(
            $editedUser,
            $plainPassword
          );
          $editedUser->setPassword($hashedPassword);
        }
        if($plainPassword === null) {
          $editedUser->setPassword($originalPassword);
        }

        // Filter on admin/users

        $adminFilterForm = $this->createForm(UserFilterType::class, $user, [
          'method' => 'GET',
          'action' => $this->generateUrl('dashboard_admin_view_user', ['id' => $id])
        ]);

        $adminFilterForm->handleRequest($request);

        if($adminFilterForm->isSubmitted() && $adminFilterForm->isValid() && $request->isMethod("GET"))
        {
          dd($adminFilterForm);
        }

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

        $userRepository->add($editedUser, true);

        return $this->redirectToRoute('dashboard_admin_view_user', ['id' => $id]);
      }

      // Filter on admin/users

      $adminFilterForm = $this->createForm(UserFilterType::class, null, [
        'method' => 'GET',
        'action' => $this->generateUrl('dashboard_admin_view_user', ['id' => $id])
      ]);

      $adminFilterForm->handleRequest($request);

      if($adminFilterForm->isSubmitted() && $adminFilterForm->isValid() && $request->isMethod("GET"))
      {
        $data = $adminFilterForm->getData();
        $filterClient = $data['client'];
        $filterDate = $data['month'];
        $month = (int)$filterDate->format('m');


        $taskRepository = $this->_em->getRepository(Task::class);
        $userTasks = $taskRepository->getFilteredClientTasks($user, $month, $filterClient);
//        dd($userTasks);
      }


      return $this->renderForm('admin/user-profile.html.twig', [
        'user' => $user,
        'userTasks' => $userTasks,
        'userTotalHours' => $totalHoursFormated,
        'editForm' => $editForm,
        'adminFilterForm' => $adminFilterForm
      ]);
    }





    #[Route('/user-delete', name: 'user_delete')]
    public function deleteUser(Request $request): Response
    {
      if (!$this->isGranted('ROLE_ADMIN'))
      {
        return $this->redirectToRoute('dashboard_my_profile');
      }
      $userRepository = $this->_em->getRepository(User::class);
      $userId = $request->request->get("userId");
      $user = $userRepository->find($userId);
      if($user == null) {
        throw $this->createNotFoundException("User not found (for deletion)");
      }
      $userRepository->remove($user, true);
//      dd($user);

      return $this->redirectToRoute('dashboard_index');
    }


  }
