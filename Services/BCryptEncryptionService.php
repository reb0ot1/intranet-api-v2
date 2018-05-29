<?php
namespace Employees\Services;

class BCryptEncryptionService implements EncryptionServiceInterface
{
    public function hash(string $password):string  {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify(string $password, string $hash) : bool
    {
        return password_verify($password, $hash);
    }

    public function md5generator($string) : string {
        return md5($string);
    }

}