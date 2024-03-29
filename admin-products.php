<?php

use Hcode\PageAdmin;
use Hcode\Model\User;
use Hcode\Model\Product;

$app->get("/admin/products",function(){
    User::verifyLogin();

    $products = Product::listAll() ;

    $page = new PageAdmin();
    $page->setTpl("products", [
        "products"=>$products
    ]);
});

$app->get("/admin/products/create", function () {
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("products-create");
});


$app->post("/admin/products/create", function () {
    User::verifyLogin();

    $product = new Product();

    $product->setData($_POST);
    $product->save();
    header("Location: /admin/products");
    exit;
});


$app->get("/admin/products/:product", function ($idproduct) {
    User::verifyLogin();

    $product = new Product();
    $product->get($idproduct);

    $page = new PageAdmin();
    $page->setTpl("products-update", [
        "product" => $product->getValues()
    ]);
});

$app->get("/admin/products/:product", function ($idproduct) {
    User::verifyLogin();

    $product = new Product();
    $product->get((int)$idproduct);

    $page = new PageAdmin();
    $page->setTpl("products-update", [
        "product" => $product->getValues()
    ]);
});

$app->post("/admin/products/:product", function ($idproduct) {
    User::verifyLogin();

    $product = new Product();
    $product->get((int)$idproduct);

    $product->setData($_POST);
    $product->update();
    $product->setPhoto($_FILES["file"]);

    header("Location: /admin/products");
    exit;
});

$app->get("/admin/products/:product/delete", function ($idproduct) {
    User::verifyLogin();

    $product = new Product();
    $product->get((int)$idproduct);

    $product->setData($_POST);
    $product->delete();

    header("Location: /admin/products");
    exit;
});