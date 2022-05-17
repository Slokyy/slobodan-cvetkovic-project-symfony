<?php

  namespace App\Controller;

  use App\Entity\Client;
  use App\Entity\Task;
  use App\Entity\User;
  use App\Form\ClientFilterType;
  use App\Form\ClientType;
  use App\Repository\TaskRepository;
  use Doctrine\Persistence\ManagerRegistry;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\File\Exception\FileException;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\Security\Core\Security;
  use Symfony\Component\String\Slugger\SluggerInterface;

  #[Route('/$2a$12$99iZHSovZPM6xvwAMeFeoONS69pt45Udgplt4DAdT7fDQvX12nBte/admin', name: "dashboard_")]
  class ClientController extends AbstractController
  {
    private $security;
    private $_em;
    private $userRepository;

    public function __construct(Security $security, ManagerRegistry $doctrine)
    {
      $this->security = $security;
      $this->_em = $doctrine;
      $this->userRepository = $doctrine->getRepository(User::class);

    }

    #[Route('/clients', name: 'admin_clients', methods: ['GET', "POST"])]
    public function index(Request $request, SluggerInterface $slugger): Response
    {
      if (!$this->isGranted('ROLE_ADMIN'))
        if (!$this->isGranted('ROLE_DEVELOPER')) {
          return $this->redirectToRoute('dashboard_logout');
        } else {
          return $this->redirectToRoute('dashboard_my_profile');
        }

      $client = new Client();
      $clientRepository = $this->_em->getRepository(Client::class);
      $form = $this->createForm(ClientType::class, $client, [
        "method" => "POST"
      ]);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
//        dd("Uspeh");
        $client = $form->getData();

        $clientImage = $form->get('avatar_path')->getData();

        if($clientImage) {
          $originalFilename = pathinfo($clientImage->getClientOriginalName(), PATHINFO_FILENAME);
          $safeFilename = $slugger->slug($originalFilename);
          $newFilename = $safeFilename.'-'.uniqid().'.'.$clientImage->guessExtension();

          // Move file where profile images should be stored
          try {
            $clientImage->move(
              $this->getParameter('client_images'),
              $newFilename
            );
          } catch (FileException $e) {
            return new Response("File Upload Error: $e");
          }

          $client->setAvatarPath($newFilename);
          $client->setAvatarAlt($form->get('name')->getData());
        }
        $clientRepository->add($client, true);
        return $this->redirectToRoute('dashboard_admin_clients');
      }

      $user = $this->security->getUser();
      $clients = $clientRepository->findAll();


      if($request->query->has('submitSearch') && !empty($request->query->get('client-table-search'))) {
        $searchParam = $request->query->get('client-table-search');

        $clients = $clientRepository->getSearchedClientsQuery($searchParam);

      }

      return $this->render('clients/clients.html.twig', [
        'LoggedUser' => $user,
        'clients' => $clients,
        'clientForm' => $form->createView()
      ]);
    }

    #[Route('/clients/{id}', name: 'admin_view_client', methods: ["GET", "PUT"])]
    public function viewClientProfile($id, Request $request, SluggerInterface $slugger)
    {
      if (!$this->isGranted('ROLE_ADMIN'))
        if (!$this->isGranted('ROLE_DEVELOPER')) {
          return $this->redirectToRoute('dashboard_logout');
        } else {
          return $this->redirectToRoute('dashboard_my_profile');
        }

      $clientRepository = $this->_em->getRepository(Client::class);
      $client = $clientRepository->find($id);

      $clientTasks = $client->getTasks();


      $editClientForm = $this->createForm(ClientType::class, $client, [
        'method' => 'PUT',
        'action' => $this->generateUrl("dashboard_admin_view_client", ['id' => $id])
      ]);

      $editClientForm->handleRequest($request);

      $filterClientForm = $this->createForm(ClientFilterType::class, $clientTasks, [
        'method' => 'GET',
        'action' => $this->generateUrl("dashboard_admin_view_client", ['id' => $id])
      ]);

      $filterClientForm->handleRequest($request);

      if($editClientForm->isSubmitted() && $editClientForm->isValid() && $request->isMethod("PUT"))
      {
        $editedClient = $editClientForm->getData();
//        dd($editedClient);

        $clientImage = $editClientForm->get('avatar_path')->getData();

        if($clientImage) {
          $originalFilename = pathinfo($clientImage->getClientOriginalName(), PATHINFO_FILENAME);
          $safeFilename = $slugger->slug($originalFilename);
          $newFilename = $safeFilename.'-'.uniqid().'.'.$clientImage->guessExtension();

          // Move file where profile images should be stored
          try {
            $clientImage->move(
              $this->getParameter('client_images'),
              $newFilename
            );
          } catch (FileException $e) {
            return new Response("File Upload Error: $e");
          }

          $editedClient->setAvatarPath($newFilename);
          $editedClient->setAvatarAlt($editClientForm->get('name')->getData());
        }

//
//        $clientRepository->add($client, true);

        $clientRepository->add($editedClient, true);

        return $this->redirectToRoute('dashboard_admin_view_client', ['id' => $id]);
      }

      if($filterClientForm->isSubmitted() && $filterClientForm->isValid() && $request->isMethod("GET"))
      {
        $data = $filterClientForm->getData();
        $filterUser = $data["user"];
        $filterDate = $data["month"];
        $month =(int)$filterDate->format('m');


        $taskRepository = $this->_em->getRepository(Task::class);

        $clientTasks = $taskRepository->getFilteredClientTasks($filterUser, $month, $client);
//        dd($clientTasks);


//        return $this->render('clients/client-profile.html.twig', [
//
//        ]);
      }

      return $this->render('clients/client-profile.html.twig', [
        'client' => $client,
        'clientTasks' => $clientTasks,
        'clientForm' => $editClientForm->createView(),
        'clientFilterForm' => $filterClientForm->createView(),
      ]);
    }

    #[Route('/clients/delete-client', name: 'admin_client_delete')]
    public function deleteClient(Request $request): Response
    {
      if (!$this->isGranted('ROLE_ADMIN'))
      {
        return $this->redirectToRoute('dashboard_my_profile');
      }

      $clientRepository = $this->_em->getRepository(Client::class);
      $clientId = $request->request->get('clientId');
      $client = $clientRepository->find($clientId);

      if($client == null) {
        throw $this->createNotFoundException('Client not found (for deletion)');
      }
      $clientRepository->remove($client, true);

      return $this->redirectToRoute("dashboard_admin_clients");
    }
  }
