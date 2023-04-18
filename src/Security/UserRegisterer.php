<?php


namespace App\Security;


use App\Entity\User;
use App\Repository\UserRepository;
use http\Exception\RuntimeException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegisterer
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function register(string $email, string $password)
    {
        if ($this->userRepository->findOneBy(['email' => $email]) !== null)  {
            throw SorryCouldNotCreateUser::becauseUserWithEmailAlreadyExists($email);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setIsMerchant(false);

        $this->userRepository->persist($user);
    }

    public function registerRestaurant(string $email, string $password): User
    {
        if ($this->userRepository->findOneBy(['email' => $email]) !== null)  {
            throw SorryCouldNotCreateUser::becauseUserWithEmailAlreadyExists($email);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setIsMerchant(true);
        $user->setRoles(['ROLE_RESTAURANT']);

        $this->userRepository->persist($user);

        return $user;
    }

}