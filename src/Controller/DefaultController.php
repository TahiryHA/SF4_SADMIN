<?php

namespace App\Controller;

use Doctrine\DBAL\Driver\Connection;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    protected $em;
    protected $db;
    public function __construct(EntityManagerInterface $em, Connection $db)
    {
        $this->em = $em;
        $this->db = $db;
    }
    
    /**
     * @Route("/orcl", name="orcl", methods={"GET","POST"})
     */
    public function orcl()
    {
        // FAITE DES TESTS ICI SUR LA BASE DE DONNEE ORACLE
        return new Response('You are successfully connected',200,[]);
    }

    /**
     * @Route("/", name="home_index")
     */
    public function index()
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute('app.login');
        }

        return $this->redirectToRoute("index");
    }

    /**
     * @Route("/area", name="index")
     */
    public function area()
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute('app.login');

        }

        // dump($this->getStats()['declarations']);
       
        return $this->render('area/home/index.html.twig', [
            'data' => $this->getStats(),
        ]);
    }

    public function getStats()
    {
        // $students = $this->em->createQueryBuilder()->select('count(d.id)')->from('App:Student', 'd')
        // ->getQuery()->getSingleScalarResult();
        // $enseignants = $this->em->createQueryBuilder()->select('count(d.id)')->from('App:Enseignant', 'd')
        // ->getQuery()->getSingleScalarResult();
        // $filieres = $this->em->createQueryBuilder()->select('count(d.id)')->from('App:Filiere', 'd')
        // ->getQuery()->getSingleScalarResult();
        // $classes = $this->em->createQueryBuilder()->select('count(d.id)')->from('App:Classe', 'd')
        // ->getQuery()->getSingleScalarResult();

        // $travailleurs = $this->em->createQueryBuilder('u')->select('u.USERNAME, u.EMAIL, u.ROLES, u.MATRICULE')
        //     ->from('App:User', 'u')
        //     ->where('u.ROLES like :role')
        //     ->setParameter('role', '%EMPLOYER%')
        //     ->setMaxResults(4)
        //     ->getQuery()
        //     ->getResult();
            
        return [
            // 'students' => $students,
            // 'enseignants' => $enseignants,
            // 'filieres' => $filieres,
            // 'classes' => $classes,
            


        ];
    }
    
}
