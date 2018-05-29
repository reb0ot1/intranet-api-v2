<?php

namespace Employees\Controllers;


use Employees\Core\ViewInterface;
use Employees\Models\Binding\Users\UserLoginBindingModel;
use Employees\Models\Binding\Users\UserProfileEditBindingModel;
use Employees\Models\Binding\Users\UserRegisterBindingModel;
use Employees\Models\View\ApplicationViewModel;
use Employees\Models\View\UserProfileEditViewModel;
use Employees\Models\View\UserProfileViewModel;
use Employees\Services\AuthenticationService;
use Employees\Services\ResponseServiceInterface;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\UserServiceInterface;

class UsersController
{

    private $view;
    private $userService;
    private $responseService;
    private $authenticationService;

    public function __construct(ViewInterface $view,
                                UserServiceInterface $userService,
                                AuthenticationServiceInterface $authenticationService,
                                ResponseServiceInterface $responseService)
    {
        $this->view = $view;
        $this->userService = $userService;
        $this->responseService = $responseService;
        $this->authenticationService = $authenticationService;
    }

    public function test()
    {
        $this->view->render();
    }

    public function login($errorMess = null)
    {
        $this->view->render(null,null,$errorMess);
    }

    public function loginPost(UserLoginBindingModel $bindingModel)
    {
        $username = $bindingModel->getEmail();
        $password = $bindingModel->getPassword();

        if ($this->userService->login($username, $password)) {
            $id = $this->authenticationService->getUserId();
            if ($id == 4) {
                $this->responseService->redirect("users","demo");
            } else {
                $this->responseService->redirect("users","profile");
            }

        } else {
            $this->responseService->redirect("users","login",["err"]);
        }

        //throw new \Exception();
    }

    public function logout()
    {
        $this->authenticationService->logout();
        $this->responseService->redirect("users","login");
    }

    public function register()
    {
        //$viewModel = new ApplicationViewModel("blog");

        $this->view->render();
    }

    public function registerPost(UserRegisterBindingModel $bindingModel)
    {
        $username = $bindingModel->getEmail();
        $password = $bindingModel->getPassword();

        //$service = $service->register();

        if($this->userService->register($username, $password)) {
            $this->responseService->redirect("users","login");
        }

        throw new \Exception();
    }

    public function profile()
    {

        if (!$this->authenticationService->isAuthenticated()) {
            $this->responseService->redirect("users","login");
        }

        $id = $this->authenticationService->getUserId();
        $user = $this->userService->findOne($id);

        if($id == 4) {
            $this->responseService->redirect("users","demo");
        }

        $viewModel = new UserProfileViewModel();
        $viewModel->setUsername($user->getEmail());
        $viewModel->setId($id);
        if($viewModel->getId() == 3) {
//            $theViewLink = "views/GemSeekTableauDashboard_UAStudySample_Jun2017v4/UAStudy";
            $theViewLink = "views/BookStory/Home";
        }
        else {
//            $theViewLink = "views/GemSeekTableauDashboard_UAStudySample_Jun2017v4/UAStudy";
            $theViewLink = "views/BookStory/Home";
        }
        $ticket = new TicketCreationViewModel("test123","13.93.82.250",$theViewLink);

        $this->view->render($ticket);
    }

    public function train()
    {

        if (!$this->authenticationService->isAuthenticated()) {
            $this->responseService->redirect("users","login");
        }



        $id = $this->authenticationService->getUserId();
        $user = $this->userService->findOne($id);

        if($id == 4) {
            $this->responseService->redirect("users","demo");
        }

        $viewModel = new UserProfileViewModel();
        $viewModel->setUsername($user->getEmail());
        $viewModel->setId($id);
        if($viewModel->getId() == 3) {
//            $theViewLink = "views/GemSeekTableauDashboard_UAStudySample_Jun2017v4/UAStudy";
            $theViewLink = "views/BookStory/Train";
        } else {
//            $theViewLink = "views/GemSeekTableauDashboard_UAStudySample_Jun2017v4/UAStudy";
            $theViewLink = "views/BookStory/Train";
        }
        $ticket = new TicketCreationViewModel("test123","13.93.82.250",$theViewLink);

        $this->view->render($ticket);
    }

    public function info()
    {

        if (!$this->authenticationService->isAuthenticated()) {
            $this->responseService->redirect("users","login");
        }

        $id = $this->authenticationService->getUserId();
        $user = $this->userService->findOne($id);

        if($id == 4) {
            $this->responseService->redirect("users","demo");
        }

        $viewModel = new UserProfileViewModel();
        $viewModel->setUsername($user->getEmail());
        $viewModel->setId($id);
        if($viewModel->getId() == 3) {
//            $theViewLink = "views/GemSeekTableauDashboard_UAStudySample_Jun2017v4/UAStudy";
            $theViewLink = "views/BookStory/Info";
        } else {
//            $theViewLink = "views/GemSeekTableauDashboard_UAStudySample_Jun2017v4/UAStudy";
            $theViewLink = "views/BookStory/Info";
        }
        $ticket = new TicketCreationViewModel("test123","13.93.82.250",$theViewLink);

        $this->view->render($ticket);
    }


    public function demo()
    {

        if (!$this->authenticationService->isAuthenticated()) {
            $this->responseService->redirect("users","login");
        }

        $id = $this->authenticationService->getUserId();
        $user = $this->userService->findOne($id);

        if($id != 4) {
            $this->responseService->redirect("users","login");
        }

        $viewModel = new UserProfileViewModel();
        $viewModel->setUsername($user->getEmail());
        $viewModel->setId($id);

            $theViewLink = "views/TableauDashboardSample_2017/MarketForecast2020";

        $ticket = new TicketCreationViewModel("test123","13.93.82.250",$theViewLink);

        $this->view->render($ticket);
    }

    public function demo2()
    {

        if (!$this->authenticationService->isAuthenticated()) {
            $this->responseService->redirect("users","login");
        }

        $id = $this->authenticationService->getUserId();
        $user = $this->userService->findOne($id);

        if($id != 4) {
            $this->responseService->redirect("users","login");
        }

        $viewModel = new UserProfileViewModel();
        $viewModel->setUsername($user->getEmail());
        $viewModel->setId($id);

        $theViewLink = "views/TableauDashboardSample_2017/MarketShareDataTable";

        $ticket = new TicketCreationViewModel("test123","13.93.82.250",$theViewLink);

        $this->view->render($ticket);
    }


//    public function profileEdit($id)
//    {
//        $currentUserId = $this->authenticationService->getUserId();
//        if ($currentUserId !== $id) {
//            $this->responseService->redirect("users","profileEdit", [$currentUserId]);
//        }
//
//        $user = $this->userService->findOne($id);
//
//        $viewModel = new UserProfileEditViewModel(
//            $id,
//            $user->getUsername(),
//            $user->getPassword(),
//            $user->getEmail(),
//            false
//            );
//        return $this->view->render($viewModel);
//    }
//
//    public function ProfileEditPost($id, UserProfileEditBindingModel $bindingModel)
//    {
//        $currentUserId = $this->authenticationService->getUserId();
//        if ($currentUserId !== $id) {
//            $this->responseService->redirect("users","profileEdit", [$currentUserId]);
//        }
//
//        $bindingModel->setId($id);
//
//        $this->userService->edit($bindingModel);
//
//        $this->responseService->redirect("users","profile",[$id]);
//    }
}