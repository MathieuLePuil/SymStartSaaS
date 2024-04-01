<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class GithubController extends AbstractController
{
    #[Route('/login/github', name: 'github_connect', methods: ['GET'])]
    public function githubConnect(ClientRegistry $clientRegistry): Response
    {
        $client = $clientRegistry->getClient('github');
        return $client->redirect(['user:email', 'read:user']);
    }

    #[Route('/login/github/check', name: 'github_connect_check', methods: ['GET'])]
    public function githubConnectCheck(ClientRegistry $clientRegistry, EntityManagerInterface $em, TokenStorageInterface $tokenStorage, SessionInterface $session): Response
    {
        $client = $clientRegistry->getClient('github');

        try {
            $user = $client->fetchUser();

            $userData = $user->toArray();

            $fullName = $userData['name'];
            $nameParts = explode(' ', $fullName);

            $firstName = array_shift($nameParts);
            $lastName = implode(' ', $nameParts);

            $userRepository = $em->getRepository(User::class);
            $existingUser = $userRepository->findOneBy(['email' => $userData['email']]);

            if($existingUser) {
                $existingUser->setFirstname($firstName);
                $existingUser->setLastname($lastName);
                $newUser = $existingUser;
            } else {
                $newUser = new User();
                $newUser->setUsername($userData['login']);
                $newUser->setEmail($userData['email']);
                $newUser->setOauth('github');
                $newUser->setRoles(['ROLE_USER']);
                $newUser->setProfilePicture($userData['avatar_url']);
                $newUser->setPassword('github');
                $newUser->setFirstname($firstName);
                $newUser->setLastname($lastName);
            }

            $em->persist($newUser);
            $em->flush();

            $token = new UsernamePasswordToken($newUser, 'main', $newUser->getRoles());

            $tokenStorage->setToken($token);
            $session->set('_security_main', serialize($token));

            return $this->redirectToRoute('app_home');

        } catch (IdentityProviderException $e) {
            var_dump($e->getMessage()); die;
        }
    }
}
