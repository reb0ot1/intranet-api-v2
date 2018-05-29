<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 18/08/2017
 * Time: 15:57
 */

namespace Employees\Services;


use Employees\Adapter\DatabaseInterface;
use Employees\Models\Binding\Emp\EmpBindingModel;
use Employees\Models\DB\Employee;


class EmployeesService implements EmployeesServiceInterface
{

    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function getList()
    {
        $query = "SELECT 
                  id, 
                  ext_id AS extId,
                  first_name AS firstName,
                  last_name AS lastName,
                  gender,
                  company,
                  position,
                  team,
                  start_date AS dateStart,
                  birthday,
                  image 
                  FROM employees";

        $stmt = $this->db->prepare($query);

        $result = $stmt->fetchAll();

        return $result;
    }


    public function getListStatus($active, $id=null)
    {
        $query = "SELECT 
                  employees.id,
                  employees.ext_id AS extId,
                  employees.first_name AS firstName,
                  employees.last_name AS lastName,
                  employees.gender,
                  employees.company,
                  employees.position,
                  employees.team,
                  employees.start_date AS dateStart,
                  employees.birthday,
                  employees.image,
                  employees.avatar, 
                  employees.photo, 
                  employees_add_info.education,
                  employees_add_info.expertise,
                  employees_add_info.skills,
                  employees_add_info.languages,
                  employees_add_info.hobbies,
                  employees_add_info.pet,
                  employees_add_info.song,
                  employees_add_info.thought,
                  employees_add_info.book,
                  employees_add_info.skype,
                  employees_add_info.book,
                  employees_add_info.email 
                  FROM employees 
                  INNER JOIN employees_add_info 
                  WHERE employees.unique_str_code = employees_add_info.unique_str_code AND employees.active = ?";

        $valuesArr = [$active];

        if ($id !== null) {
            $query .=" AND employees.id = ?";
            array_push($valuesArr, $id);
        }

        $stmt = $this->db->prepare($query);

        $status = $stmt->execute(["yes"]);

        return $stmt->fetchAll();

    }

    public function getEmp($id) {
        $query = "SELECT 
                  employees.id,
                  employees.ext_id AS extId,
                  employees.first_name AS firstName,
                  employees.last_name AS lastName,
                  employees.gender,
                  employees.company,
                  employees.position,
                  employees.team,
                  employees.start_date AS dateStart,
                  employees.birthday,
                  employees.image,
                  employees.avatar,
                  employees.photo,
                  employees_add_info.education,
                  employees_add_info.expertise,
                  employees_add_info.skills,
                  employees_add_info.languages,
                  employees_add_info.hobbies,
                  employees_add_info.pet,
                  employees_add_info.song,
                  employees_add_info.thought,
                  employees_add_info.book,
                  employees_add_info.skype,
                  employees_add_info.book,
                  employees_add_info.email 
                  FROM employees 
                  INNER JOIN employees_add_info 
                  WHERE employees.unique_str_code = employees_add_info.unique_str_code AND employees.id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $result = $stmt->fetch();

        return $result;
    }



