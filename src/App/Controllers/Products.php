<?php

namespace App\Controllers;

use App\Models\Product;

class Products {

    //These methods of Controller are called actions 
    public function index() {

        $model = new Product;
        $products = $model->getData();

        require "views/products_index.php";
    }

    public function show(string $id) {

        var_dump($id);

        require "views/products_show.php";
    }

    // This is a action method which shows a certain page depending upon the values being shared and it has a unique router created just for this with the names also matching the parameters title, id and page.
    public function showPage(string $title, string $id, string $page) {

        echo $title, $id, $page;

    }
}