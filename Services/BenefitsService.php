<?php

namespace Employees\Services;


use Employees\Adapter\DatabaseInterface;

class BenefitsService implements BenefitsServiceInterface
{

    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function listbenefits()
    {
//        $query = "SELECT * FROM benefits";
        $query = "SELECT id, title, description, date, other FROM benefits";
//        $query = "SELECT ben.id, ben.title, ben.description, ben.date, ben.other, benf.id FROM benefits ben INNER JOIN benefit_files benf ON ben.id = benf.benefit_id";

        $stmt = $this->db->prepare($query);

        if ($stmt->execute()) {
            return $stmt->fetchAll();
        }

        return false;
    }

    public function listbenefitstest()
    {
//        $query = "SELECT * FROM benefits";
//        $query = "SELECT ben.id, ben.title, ben.description, ben.date, ben.other, benf.id FROM benefits";
        $query = "SELECT ben.id, ben.title, ben.description, ben.date, ben.other, benf.id FROM benefits ben INNER JOIN benefit_files benf ON ben.id = benf.benefit_id";

        $stmt = $this->db->prepare($query);

        if ($stmt->execute()) {
            return $stmt->fetchAll();
        }

        return false;
    }
}
