<?php

  namespace App\Controller;

  use App\Entity\Client;
  use App\Form\ClientType;
  use Doctrine\Persistence\ManagerRegistry;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\Security\Core\Security;

  #[Route('/$2a$12$99iZHSovZPM6xvwAMeFeoONS69pt45Udgplt4DAdT7fDQvX12nBte/admin', name: "dashboard_")]
  class ClientController extends AbstractController
  {
    private $security;
    private $_em;

    public function __construct(Security $security, ManagerRegistry $doctrine)
    {
      $this->security = $security;
      $this->_em = $doctrine;

    }

    #[Route('/clients', name: 'admin_clients', methods: ['GET', "POST"])]
    public function index(Request $request): Response
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
        $clientRepository->add($client, true);
        return $this->redirectToRoute('dashboard_admin_clients');
      }

      $user = $this->security->getUser();
      $clients = $clientRepository->findAll();

      return $this->render('clients/clients.html.twig', [
        'LoggedUser' => $user,
        'clients' => $clients,
        'clientForm' => $form->createView()
      ]);
    }

    #[Route('/clients/{id}', name: 'admin_view_client', methods: ["GET", "PUT"])]
    public function viewClientProfile($id, Request $request)
    {
      if (!$this->isGranted('ROLE_ADMIN'))
        if (!$this->isGranted('ROLE_DEVELOPER')) {
          return $this->redirectToRoute('dashboard_logout');
        } else {
          return $this->redirectToRoute('dashboard_my_profile');
        }

      $clientRepository = $this->_em->getRepository(Client::class);
      $client = $clientRepository->find($id);

      $editClientForm = $this->createForm(ClientType::class, $client, [
        'method' => 'PUT',
        'action' => $this->generateUrl("dashboard_admin_view_client", ['id' => $id])
      ]);

      $editClientForm->handleRequest($request);

      if($editClientForm->isSubmitted() && $editClientForm->isValid() && $request->isMethod("PUT"))
      {
        $editedClient = $editClientForm->getData();
//        dd($editedClient);

        $clientRepository->add($editedClient, true);

        return $this->redirectToRoute('dashboard_admin_view_client', ['id' => $id]);
      }

      return $this->render('clients/client-profile.html.twig', [
        'client' => $client,
        'clientForm' => $editClientForm->createView()
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
