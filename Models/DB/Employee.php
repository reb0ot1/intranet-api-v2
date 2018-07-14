<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 7.1.2018 г.
 * Time: 16:27
 */

namespace Employees\Models\DB;


class Employee implements \JsonSerializable
{
    private $id;
    private $firstName;
    private $lastName;
    private $gender;
    private $company;
    private $position;
    private $team;
    private $dateStart;
    private $birthday;
    private $image;
    private $avatar;
    private $photo;
    private $education;
    private $educationGroups;
    private $expertise;
    private $skills;
    private $languages;
    private $hobbies;
    private $hobbyGroups;
    private $pet;
    private $song;
    private $thought;
    private $book;
    private $skype;
    private $email;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param mixed $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return mixed
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param mixed $dateStart
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * @param mixed $education
     */
    public function setEducation($education)
    {
        $this->education = $education;
    }

    /**
     * @return mixed
     */
    public function getEducationGroups()
    {
        $groupCodes = [];
        foreach (explode(",",$this->educationGroups) as $group)
        {
            if (!in_array($group, $groupCodes)) {
                $groupCodes[] = $group;
            }
        }
        return $groupCodes;
    }

    /**
     * @param mixed $educationGroups
     */
    public function setEducationGroups($educationGroups)
    {
        $this->educationGroups = $educationGroups;
    }


    /**
     * @return mixed
     */
    public function getExpertise()
    {
        return $this->expertise;
    }

    /**
     * @param mixed $expertise
     */
    public function setExpertise($expertise)
    {
        $this->expertise = $expertise;
    }

    /**
     * @return mixed
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param mixed $skills
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;
    }

    /**
     * @return mixed
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param mixed $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * @return mixed
     */
    public function getHobbies()
    {
        return $this->hobbies;
    }

    /**
     * @param mixed $hobbies
     */
    public function setHobbies($hobbies)
    {
        $this->hobbies = $hobbies;
    }

    /**
     * @return mixed
     */
    public function getHobbyGroups()
    {
        $groupCodes = [];
        foreach (explode(",",$this->hobbyGroups) as $group)
        {
            if (!in_array($group, $groupCodes)) {
                $groupCodes[] = $group;
            }
        }

        return $groupCodes;
    }

    /**
     * @param mixed $hobbyGroups
     */
    public function setHobbyGroups($hobbyGroups)
    {
        $this->hobbyGroups = $hobbyGroups;
    }

    /**
     * @return mixed
     */
    public function getPet()
    {
        return $this->pet;
    }

    /**
     * @param mixed $pet
     */
    public function setPet($pet)
    {
        $this->pet = $pet;
    }

    /**
     * @return mixed
     */
    public function getSong()
    {
        return $this->song;
    }

    /**
     * @param mixed $song
     */
    public function setSong($song)
    {
        $this->song = $song;
    }

    /**
     * @return mixed
     */
    public function getThought()
    {
        return $this->thought;
    }

    /**
     * @param mixed $thought
     */
    public function setThought($thought)
    {
        $this->thought = $thought;
    }

    /**
     * @return mixed
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param mixed $book
     */
    public function setBook($book)
    {
        $this->book = $book;
    }

    /**
     * @return mixed
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param mixed $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    function jsonSerialize()
    {
        $getter_names = get_class_methods(get_class($this));
        $gettable_attributes = array();
        foreach ($getter_names as $key => $value) {
            if(substr($value, 0, 3) === 'get') {
                $gettable_attributes[substr($value, 3, strlen($value))] = $this->$value();
            }
        }
        return $gettable_attributes;
    }



}