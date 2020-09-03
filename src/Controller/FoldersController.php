<?php

namespace App\Controller;

use App\Utils\Utils;
use App\Entity\Folders;
use App\Form\FoldersType;
use App\Services\ApiService;
use App\Repository\FoldersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/folders")
 */
class FoldersController extends AbstractController
{
    protected $apiService;
    protected $breadcrumbs;
    protected $utils;


    public function __construct(ApiService $apiService, Utils $utils)
    {
        $this->apiService = $apiService;
        $this->utils = $utils;
        $this->breadcrumbs = $utils->_breadcrumbs();
    }
    /**
     * @Route("/", name="folders_index", methods={"GET"})
     */
    public function index(FoldersRepository $foldersRepository): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "index");
        $this->breadcrumbs->addItem("Dossier");
        $this->breadcrumbs->addItem("Liste");

        return $this->render('folders/index.html.twig', [
            'folders' => $foldersRepository->findBy(['hide' => false,'archive' => false,'trash' => false]),
        ]);
    }

    /**
     * @Route("/hide", name="folders_hide", methods={"GET"})
     */
    public function hide(FoldersRepository $foldersRepository): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "index");
        $this->breadcrumbs->addItem("Dossier");
        $this->breadcrumbs->addItem("Cacher");

        return $this->render('folders/hide.html.twig', [
            'folders' => $foldersRepository->findBy(['hide' => true]),
        ]);
    }

    /**
     * @Route("/archive", name="folders_archive", methods={"GET"})
     */
    public function archive(FoldersRepository $foldersRepository): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "index");
        $this->breadcrumbs->addItem("Dossier");
        $this->breadcrumbs->addItem("Archive");

        return $this->render('folders/archive.html.twig', [
            'folders' => $foldersRepository->findBy(['archive' => true]),
        ]);
    }


    /**
     * @Route("/trash", name="folders_trash", methods={"GET"})
     */
    public function trash(FoldersRepository $foldersRepository): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "index");
        $this->breadcrumbs->addItem("Dossier");
        $this->breadcrumbs->addItem("Corbeille");

        return $this->render('folders/trash.html.twig', [
            'folders' => $foldersRepository->findBy(['trash' => true]),
        ]);
    }


    /**
     * @Route("/new", name="folders_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "index");
        $this->breadcrumbs->addItem("Dossier");
        $this->breadcrumbs->addItem("Ajout");

        $folder = new Folders();
        $form = $this->createForm(FoldersType::class, $folder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $folder->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setHide(false)
            ->setArchive(false)
            ->setTrash(false)

            ;

            $entityManager->persist($folder);
            $entityManager->flush();

            return $this->redirectToRoute('folders_index');
        }

        return $this->render('folders/new.html.twig', [
            'folder' => $folder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="folders_show", methods={"GET"})
     */
    public function show(Folders $folder): Response
    {
        $this->breadcrumbs->prependRouteItem("Dossier", "folders_index");
        $this->breadcrumbs->addItem($folder->getName());
        // $this->breadcrumbs->addItem("Fichiers");
        
        return $this->render('folders/show.html.twig', [
            'name' => $folder->getName(),
            'files' => $folder->getFiles(),
        ]);
    }

    /**
     * @Route("/{id}/hideA", name="folder_hide_action", methods={"GET"})
     */
    public function hideA(Folders $folder): Response
    {
        if ($folder->getHide()) {
            $folder->setHide(false);
        }else {
            $folder->setHide(true);

        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($folder);
        $entityManager->flush();

        return $this->redirectToRoute('folders_index');
    }

    /**
     * @Route("/{id}/archiveA", name="folder_archive_action", methods={"GET"})
     */
    public function archiveA(Folders $folder): Response
    {
        if ($folder->getArchive()) {
            $folder->setArchive(false);

        }else {
            $folder->setArchive(true);

        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($folder);
        $entityManager->flush();

        return $this->redirectToRoute('folders_index');

    }

    /**
     * @Route("/{id}/trashA", name="folder_trash_action", methods={"GET"})
     */
    public function trashA(Folders $folder): Response
    {
        if ($folder->getTrash()) {

            $folder->setTrash(false);
            
        }else{
            $folder->setTrash(true);

        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($folder);
        $entityManager->flush();

        return $this->redirectToRoute('folders_index');

    }

    /**
     * @Route("/{id}/restoreA", name="folder_restore_action", methods={"GET"})
     */
    public function restoreA(Folders $folder): Response
    {
        $folder->setTrash(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($folder);
        $entityManager->flush();

        return $this->redirectToRoute('folders_index');

    }

    /**
     * @Route("/{id}/edit", name="folders_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Folders $folder): Response
    {
        $form = $this->createForm(FoldersType::class, $folder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('folders_index');
        }

        return $this->render('folders/edit.html.twig', [
            'folder' => $folder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="folders_delete", methods={"DELETE","GET"})
     */
    public function delete(Request $request, Folders $folder): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$folder->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($folder);
            $entityManager->flush();
        // }

        return $this->redirectToRoute('folders_index');
    }
}
