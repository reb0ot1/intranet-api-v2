<?php

use Employees\Adapter\Database;
use Employees\Config\DbConfig;
use Employees\Config\DefaultParam;
use Employees\Adapter\Ember;
use Employees\Core\MVC\KeyHolder;


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTION');
header('Access-Control-Allow-Headers: Content-Type, Origin, Authorization');
header('Content-Type: application/json; charset=utf-8');


spl_autoload_register(function($class){
    $class = str_replace("Employees\\","", $class);
    $class = str_replace("\\",DIRECTORY_SEPARATOR, $class);
    require_once $class . '.php';
});
ini_set("include_path", '/home/q1q1eu2x/php:' . ini_get("include_path") );
//include_once "Mail.php";

$requestMethod = $_SERVER["REQUEST_METHOD"]; //requested method


if ($requestMethod == "OPTIONS") {

    exit;

}

$uri = $_SERVER['REQUEST_URI'];
$self = $_SERVER['PHP_SELF'];
$self = str_replace("index.php","",$self);

$uri = str_replace($self, '', $uri);

// $uri = substr($uri, 1);


$args = explode("/",$uri);

if (count($args) == 0) {
    exit;
}

$keyHolds = "";

    $headers = getallheaders();

    if (array_key_exists("Authorization", $headers)) {
        $token = explode(" ", $headers["Authorization"]);

        if (count($token) == 2) {
            $keyType = $token[0];
            $keyHolds = $token[1];
        } else {
            $keyHolds = $token[0];
        }
    }


$routes = \Employees\Config\Routes::$$requestMethod;

$arguments = [];
$uiAction = array_shift($args);
$filesContainer = [];

if (!array_key_exists($uiAction, $routes)) {
    exit;
}

//$theMethod = new Ember($controller, $args, \Employees\Config\Routes::$$requestMethod);
//$controllerName = $theMethod->getController();
//$actionName = $theMethod->getMethod();
$controllerName = $routes[$uiAction]["controller"];
$actionName = $routes[$uiAction]["method"];

if (count($routes[$uiAction]["arguments"]) > 0) {

    array_merge($routes[$uiAction]["arguments"], $args);
    $args = $routes[$uiAction]["arguments"];
//    array_unshift($routes[$controller]["arguments"], $args);

}



if ($requestMethod == "POST" || $requestMethod == "PUT") {
    if (count($_POST) == 1) {
        $_POST = array_shift($_POST);
    }

    $payLoad = json_decode(file_get_contents("php://input"), true);

    if ($payLoad) {

        $_POST = array_shift($payLoad);
    }

    if ($_FILES) {

        if (!array_key_exists("name",$_FILES)) {

            $_FILES = array_shift($_FILES);

        }
        $filesArray = [];
        foreach ($_FILES as $key=>$value) {
            if (is_array($value)) {
                $filesArray[$key] = array_shift($value);
            } else {
                $filesArray[$key] = $value;
            }
        }

        $filesContainer[] = $filesArray;

    }
}

//$actionName = array_shift($args);
$dbInstanceName = 'default';

Database::setInstance(
    DbConfig::DB_HOST,
    DbConfig::DB_USER,
    DbConfig::DB_PASSWORD,
    DbConfig::DB_NAME,
    $dbInstanceName
);


$mvcContext = new \Employees\Core\MVC\MVCContext(
    $controllerName,
    $actionName,
    $self,
    $args,
    $uiAction
);



$files = new \Employees\Core\MVC\FileUpload($filesContainer, __DIR__."/".DefaultParam::FileUploadContainer);


$app = new \Employees\Core\Application($mvcContext);

if (!$app->checkControllerMethodExist() || !$app->checkControllerExists()) {

    print_r("URL root not found");
    exit;
}



$app->addClass(
    \Employees\Core\MVC\MVCContextInterface::class,
    $mvcContext
);

$app->addClass(
    \Employees\Core\MVC\SessionInterface::class,
        new \Employees\Core\MVC\Session($_SESSION)
);

    $app->addClass(\Employees\Core\MVC\KeyHolderInterface::class,
        new \Employees\Core\MVC\KeyHolder($keyHolds));

$app->addClass(
 \Employees\Adapter\DatabaseInterface::class,
    Database::getInstance($dbInstanceName)
);

$app->addClass(
    \Employees\Core\MVC\FileUploadInterface::class,
    $files
);

$app->registerDependency(
    \Employees\Core\ViewInterface::class,
    \Employees\Core\View::class
);

$app->registerDependency(
    \Employees\Services\UserServiceInterface::class,
    \Employees\Services\UserService::class
);

$app->registerDependency(
    \Employees\Services\EmployeesServiceInterface::class,
    \Employees\Services\EmployeesService::class
);

$app->registerDependency(
    \Employees\Services\EncryptionServiceInterface::class,
    \Employees\Services\BCryptEncryptionService::class
);

$app->registerDependency(
    \Employees\Services\AuthenticationServiceInterface::class,
    \Employees\Services\AuthenticationService::class
);
$app->registerDependency(
    \Employees\Services\ResponseServiceInterface::class,
    \Employees\Services\ResponseService::class
);

$app->registerDependency(\Employees\Services\CreatingQueryServiceInterface::class,
    \Employees\Services\CreatingQueryService::class);

$app->registerDependency(\Employees\Services\NewsServiceInterface::class,
    \Employees\Services\NewsService::class
    );

$app->registerDependency(\Employees\Services\ImageFromBinServiceInterface::class,
    \Employees\Services\ImageFromBinService::class
    );

$app->registerDependency(\Employees\Core\DataReturnInterface::class,
    \Employees\Core\DataReturn::class
);

$app->registerDependency(\Employees\Services\BenefitsServiceInterface::class,
    \Employees\Services\BenefitsService::class
    );

$app->registerDependency(\Employees\Services\SettingsDataServiceInterface::class,
    \Employees\Services\SettingsDataService::class
);

$app->registerDependency(\Employees\Services\DocumentServiceInterface::class,
    \Employees\Services\DocumentService::class
);
$app->registerDependency(\Employees\Services\DocumentCategoriesServiceInterface::class,
    \Employees\Services\DocumentCategoryService::class
);



$app->start();
