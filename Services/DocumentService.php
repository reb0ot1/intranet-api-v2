<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 10.6.2018 г.
 * Time: 15:26
 */

namespace Employees\Services;


use Employees\Adapter\DatabaseInterface;
use Employees\Models\Binding\Document\DocumentBindingModel;
use Employees\Models\DB\Employee;

class DocumentService implements DocumentServiceInterface
{
    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function all()
    {
        // TODO: Implement all() method.
        $query = "SELECT * FROM document_files";
    }

    public function findOne($documentId)
    {
        // TODO: Implement findOne() method.
    }

    /**
     * @param \Employees\Models\Binding\Document\DocumentBindingModel $documentBindingModel
     */
    public function add($documentBindingModel)
    {

        $this->db->beginTransaction();
        try {
            $query = "INSERT INTO document_files (category_id, file_name, added) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);

            foreach ($documentBindingModel->getFiles() as $file) {
                $result = $stmt->execute([$documentBindingModel->getCategoryId(), $file, date('m-d-Y h:i:s')]);
            }

        $this->db->commit();
            return true;
        } catch (\Exception $e) {

            $this->db->rollBack();

            throw $e;

        }

    }

    public function remove($documentId)
    {
        // TODO: Implement remove() method.
    }

}