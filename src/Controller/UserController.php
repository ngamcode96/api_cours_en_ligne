<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    #[Route('/api/user/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request, ValidatorInterface $validator, UserPasswordHasherInterface $password_hasher, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();

        try {

            $user = $serializer->deserialize($data, Utilisateur::class, 'json');
            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $hash = $password_hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);

            try {
                $em->persist($user);
                $em->flush();
            } catch (UniqueConstraintViolationException $error) {
                return $this->json(
                    [
                        'status' => 400,
                        'message' => "l'adresse email saisie existe déjà!"
                    ],
                    400
                );
            }
            $em->persist($user);
            $em->flush();

            return $this->json(
                [
                    'status' => 201,
                    'message' => "Inscription reussie",
                    'data' => $user
                ],
                201
            );
        } catch (NotEncodableValueException $e) {

            return $this->json(
                [
                    'status' => 400,
                    'message' => $e->getMessage()
                ],
                400
            );
        }
    }

    #[Route('/api/user/login', name: 'user_login', methods: ['POST'])]
    public function  login(#[CurrentUser] ?Utilisateur $user)
    {
        if (null === $user) {
            return $this->json([
                'message' => 'Adresse email ou mot de passe incorrect',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => "",
        ]);
    }

    #[Route('/api/user/logout', name: 'user_logout', methods: ['GET'])]
    public function  logout()
    {


        return $this->json([
            'message' => 'logout success'
        ], 200);
    }
}
