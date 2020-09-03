<?php

namespace App\Controller;

use App\Entity\Files;
use App\Form\FilesType;
use App\Services\ApiService;
use App\Repository\FilesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Utils\Utils;

/**
 * @Route("/files")
 */
class FilesController extends AbstractController
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
     * @Route("/", name="files_index", methods={"GET"})
     */
    public function index(FilesRepository $filesRepository): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "index");
        $this->breadcrumbs->addItem("Fichier");
        $this->breadcrumbs->addItem("Liste");

        return $this->render('files/index.html.twig', [
            'files' => $filesRepository->findBy(['hide' => false, 'archive' => false, 'trash' => false]),
        ]);
    }

    /**
     * @Route("/archives", name="files_archive", methods={"GET"})
     */
    public function archive(FilesRepository $filesRepository): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "index");
        $this->breadcrumbs->addItem("Fichier");
        $this->breadcrumbs->addItem("Archive");

        return $this->render('files/archive.html.twig', [
            'files' => $filesRepository->findBy(['archive' => true]),
        ]);
    }

    /**
     * @Route("/hides", name="files_hide", methods={"GET"})
     */
    public function hide(FilesRepository $filesRepository): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "index");
        $this->breadcrumbs->addItem("Fichier");
        $this->breadcrumbs->addItem("Cacher");

        return $this->render('files/hide.html.twig', [
            'files' => $filesRepository->findBy(['hide' => true]),
        ]);
    }

    /**
     * @Route("/trashes", name="files_trash", methods={"GET"})
     */
    public function trash(FilesRepository $filesRepository): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "index");
        $this->breadcrumbs->addItem("Fichier");
        $this->breadcrumbs->addItem("Corbeille");

        return $this->render('files/trash.html.twig', [
            'files' => $filesRepository->findBy(['trash' => true]),
        ]);
    }

    /**
     * @Route("/new", name="files_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "index");
        $this->breadcrumbs->addItem("Fichier");
        $this->breadcrumbs->addItem("Ajout");

        $file = new Files();
        $form = $this->createForm(FilesType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $dataFile = $form->get('filename')->getData();
            $file
            ->setSize($dataFile->getSize())
            ->setType($dataFile->getMimeType())
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setHide(false)
            ->setArchive(false)
            ->setTrash(false)

            ;

            $this->apiService->add_file($form,'upload_directory',$file,'filename');

            return $this->redirectToRoute('files_index');
        }

        return $this->render('files/new.html.twig', [
            'file' => $file,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="files_show", methods={"GET"})
     */
    public function show(Files $file): Response
    {
        return $this->render('files/show.html.twig', [
            'file' => $file,
        ]);
    }

    /**
     * @Route("/{id}/hideA", name="file_hide_action", methods={"GET"})
     */
    public function hideA(Files $file): Response
    {
        if ($file->getHide()) {
            $file->setHide(false);
        }else{
            $file->setHide(true);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($file);
        $entityManager->flush();

        return $this->redirectToRoute('files_index');
    }

    /**
     * @Route("/{id}/archiveA", name="file_archive_action", methods={"GET"})
     */
    public function archiveA(Files $file): Response
    {
        if ($file->getArchive()) {
            $file->setArchive(false);
            
        }else {
            $file->setArchive(true);

        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($file);
        $entityManager->flush();

        return $this->redirectToRoute('files_index');

    }

    /**
     * @Route("/{id}/trashA", name="file_trash_action", methods={"GET"})
     */
    public function trashA(Files $file): Response
    {
        $file->setTrash(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($file);
        $entityManager->flush();

        return $this->redirectToRoute('files_index');

    }

    /**
     * @Route("/{id}/restoreA", name="file_restore_action", methods={"GET"})
     */
    public function restoreA(Files $file): Response
    {
        $file->setTrash(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($file);
        $entityManager->flush();

        return $this->redirectToRoute('files_index');

    }

    /**
     * @Route("/{id}/edit", name="files_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Files $file): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "index");
        $this->breadcrumbs->addItem("Fichier");
        $this->breadcrumbs->addItem("Modification");

        $form = $this->createForm(FilesType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('files_index');
        }

        return $this->render('files/edit.html.twig', [
            'file' => $file,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="files_delete", methods={"DELETE","GET"})
     */
    public function delete(Request $request, Files $file): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$file->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $this->apiService->removeFile($file->getFilename(), 'uploads/');
            $entityManager->remove($file);
            $entityManager->flush();
        // }

        return $this->redirectToRoute('files_index');
    }

    /**
     * @Route("/{filename}/generate", name="generate", methods={"GET"})
     */
    public function generate(Request $request,$filename)
    {
        
        $path = $request->getSchemeAndHttpHost().'/uploads/';
        $content = file_get_contents($path.$filename);
    
        $response = new Response();
    
        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);
    
        $response->setContent($content);

        return $response;
    }
}
