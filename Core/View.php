<?php

namespace Employees\Core;

use Employees\Core\MVC\MVCContextInterface;

class View implements ViewInterface
{
    const VIEW_FOLDER = 'views';
    const PARTIALS_FOLDER = 'partials';
    const HEADER_NAME = 'header';
    const FOOTER_NAME = 'footer';
    const STATIC_EXTENSION = '.php';
    const VIEW_EXTENSION = '.php';

    private $mvcContext;

    public function __construct(MVCContextInterface $MVCContext)
    {
        $this->mvcContext = $MVCContext;
    }

    public function render($templateName = null, $model = null, $message = null)
    {
       $controller = $this->mvcContext->getController();
       $action = $this->mvcContext->getAction();
        if ($templateName === null) {
            $templateName = $controller . DIRECTORY_SEPARATOR . $action;
        } else if (!is_string($templateName)) {
            $model = $templateName;
            $templateName = $controller . DIRECTORY_SEPARATOR . $action;
        }

        include self::VIEW_FOLDER
            . DIRECTORY_SEPARATOR
            .self::PARTIALS_FOLDER
            .DIRECTORY_SEPARATOR
            .self::HEADER_NAME
            .self::STATIC_EXTENSION;

        include self::VIEW_FOLDER
            .DIRECTORY_SEPARATOR
            . $templateName
            . self::VIEW_EXTENSION;

        include self::VIEW_FOLDER
            . DIRECTORY_SEPARATOR
            .self::PARTIALS_FOLDER
            .DIRECTORY_SEPARATOR
            .self::FOOTER_NAME
            .self::STATIC_EXTENSION;
    }

    public function url($controller, $action, $params = [])
    {
        $url = $this->mvcContext->getUriJunk()
            . $controller
            . DIRECTORY_SEPARATOR
            . $action;

        if (!empty($params)) {
           $url .= DIRECTORY_SEPARATOR
               . implode(DIRECTORY_SEPARATOR, $params);
        }

        return $url;
    }
}