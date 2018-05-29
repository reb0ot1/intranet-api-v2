<?php

namespace Employees\Core\MVC;

interface SessionInterface
{
    public function exists($key) : bool;

    public function get($key);

    public function set($key, $value);

    public function delete($key);

    public function destroy();
}