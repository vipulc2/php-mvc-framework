<?php

namespace App\Controllers;

use Framework\Viewer;

class Home {
    
    //These methods of Controller are called actions 
    public function index() {

        $viewer = new Viewer;

        echo $viewer->render("shared/header.php", [
            "title" => "Home",
        ]);

        echo $viewer->render("Home/index.php");
    }
}