<?php

class User{
    
    private $userId;
    private $name;
    private $phone;
    private $password;
    
    public function __construct($userId, $name, $phone, $password) {
        $this->userId   = $userId;
        $this->name     = $name;
        $this->phone    = $phone;
        $this->password = $password;
    }
    
    public function getUserId() {
        return $this->userId;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getPhone() {
        return $this->phone;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
}

