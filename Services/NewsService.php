<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 26.11.2017 г.
 * Time: 12:46
 */

namespace Employees\Services;

use Employees\Adapter\DatabaseInterface;
use Employees\Models\Binding\News\NewsBindingModel;



class NewsService implements NewsServiceInterface
{
    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }


    public function getAllNews($isActive)
    {
        $query = "SELECT 
                  id,
                  title,
                  date,
                  author,
                  body,
                  image 
                  FROM news WHERE active = ? 
                  ORDER BY date DESC";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$isActive]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public function getNews($id)
    {
        $query = "SELECT
                  id,
                  title,
                  date,
                  author,
                  body 
                  FROM news
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$id]);

        $result = $stmt->fetch();

        return $result;
    }

    public function getNewsByStrId($uniqueStr) : array {
        $query = "SELECT 
                  id,
                  title,
                  date,
                  author,
                  body,
                  image 
                  FROM news WHERE unique_str_code = ? AND active = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$uniqueStr, "yes"]);
        $result = $stmt->fetch();

        return $result;
    }

    public function addNews(NewsBindingModel $newsBindingModel, $uniqueStr) : bool
    {

        $query = "INSERT INTO 
                  news (
                  admin_id,
                  active,
                  date,
                  author,
                  title,
                  body,
                  unique_str_code
                  ) 
                  VALUES (?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $newsBindingModel->getAdminId(),
            "yes",
            $newsBindingModel->getDate(),
            $newsBindingModel->getAuthor(),
            $newsBindingModel->getTitle(),
            $newsBindingModel->getBody(),
//            $newsBindingModel->getBody(),
            $uniqueStr
        ]);
    }

    public function updateNews(NewsBindingModel $bindingModel) : bool
    {
        $arr = [
            "author"=>$bindingModel->getAuthor(),
            "title"=>$bindingModel->getTitle(),
            "date"=>$bindingModel->getDate(),
            "body"=>$bindingModel->getBody()
        ];

        $createQuery = new CreatingQueryService();
        $createQuery->setValues($arr);
        $createQuery->setQueryUpdateEmp($bindingModel->getId(), "id = ?");

        $query = "UPDATE news SET ".$createQuery->getQuery();

        $stmt = $this->db->prepare($query);

        return $stmt->execute($createQuery->getValues());
    }

    public function removeNews($id)
    {
        $query = "UPDATE 
                  news 
                  SET 
                  active = ?  
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute(["no",$id]);
    }


}