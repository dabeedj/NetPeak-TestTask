<?php
return [
    // Product
    'product/([0-9]+)/?([0-9]*)' => 'product/view/$1/$2', // actionView in ProductController
    // Product create
    'product/create' => 'product/create', //actionCreate in ProductController
    // Main Page
    'sort/([a-z_]+)/?$' => 'main/index/$1', // actionIndex in MainController with sort column
    'sort/([a-z_]+)/?(asc|desc|)$' => 'main/index/$1/$2', // actionIndex in MainController with sort column and direction
    'index.php' => 'main/index', // actionIndex in MainController
    '' => 'main/index', // actionIndex in MainController
];
