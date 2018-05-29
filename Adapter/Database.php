<?php
namespace Employees\Adapter;


class Database implements DatabaseInterface
{
    private static $instances = [];

    private $pdo;

    private function __construct($host,$user,$pass, $dbName)
    {
        $this->pdo = new \PDO("mysql:host=$host;dbname=$dbName", $user, $pass, array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => true));
    }

    public function prepare($statement) : DatabaseStatementInterface
    {
        return new DatabaseStatement($this->pdo->prepare($statement));
    }


    public static function setInstance($host, $user, $pass, $dbName, $instanceName)
    {
        self::$instances[$instanceName] = new Database($host, $user, $pass, $dbName);
    }

    public static function getInstance($instanceName)
    {
        return self::$instances[$instanceName];
    }
}

// Testing github and sourcetree environment
