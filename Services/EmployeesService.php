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
use Employees\Models\DB\Email;
use Employees\Models\DB\Employee;
use Employees\Vendor\phpmailer\src\Exception;


class EmployeesService implements EmployeesServiceInterface
{

    private $db;
    const url = DefaultParam::ServerRoot.DefaultParam::EmployeeContainer;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function getListStatus($active, $id=null)
    {
        $query = "SELECT 
                  emp.id,
                  emp.ext_id AS extId,
                  emp.first_name AS firstName,
                  emp.last_name AS lastName,
                  emp.gender,
                  emp.sub_company_id as company,
                  emp.position_id AS position,
                  emp.team_id AS team,
                  emp.start_date AS dateStart,
                  emp.birthday,
                  CONCAT('".self::url."',emp.image) AS image,
                  CONCAT('".self::url."',emp.avatar) AS avatar, 
                  CONCAT('".self::url."',emp.photo) AS photo, 
                  emp.education,
                  emp.expertise,
                  emp.skills,
                  emp.languages,
                  emp.hobbies,
                  emp.pet,
                  emp.song,
                  emp.thought,
                  emp.book,
                  emp.skype,
                  emp.book,
                  emp.email, 
                  GROUP_CONCAT(edu.id) AS educationGroups, 
                  GROUP_CONCAT(hob.id) AS hobbyGroups 
                  FROM employees AS emp
                  LEFT JOIN employees_hobbies AS hobgr ON emp.id = hobgr.employee_id 
                  LEFT JOIN hobby_groups AS hob ON hobgr.hobby_id = hob.id 
                  LEFT JOIN employees_educations AS ee ON emp.id = ee.employee_id 
                  LEFT JOIN education_groups AS edu ON ee.education_id = edu.id 
						WHERE emp.active = ?";

        $valuesArr = [$active];

        if ($id !== null) {
            $query .=" AND emp.id = ? GROUP BY emp.id";
            array_push($valuesArr, $id);
        } else {
            $query .=" GROUP BY emp.id";
        }

        $stmt = $this->db->prepare($query);

        $stmt->execute($valuesArr);

        while($result = $stmt->fetchObject(Employee::class)) {
            yield $result;
        }

    }

    public function getEmp($id) {
        $query = "SELECT 
                  emp.id,
                  emp.ext_id AS extId,
                  emp.first_name AS firstName,
                  emp.last_name AS lastName,
                  emp.gender,
                  emp.sub_company_id as company,
                  emp.position_id AS position,
                  emp.team_id AS team,
                  emp.start_date AS dateStart,
                  emp.birthday,
                  CONCAT('".self::url."',emp.image) AS image,
                  CONCAT('".self::url."',emp.avatar) AS avatar, 
                  CONCAT('".self::url."',emp.photo) AS photo, 
                  emp.education,
                  emp.expertise,
                  emp.skills,
                  emp.languages,
                  emp.hobbies,
                  emp.pet,
                  emp.song,
                  emp.thought,
                  emp.book,
                  emp.skype,
                  emp.book,
                  emp.email, 
                  GROUP_CONCAT(edu.id) AS educationGroups, 
                  GROUP_CONCAT(hob.id) AS hobbyGroups 
                  FROM employees AS emp
                  LEFT JOIN employees_hobbies AS hobgr ON emp.id = hobgr.employee_id 
                  LEFT JOIN hobby_groups AS hob ON hobgr.hobby_id = hob.id 
                  LEFT JOIN employees_educations AS ee ON emp.id = ee.employee_id 
                  LEFT JOIN education_groups AS edu ON ee.education_id = edu.id 
                  WHERE emp.id = ? GROUP BY emp.id";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $result = $stmt->fetchObject(Employee::class);

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

    public function removeEmp($empId) : bool
    {

        $query = "UPDATE 
                  employees 
                  SET 
                  active = ?  
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute(["no",$empId]);
    }

    public function getEmailBodyForEmployeeCreation()
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

        $stmt->execute(["New employee registration"]);

        $result = $stmt->fetchObject(Email::class);

        return $result;
    }

    public function addEmployeeEducationGroups($employeeId, $groups)
    {
        $this->db->beginTransaction();

        try {
            $query = "INSERT INTO
                          employees_educations (employee_id, education_id) 
                          VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            foreach ($groups as $group) {
                  $stmt->execute([$employeeId, $group]);
            }

            $this->db->commit();
            return true;

        } catch (\PDOException  $e) {
            $this->db->rollBack();
            throw $e;
        }

    }

    public function updateEmployeeEducationGroups($employeeId, $groups)
    {
        $this->db->beginTransaction();
        $placeHolders = $this->createPlaceholders(count($groups));
        $values = $groups;
        array_push($values, $employeeId);

        try {
            $queryUpdate = "INSERT IGNORE INTO
                          employees_educations (employee_id, education_id) 
                          VALUES (?, ?)";

            $queryRemove = "DELETE FROM employees_educations WHERE education_id NOT IN ($placeHolders) AND employee_id = ?";
            $stmtInsert = $this->db->prepare($queryUpdate);
            $stmtRemove = $this->db->prepare($queryRemove);
            foreach ($groups as $group) {
                $stmtInsert->execute([$employeeId, $group]);
            }

            $stmtRemove->execute($values);

            $this->db->commit();
            return true;

        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function addEmployeeHobbyGroups($employeeId, $groups)
    {
        $this->db->beginTransaction();

        try {
            $query = "INSERT INTO
                          employees_hobbies (employee_id, hobby_id)
                          VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            foreach ($groups as $group) {
                $stmt->execute([$employeeId, $group]);
            }

            $this->db->commit();
            return true;

        } catch (\PDOException  $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateEmployeeHobbyGroups($employeeId, $groups)
    {
        $this->db->beginTransaction();
        $placeHolders = $this->createPlaceholders(count($groups));
        $values = $groups;
        array_push($values, $employeeId);

        try {
            $queryUpdate = "INSERT IGNORE INTO
                          employees_hobbies (employee_id, hobby_id) 
                          VALUES (?, ?)";

            $queryRemove = "DELETE FROM employees_hobbies WHERE hobby_id NOT IN ($placeHolders) AND employee_id = ?";
            $stmtInsert = $this->db->prepare($queryUpdate);
            $stmtRemove = $this->db->prepare($queryRemove);
            foreach ($groups as $group) {
                $stmtInsert->execute([$employeeId, $group]);
            }

            $stmtRemove->execute($values);

            $this->db->commit();
            return true;

        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }


    private function createPlaceholders($numberOfPlaceholders)
    {
        $placeHolders = implode(', ', array_fill(0, $numberOfPlaceholders, '?'));

        return $placeHolders;
    }

}