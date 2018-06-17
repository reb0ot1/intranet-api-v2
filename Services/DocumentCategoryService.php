<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 10.6.2018 г.
 * Time: 16:05
 */

namespace Employees\Services;


use Employees\Adapter\DatabaseInterface;
use Employees\Models\DB\DocumentCategories;

class DocumentCategoryService implements DocumentCategoriesServiceInterface
{

    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {

        $query = "SELECT id, name, document_folder AS folder FROM document_categories";

        $stmt = $this->db->prepare($query);

        $result = $stmt->execute();

        while ($result = $stmt->fetchObject(DocumentCategories::class)) {
            yield $result;
        }

    }

    public function findOne($categoryId)
    {
        $query = "SELECT name, document_folder AS folder FROM document_categories WHERE id = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$categoryId]);

        $result = $stmt->fetchObject(DocumentCategories::class);

        return $result;
    }

}