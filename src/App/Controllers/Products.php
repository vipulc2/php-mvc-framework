<?php

namespace App\Controllers;

use App\Models\Product;

use Framework\Viewer;

class Products {

    //These methods of Controller are called actions 
    public function index() {

        $model = new Product;
        $products = $model->getData();

        $viewer = new Viewer;

        echo $viewer->render("shared/header.php", [
            "title" => "Products",
        ]);

        echo $viewer->render("Products/index.php", ["products" => $products]);
    }

    public function show(string $id) {

        $viewer = new Viewer;

        echo $viewer->render("shared/header.php", [
            "title", "Product",
        ]);

        echo $viewer->render("Products/show.php", [
            "id" => $id
        ]);
    }

    // This is a action method which shows a certain page depending upon the values being shared and it has a unique router created just for this with the names also matching the parameters title, id and page.
    public function showPage(string $title, string $id, string $page) {

        echo $title, $id, $page;

    }
}