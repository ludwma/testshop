<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;
use Nette\Application\UI\Form;
use App\user;


class UserManager implements Nette\Security\IAuthenticator {

    private $enmanager;

    //database inject
    public function __construct(\Kdyby\Doctrine\EntityManager $enmanager) {
	$this->enmanager = $enmanager;
    }

    //user authentication
    //provede autentikaci a v db upravi cas posledniho loginu
    public function authenticate(array $credentials) {
        list($username, $password) = $credentials;
        $row = $this->enmanager->getRepository(\App\user::class)->findOneBy(["username"=>$username]);
        if (empty($row) || !Passwords::verify($password, $row->password)) {
            throw new Nette\Security\AuthenticationException;
        }
	$row->lastlogin = new Nette\Utils\DateTime();
        $this->enmanager->flush();

        return new Nette\Security\Identity($row->user_id, $row->role,["username"=>$row->username,"user_id"=>$row->user_id]);
    }
    
    
    //add new user
    public function add($username, $email, $password) {
        try {
	    $user = new user;
	    $user->username = $username;
	    $user->password = Passwords::hash($password);
	    $user->email = $email;
	    $this->enmanager->persist($user);
	    $this->enmanager->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            return FALSE;
        }
        return TRUE;
    }

    //select info about user
    public function getUserInfo($id) {
        $row = $this->enmanager->getRepository(\App\user::class)->findOneBy(["user_id"=>$id]);
        return $row;
    }

    //edit user
    public function editUser($jmeno, $email, $heslo, $id) {
	try{
	    $userDao = $this->enmanager->getRepository(user::class);
	    $user = $userDao->findOneBy(["user_id"=>$id]);
	    $user->username = $jmeno;
	    $user->email = $email;
	    $user->password = password_hash($heslo, PASSWORD_DEFAULT);
	    $this->enmanager->flush();
            }catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            return FALSE;
        }
	return TRUE;
    }
}

