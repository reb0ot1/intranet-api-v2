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
use Employees\Config\DefaultParam;
use Employees\Models\DB\Employee;


class EmployeesService implements EmployeesServiceInterface
{

    private $db;
    const url = DefaultParam::ServerRoot.DefaultParam::EmployeeContainer;

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
                  sub_company_id AS company,
                  position_id AS position,
                  team_id AS team,
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
                  employees.sub_company_id as company,
                  employees.position_id AS position,
                  employees.team_id AS team,
                  employees.start_date AS dateStart,
                  employees.birthday,
                  employees.image,
                  employees.avatar, 
                  employees.photo, 
                  employees.education,
                  employees.expertise,
                  employees.skills,
                  employees.languages,
                  employees.hobbies,
                  employees.pet,
                  employees.song,
                  employees.thought,
                  employees.book,
                  employees.skype,
                  employees.book,
                  employees.email 
                  FROM employees 
                  WHERE employees.active = ?";

        $valuesArr = [$active];

        if ($id !== null) {
            $query .=" AND employees.id = ?";
            array_push($valuesArr, $id);
        }

        $stmt = $this->db->prepare($query);

        $status = $stmt->execute($valuesArr);

        return $stmt->fetchAll();

    }

    public function getEmp($id) {
        $query = "SELECT 
                  employees.id,
                  employees.ext_id AS extId,
                  employees.first_name AS firstName,
                  employees.last_name AS lastName,
                  employees.gender,
                  employees.sub_company_id AS company,
                  employees.position_id AS position,
                  employees.team_id AS team,
                  employees.start_date AS dateStart,
                  employees.birthday,
                  CONCAT('".self::url."',employees.image) AS image,
                  CONCAT('".self::url."',employees.avatar) AS avatar, 
                  CONCAT('".self::url."',employees.photo) AS photo, 
                  employees.education,
                  employees.expertise,
                  employees.skills,
                  employees.languages,
                  employees.hobbies,
                  employees.pet,
                  employees.song,
                  employees.thought,
                  employees.book,
                  employees.skype,
                  employees.book,
                  employees.email 
                  FROM employees 
                  WHERE employees.id = ?";

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
                  employees.sub_company_id AS company,
                  employees.position_id AS position,
                  employees.team_id AS team,
                  employees.start_date AS dateStart,
                  employees.birthday,
                  employees.image,
                  employees.avatar, 
                  employees.photo, 
                  employees.education,
                  employees.expertise,
                  employees.skills,
                  employees.languages,
                  employees.hobbies,
                  employees.pet,
                  employees.song,
                  employees.thought,
                  employees.book,
                  employees.skype,
                  employees.book,
                  employees.email 
                  FROM employees 
                  WHERE employees.unique_str_code = ? AND employees.active = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$strId,"yes"]);
        $result = $stmt->fetch();

        return $result;
    }

    public function addEmp(EmpBindingModel $model)
    {

            $query = "INSERT INTO employees (
                  first_name,
                  last_name,
                  gender,
                  sub_company_id,
                  position_id,
                  team_id,
                  start_date,
                  birthday,
                  image,
                  avatar,
                  photo, 
                  active,
                  education,
                  expertise,
                  skills,
                  languages,
                  hobbies,
                  pet,
                  song,
                  thought,
                  book,
                  skype,
                  email) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([
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
            ]);

            return $this->db->lastInsertId();

    }

//    public function updEmp(EmpBindingModel $model)
    public function updEmp(EmpBindingModel $empBindingModel)
    {

        $query = "UPDATE employees SET 
                          first_name = ?,
                          last_name = ?,
                          gender = ?,
                          sub_company_id = ?,
                          position_id = ?,
                          team_id = ?,
                          start_date = ?,
                          birthday = ?,
                          image = ?,
                          photo = ?,
                          avatar = ?,
                          active = ?,
                          education = ?,
                          expertise = ?,
                          skills = ?,
                          languages = ?,
                          hobbies = ?,
                          pet = ?,
                          song = ?,
                          thought = ?,
                          book = ?,
                          skype = ?,
                          email = ? 
                          WHERE id = ?
                          ";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $empBindingModel->getFirstName(),
            $empBindingModel->getLastName(),
            $empBindingModel->getGender(),
            $empBindingModel->getCompany(),
            $empBindingModel->getPosition(),
            $empBindingModel->getTeam(),
            $empBindingModel->getDateStart(),
            $empBindingModel->getBirthday(),
            $empBindingModel->getImage(),
            $empBindingModel->getPhoto(),
            $empBindingModel->getAvatar(),
            $empBindingModel->getActive(),
            $empBindingModel->getEducation(),
            $empBindingModel->getExpertise(),
            $empBindingModel->getSkills(),
            $empBindingModel->getLanguages(),
            $empBindingModel->getHobbies(),
            $empBindingModel->getPet(),
            $empBindingModel->getSong(),
            $empBindingModel->getThought(),
            $empBindingModel->getBook(),
            $empBindingModel->getSkype(),
            $empBindingModel->getEmail(),
            $empBindingModel->getId()
        ]);

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


}