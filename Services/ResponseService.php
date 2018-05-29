<?php

namespace Employees\Services;

use Employees\Core\MVC\MVCContextInterface;

class ResponseService implements ResponseServiceInterface
{

    private $MVCContext;

    /**
     * ResponseService constructor.
     * @param MVCContextInterface $MVCContext
     */
    public function __construct(MVCContextInterface $MVCContext)
    {
        $this->MVCContext = $MVCContext;
    }

    public function redirect($controller, $action, array $params = [])
    {
        $url = $this->MVCContext->getUriJunk()
            . $controller
            . DIRECTORY_SEPARATOR
            . $action;

        if (!empty($params)) {
            $url .= DIRECTORY_SEPARATOR
                . implode(DIRECTORY_SEPARATOR, $params);
        }
        header("Location: $url");
    }


}