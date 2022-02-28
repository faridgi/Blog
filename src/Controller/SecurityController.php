<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class SecurityController extends AbstractController
{
    private $manager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $manager,UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->manager = $manager;
    }
    /**
     * @Route("/register", name="security_register")
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        // analyse de la requête par le formulaire
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //traitement des données reçues du formulaire
            

            $user->setPassword($this->passwordHasher->hashPassword($user,$user->getPassword()));
            $this->manager->persist($user);
            $this->manager->flush();
            return $this->redirectToRoute("security_login");
            
        }

       
        return $this->render('security/index.html.twig', [
            'controller_name' => 'Formulaire d\'inscription',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */

    public function login(): Response
    {
        return $this->render('security/login.html.twig');
    }

     /**
     * @Route("/logout", name="security_logout")
     */

    public function logout()
    {
      
    }
}
