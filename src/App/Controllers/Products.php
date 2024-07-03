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

    public function show() {

        require "views/products_show.php";
    }
}