<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 10.6.2018 г.
 * Time: 15:26
 */

namespace Employees\Services;


use Employees\Adapter\DatabaseInterface;
use Employees\Config\DefaultParam;
use Employees\Models\Binding\Document\DocumentBindingModel;
use Employees\Models\DB\Document;
use Employees\Models\DB\Email;
use Employees\Models\DB\Employee;

class DocumentService implements DocumentServiceInterface
{
//    const url = DefaultParam::ServerRoot."newsletterdownload/";
    const url = DefaultParam::ServerRoot."webroot/documents/newsletters/";

    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    private function lastInsert()
    {
        $query = "SELECT LAST_INSERT_ID()";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $res = $stmt->fetchColumn();

        return $res;
    }

    public function all()
    {

        // TODO: Implement all() method.
        $query = "SELECT id,
                         category_id AS categoryId,
                         added AS date,
                         title,
                         description,
                         file_type AS fileType,
                         file_size AS fileSize,
                         CONCAT('".self::url."',file_name) AS url 
                          FROM document_files";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;

    }

    public function findOne($documentId)
    {
        $query = "SELECT id,
                         category_id AS categoryId, 
                         added AS date,
                         title,
                         description,
                         file_type AS fileType,
                         file_size AS fileSize,
                         CONCAT('".self::url."',file_name) AS url,
                         file_name AS name 
                          FROM document_files WHERE id = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$documentId]);

        $result = $stmt->fetch();

        return $result;
    }

    /**
     * @param \Employees\Models\Binding\Document\DocumentBindingModel $documentBindingModel
     */
    public function add($documentBindingModel)
    {
        $this->db->beginTransaction();
        try {
            $query = "INSERT INTO document_files (
                                             category_id,
                                             file_name,
                                             added,
                                             title,
                                             description,
                                             file_type,
                                             file_size
                                             )
                                               VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);


            foreach ($documentBindingModel->getFiles() as $file) {
                $result = $stmt->execute([$documentBindingModel->getCategoryId(),
                    $file["name"],
                    $documentBindingModel->getDate(),
                    $documentBindingModel->getTitle(),
                    $documentBindingModel->getDescription(),
                    $file["type"],
                    $file["size"]
                    ]);
            }
            $lastId = $this->db->lastInsertId();

            $this->db->commit();

            return $lastId;
        } catch (\Exception $e) {

            $this->db->rollBack();

            throw $e;

        }

    }

    public function remove($documentId)
    {
        // TODO: Implement remove() method.

        $query = "DELETE FROM document_files WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([$documentId]);
    }

    public function getEmailBodyForNewsletter()
    {
        $query = "SELECT 
                  email_types.subject, 
                  email_types.body, 
                  email_types.alt_body AS altBody, 
                  email_types.signature 
                  FROM email_contents 
                  INNER JOIN email_types 
                  ON email_contents.id = email_types.email_content_id 
                  WHERE email_contents.name = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute(["New newsletter creation"]);

        $result = $stmt->fetchObject(Email::class);

        return $result;
    }

}