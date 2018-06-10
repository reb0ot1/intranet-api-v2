<?php
namespace Employees\Core;

use Employees\Core\MVC\MVCContextInterface;

class Application
{
    const VENDOR_NAMESPACE = 'Employees';
    const CONTROLLERS_NAMESPACE = 'Controllers';
    const CONTROLLERS_SUFFIX = "Controller";
    const SEPARATOR_NAMESPACE = '\\';

    private $mvcContext;

    private $dependencies = [];

    private $resolvedDependencies = [];



    public function registerDependency($interfaceName, $implementationName)
    {
        $this->dependencies[$interfaceName] = $implementationName;
    }


    public function addClass($interfaceName, $instance)
    {
        $implementationName = get_class($instance); // Vseki obekt koito e instanciran s new, tuk shte vyrne imeto na class-a kato string
        $this->dependencies[$interfaceName] = $implementationName;
        $this->resolvedDependencies[$implementationName] = $instance;

    }

    public function __construct(MVCContextInterface $mvcContext)
    {
        $this->mvcContext = $mvcContext;
    }

    public function start()
    {
        //var_dump($this->dependencies);
        $controllerName = $this->mvcContext->getController();
        $controllerFullQualifiedName =
               self::VENDOR_NAMESPACE
             . self::SEPARATOR_NAMESPACE
             . self::CONTROLLERS_NAMESPACE
             . self::SEPARATOR_NAMESPACE
             . ucfirst($controllerName)
             . self::CONTROLLERS_SUFFIX;
        $actionName = $this->mvcContext->getAction();
        $args = $this->mvcContext->getArguments();


    //DA GO RAZGLEDAM DOPYLNITELNO ZA DA SI IZQSNQ KAK STAVAT NESHTATA TUK
        
        $refMethod = new \ReflectionMethod($controllerFullQualifiedName,$actionName);
        $parameters = $refMethod->getParameters();

        foreach ($parameters as $parameter) {

            $parameterClass = $parameter->getClass();

            if ($parameterClass !== null) {
                $className = $parameterClass->getName();

                if (!$parameterClass->isInterface()) {

                    $instance = $this->mapForm($_POST, $parameterClass);

                } else {
                    $instance = $this->resolve($this->dependencies[$className]);
                }

                $args[] = $instance;
            }
        }
     ////////////////////////////////////////////////////////////////////

        if (class_exists($controllerFullQualifiedName)) {

            $controller = $this->resolve($controllerFullQualifiedName);
            call_user_func_array(
                [
                    $controller,
                    $actionName
                ],
                $args

            );
        }
    }

    private function resolve($className)
    {

        if (array_key_exists($className, $this->resolvedDependencies)) {
            return $this->resolvedDependencies[$className];
        }
        $refClass = new \ReflectionClass($className);
        $constructor = $refClass->getConstructor();
        if ($constructor === null) { // nqma argumenti koito sa mu neobhodimi i moje da se napravi direktna instanciq
            $instance = new $className();
            return $instance;
        }
        $parameters = $constructor->getParameters();
        $parametersToInstantiate = [];
//        var_dump($parameters);
//        exit;
        foreach ($parameters as $parameter){
            $interface = $parameter->getClass();

            if ($interface === null) {
               throw new \Exception("Parameters cannot be primitive in order the DI to work!");
            }
            $interfaceName = $interface->getName();

            $implementation = $this->dependencies[$interfaceName];

            if (array_key_exists($implementation, $this->resolvedDependencies)) {
                $implementationInstance = $this->resolvedDependencies[$implementation];
            } else {
                $implementationInstance = $this->resolve($implementation);
                $this->resolvedDependencies[$implementation] = $implementationInstance;
            }



            $parametersToInstantiate[] = $implementationInstance;
        }

        $result = $refClass->newInstanceArgs($parametersToInstantiate);
        $this->resolvedDependencies[$className] = $result;

        return $result;

    }

    private function mapForm($form, \ReflectionClass $parameterClass)
    {
        $className = $parameterClass->getName();
        $instance = new $className();
        foreach ($parameterClass->getProperties() as $field) {
            $field->setAccessible(true);
            if (array_key_exists($field->getName(),$form)){
                $field->setValue($instance, $form[$field->getName()]);
            }
        }

        return $instance;
    }

    public function checkControllerMethodExist()
    {
        $controllerFullQualifiedName = $this->getControllerFullQualifiedName();

        if (class_exists($controllerFullQualifiedName)) {
            $methods = array_map('strtolower',get_class_methods($controllerFullQualifiedName));

            if (in_array(strtolower($this->mvcContext->getAction()), $methods)) {
                return true;
            }
        }

        return false;
    }

    public function checkControllerExists()
    {
        $controllerFullQualifiedName = $this->getControllerFullQualifiedName();

        if (class_exists($controllerFullQualifiedName)) {
            return true;
        }

        return false;
    }

    private function getControllerFullQualifiedName()
    {
        return  self::VENDOR_NAMESPACE
            . self::SEPARATOR_NAMESPACE
            . self::CONTROLLERS_NAMESPACE
            . self::SEPARATOR_NAMESPACE
            . ucfirst($this->mvcContext->getController())
            . self::CONTROLLERS_SUFFIX;
    }
}