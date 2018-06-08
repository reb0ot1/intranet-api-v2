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


$keyHolds = "";

if ($requestMethod != "OPTIONS") {

    $headers = getallheaders();

    if (array_key_exists("Authorization", $headers)) {
        $keyHolds = $headers["Authorization"];
    }

}

$arguments = [];

$theMethod = new Ember(array_shift($args), \Employees\Config\Routes::$$requestMethod);
$controllerName = $theMethod->getController();
$actionName = $theMethod->getMethod();

if ($controllerName == null || $actionName == null) {
    print_r("URL root not found");
    exit;
}

count($args) > 0 ? array_push($arguments,array_shift($args)) : $arguments ;


if ($requestMethod == "POST" || $requestMethod == "PUT") {
    $payLoad = json_decode(file_get_contents("php://input"), true);

    if ($payLoad) {

        $phpInput = $payLoad;
        if (array_key_exists($controllerName, $payLoad)) {
            $_POST = $payLoad[$controllerName];
        } else if (array_key_exists(substr($controllerName,0,-1), $payLoad)) {
            $_POST = $payLoad[substr($controllerName,0,-1)];
        }
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
    $arguments
//    $args
);




$app = new \Employees\Core\Application($mvcContext);


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


$app->start();
