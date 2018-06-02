<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 2.6.2018 г.
 * Time: 21:30
 */

namespace Employees\Services;


use Employees\Adapter\DatabaseInterface;
use Employees\Models\DB\Subgroups;

class SettingsDataService implements SettingsDataServiceInterface
{

    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function getSubCompanies()
    {
        $query = "SELECT * FROM company_sub_groups";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

//        var_dump($stmt->fetchAll());

        while ($result = $stmt->fetchObject(Subgroups::class)) {
            yield $result;
        }
    }

    public function getPossitions()
    {
        // TODO: Implement getPossitions() method.
    }

}