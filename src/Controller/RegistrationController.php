<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator  // Ajout du validateur
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // 🔴 Vérification des erreurs de validation
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                // 🔴 DEBUG : Afficher les erreurs pour voir ce qui est détecté
                foreach ($form->getErrors(true) as $error) {
                    dump($error->getMessage()); // Symfony var_dump pour voir les erreurs dans la debug bar
                }
            }
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $this->addFlash('error', 'Un compte avec cet email existe déjà.');
                return $this->redirectToRoute('app_register'); // Redirection pour afficher le message
            }

            $phone = $form->get('phone')->getData();
if (!preg_match('/^\d{10,20}$/', $phone)) { 
    $this->addFlash('error', 'Le numéro de téléphone doit contenir uniquement des chiffres (entre 10 et 20 caractères).');
    return $this->redirectToRoute('app_register');
}


            // $plainPassword = $form->get('plainPassword')->getData();
            // if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!@#$%^&*()_+={}\[\]:;"\'<>?,.\/\-]{6,}$/', $plainPassword)) {
            //     $this->addFlash('error', 'Le mot de passe doit contenir au moins 6 caractères, incluant au moins une lettre et un chiffre.');
            //     return $this->redirectToRoute('app_register');
            // }

            $plainPassword = $form->get('plainPassword')->getData();
if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[a-z])[A-Za-z\d!@#$%^&*()_+={}\[\]:;"\'<>?,.\/\-]{6,}$/', $plainPassword)) {
    $this->addFlash('error', 'Le mot de passe doit contenir au moins 6 caractères, incluant au moins une lettre majuscule, une lettre minuscule et un chiffre.');
    return $this->redirectToRoute('app_register');
}

            

           $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setRoles(['ROLE_USER']);
        
            $entityManager->persist($user);
            $entityManager->flush();
        
            $this->addFlash('success', 'Votre compte a été créé avec succès !');
        
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            
        ]);
    }
}
