<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;

class Home extends Controller {
    
    //These methods of Controller are called actions 
    public function index() {

        echo $this->viewer->render("shared/header.php", [
            "title" => "Home",
        ]);

        echo $this->viewer->render("Home/index.php");
    }
}