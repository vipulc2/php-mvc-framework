<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;

use Framework\Viewer;

class Products {

    public function __construct(private Viewer $viewer, private Product $model) {

    } 

    //These methods of Controller are called actions 
    public function index() {

        $products = $this->model->getData();

        echo $this->viewer->render("shared/header.php", [
            "title" => "Products",
        ]);

        echo $this->viewer->render("Products/index.php", ["products" => $products]);
    }

    public function show(string $id) {

        echo $this->viewer->render("shared/header.php", [
            "title", "Product",
        ]);

        echo $this->viewer->render("Products/show.php", [
            "id" => $id
        ]);
    }

    // This is a action method which shows a certain page depending upon the values being shared and it has a unique router created just for this with the names also matching the parameters title, id and page.
    public function showPage(string $title, string $id, string $page) {

        echo $title, $id, $page;

    }
}