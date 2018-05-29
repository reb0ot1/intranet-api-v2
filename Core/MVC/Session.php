<?php
namespace Employees\Core\MVC;

class Session implements SessionInterface
{
    private $data = [];

    public function __construct(&$data)
    {
        $this->data = &$data;
    }

    public function exists($key) : bool
    {
        return array_key_exists($key, $this->data);
    }

    public function get($key)
    {
        // TODO: Implement get() method.
        return $this->data[$key];
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function delete($key)
    {
        // TODO: Implement unset() method.
        unset($this->data[$key]);
    }

    public function destroy()
    {
        unset($this->data);
        session_destroy();
    }

}