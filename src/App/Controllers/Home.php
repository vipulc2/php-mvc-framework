<?php

namespace App\Controllers;

class Home {
    
    //These methods of Controller are called actions 
    public function index() {

        require "views/home_index.php";
    }
}