    public function getEmpByStrId($strId) {
        $query = "SELECT 
                  employees.id,
                  employees.ext_id AS extId,
                  employees.first_name AS firstName,
                  employees.last_name AS lastName,
                  employees.gender,
                  employees.company,
                  employees.position,
                  employees.team,
                  employees.start_date AS dateStart,
                  employees.birthday,
                  employees.image,
                  employees.avatar, 
                  employees.photo, 
                  employees_add_info.education,
                  employees_add_info.expertise,
                  employees_add_info.skills,
                  employees_add_info.hobbies,
                  employees_add_info.pet,
                  employees_add_info.song,
                  employees_add_info.thought,
                  employees_add_info.book,
                  employees_add_info.skype,
                  employees_add_info.book,
                  employees_add_info.email 
                  FROM employees 
                  INNER JOIN employees_add_info 
                  WHERE employees.unique_str_code = employees_add_info.unique_str_code AND employees.unique_str_code = ? AND employees.active = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$strId,"yes"]);
        $result = $stmt->fetch();

        return $result;
    }

    public function addEmp(EmpBindingModel $model, $uniqueStrId)
    {


        $query = "INSERT INTO employees (
                  first_name,
                  last_name,
                  gender,
                  company,
                  position,
                  team,
                  start_date,
                  birthday,
                  image,
                  avatar,
                  photo, 
                  active,
                  unique_str_code) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?); 
                  INSERT INTO employees_add_info (education,expertise,skills,languages,hobbies,pet,song,thought,book,skype,email,unique_str_code) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);";
        
        $stmt = $this->db->prepare($query);
        $result =  $stmt->execute([
            $model->getFirstName(),
            $model->getLastName(),
            $model->getGender(),
            $model->getCompany(),
            $model->getPosition(),
            $model->getTeam(),
            $model->getDateStart(),
            $model->getBirthday(),
            $model->getImage(),
            $model->getAvatar(),
            $model->getPhoto(),
            $model->getActive(),
            $uniqueStrId,
            $model->getEducation(),
            $model->getExpertise(),
            $model->getSkills(),
            $model->getLanguages(),
            $model->getHobbies(),
            $model->getPet(),
            $model->getSong(),
            $model->getThought(),
            $model->getBook(),
            $model->getSkype(),
            $model->getEmail(),
            $uniqueStrId
        ]);

        return $result;
    }

//    public function updEmp(EmpBindingModel $model)
    public function updEmp(EmpBindingModel $empBindingModel)
    {

        $updatePropArray = [
            "first_name"=>$empBindingModel->getFirstName(),
            "last_name"=>$empBindingModel->getLastName(),
            "gender"=>$empBindingModel->getGender(),
            "company"=>$empBindingModel->getCompany(),
            "position"=>$empBindingModel->getPosition(),
            "team"=>$empBindingModel->getTeam(),
            "start_date"=>$empBindingModel->getDateStart(),
            "birthday"=>$empBindingModel->getBirthday(),
            "image" => $empBindingModel->getImage(),
            "photo" => $empBindingModel->getPhoto(),
            "avatar" => $empBindingModel->getAvatar(),
            "active"=>$empBindingModel->getActive()
        ];

        $updateAddInfo = [
            "education" => $empBindingModel->getEducation(),
            "expertise" => $empBindingModel->getExpertise(),
            "skills" => $empBindingModel->getSkills(),
            "languages" => $empBindingModel->getLanguages(),
            "hobbies" => $empBindingModel->getHobbies(),
            "pet" => $empBindingModel->getPet(),
            "song" => $empBindingModel->getSong(),
            "thought" => $empBindingModel->getThought(),
            "book" => $empBindingModel->getBook(),
            "skype" => $empBindingModel->getSkype(),
            "email" => $empBindingModel->getEmail()
        ];


        $createQuery = new CreatingQueryService();
        $createQuery->setValues($updatePropArray);
        $createQuery->setQueryUpdateEmp($empBindingModel->getId(), "id = ?");

        $createQuery2 = new CreatingQueryService();
        $createQuery2->setValues($updateAddInfo);
        $createQuery2->setQueryUpdateEmp($empBindingModel->getId(), "emp_id = ?");

        $query = "UPDATE employees SET ".$createQuery->getQuery();
        $query2 = "UPDATE employees_add_info SET ".$createQuery2->getQuery();



        $stmt = $this->db->prepare($query.";".$query2.";");

        return $stmt->execute(array_merge($createQuery->getValues(),$createQuery2->getValues()));

    }

    public function removeEmp($empId) : bool {

        $query = "UPDATE 
                  employees 
                  SET 
                  active = ?  
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute(["no",$empId]);
    }

    public function updateAddInfoId($key, $empId)
    {
        $query = "UPDATE 
                  employees_add_info
                  SET 
                  emp_id = ?  
                  WHERE unique_str_code = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([$empId, $key]);
    }


}