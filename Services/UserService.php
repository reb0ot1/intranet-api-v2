<?php
namespace Employees\Services;

use Employees\Core\MVC\SessionInterface;
use Employees\Adapter\Database;
use Employees\Adapter\DatabaseInterface;
use Employees\Models\Binding\Users\UserProfileEditBindingModel;
use Employees\Models\DB\User;

class UserService implements UserServiceInterface
{
    /**
     * @var Database
     */

    private $db;

    /**
     * @var SessionInterface
     */
    private $session;

    private $encryptionService;

    public function __construct(DatabaseInterface $db, SessionInterface $session, EncryptionServiceInterface $encryptionService)
    {
        $this->db = $db;
        $this->session = $session;
        $this->encryptionService = $encryptionService;
    }

    public function login($username, $password)
    {

        $query = "SELECT 
                        id,
                        login,
                        password
                  FROM 
                      admins
                  WHERE
                      login =  ? AND password = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$username, $password]);

        /** @var User $user */

        $user = $stmt->fetchObject(User::class);



        if ($user) {

            return $user;
        }

            return false;
//            $hash = $user->getPassword();
//
//            if ($this->encryptionService->verify($password, $hash)) {
//                $this->session->set('id',$user->getId());
//                return true;
//            } else {
//
//                return false;
//            }



    }

    public function register($username, $password) : bool
    {
        $query = "INSERT INTO users (email, password) VALUES (?,?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(
            [
                $username,
                $this->encryptionService->hash($password)
            ]
        );

    }

    public function findOne($id) : User
    {
        $query = "SELECT
                        id,
                        email
                  FROM 
                      users
                  WHERE
                      id =  ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        /** @var User $user */
        $user = $stmt->fetchObject(User::class);
        return $user;
    }

    public function edit(UserProfileEditBindingModel $model) : bool
    {

        if ($model->getPassword() != $model->getConfirmPassword()) {
            return false;
        }
        $query = "UPDATE users SET username=?, password=?, email=? WHERE id = ?";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
           $model->getUsername(),
            $this->encryptionService->hash($model->getPassword()),
            $model->getEmail(),
            $model->getId()
        ]);
    }

    public function userToken($userId, $token) : bool
    {
        $query = "UPDATE admin_tokens SET token = ?, created = ?, expire = ? WHERE admin_id = ?";

        $stmt = $this->db->prepare($query);

        $created = time();
        $expire = time() + (30*60);

        return $stmt->execute([$token, $created, $expire, $userId]);

    }
}
