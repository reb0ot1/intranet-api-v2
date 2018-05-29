<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 18.11.2017 г.
 * Time: 18:41
 */

namespace Employees\Controllers;



use Employees\Core\DataReturnInterface;
use Employees\Core\MVC\KeyHolder;
use Employees\Models\Binding\Users\UserLoginBindingModel;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\ResponseServiceInterface;
use Employees\Services\UserServiceInterface;

class AdminController
{

    private $userService;
    private $authenticationService;
    private $responseService;
    private $dataReturn;

    public function __construct(UserServiceInterface $userService,
                                AuthenticationServiceInterface $authenticationService,
                                ResponseServiceInterface $responseService,
                                DataReturnInterface $dataReturn)
    {
        $this->authenticationService = $authenticationService;
        $this->userService = $userService;
        $this->responseService = $responseService;
        $this->dataReturn = $dataReturn;
    }

    public function token(UserLoginBindingModel $bindingModel) {

            $username = $bindingModel->getUsername();
            $password = $bindingModel->getPassword();


            $admin = $this->userService->login($username, $password);

            if ($admin) {

                $token = bin2hex(openssl_random_pseudo_bytes(64));

                if ($this->userService->userToken($admin->getId(), $token)) {

                  return $this->dataReturn->tokenReturn(array("access_token" => $token));

                }

            }

            return $this->dataReturn->accessDenied(401, "Login was unsuccessfull. Please check your username and password.");


     }




}