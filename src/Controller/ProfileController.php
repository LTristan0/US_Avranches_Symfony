<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Services\ImageUploaderHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profile', name: 'app_profile')]
class ProfileController extends AbstractController
{   
    #[Route('/', name: 'app_profile_index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/personnal', name: 'app_profile_personnal')]
    public function personnal(ImageUploaderHelper $imageUploaderHelper): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur actuellement connecté

        $form = $this->createForm(ProfileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errorMessage = $imageUploaderHelper->uploadImage($form, $user);
            if (!empty($errorMessage)) {
                $this->addFlash ('danger', 'An error has occured: '. $errorMessage);
            }
            $userRepository->save($user, true);

            return $this->render('/profile/profile.html.twig', [
                'connected_user' => $user,
                'controller_name' => 'ProfileController',
                'form' => $form,
            ]);
        }

        return $this->render('profile/profile.html.twig', [
            'connected_user' => $user,
            'controller_name' => 'ProfileController',
            'form' => $form,
        ]);
    }





}