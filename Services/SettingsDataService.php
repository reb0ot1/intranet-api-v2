<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 2.6.2018 г.
 * Time: 21:30
 */

namespace Employees\Services;


use Employees\Adapter\DatabaseInterface;
use Employees\Models\DB\Dropdown;

class SettingsDataService implements SettingsDataServiceInterface
{

    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function companies()
    {
        $query = "SELECT * FROM company_sub_groups ORDER BY name ASC" ;

        $stmt = $this->db->prepare($query);

        $stmt->execute();

//        var_dump($stmt->fetchAll());

        while ($result = $stmt->fetchObject(Dropdown::class)) {
            yield $result;
        }
    }


    public function positions()
    {
        $query = "SELECT * FROM company_positions ORDER BY name ASC" ;

        $stmt = $this->db->prepare($query);

        $stmt->execute();

//        var_dump($stmt->fetchAll());

        while ($result = $stmt->fetchObject(Dropdown::class)) {
            yield $result;
        }
    }

    public function teams()
    {
        $query = "SELECT * FROM company_teams ORDER BY name ASC" ;

        $stmt = $this->db->prepare($query);

        $stmt->execute();

//        var_dump($stmt->fetchAll());

        while ($result = $stmt->fetchObject(Dropdown::class)) {
            yield $result;
        }

    }
}