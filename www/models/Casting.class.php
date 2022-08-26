<?php

class Casting{

    private int $_id;
    private string $_firstname;
    private string $_lastname;
    private bool $_sex;
    private string $_about;
    private DateTime $_birthdate;
    private int $_type;

    public function __construct($id, $firstname, $lastname, $sex, $about, $birthdate, $type){
        $this->_id = $id;
        $this->_firstname = $firstname;
        $this->_lastname = $lastname;
        $this->_sex = $sex == "0" ? false : true;
        $this->_about = $about;
        $this->_birthdate = new DateTime($birthdate);
        $this->_type = $type;
    }

    public function getId(){return $this->_id;}
    public function getFirstname(){return $this->_firstname;}
    public function getLastname(){return $this->_lastname;}
    public function getSex(){return $this->_sex;}
    public function getAbout(){return $this->_about;}
    public function getBirthdate(){return $this->_birthdate;}
    public function getType(){return $this->_type;}

}

?>