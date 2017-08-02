<?php
/**
 * Created by PhpStorm.
 * User: fsjrb
 * Date: 02/08/2017
 * Time: 11:34
 */

namespace ExtratoLista\Auth;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;


class UserProvider implements UserProviderInterface
{

    public function __construct()
    {

    }

    public function loadUserByUsername($username)
    {
        $userData = (new \ExtratoLista\Models\User())->data;

        if (!isset($userData[$username])) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }else{
            $user = $userData[$username];
        }

        return new User($user['username'], $user['password'],  $user['roles'], true, true, true, true);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}