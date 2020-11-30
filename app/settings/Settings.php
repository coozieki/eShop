<?php

namespace app\settings;

use app\interfaces\Singletone;

class Settings extends Singletone {
    protected static $instance;

    public $routes = [
                'admin' => [
                    'controller' => 'core/admin/controller/RouteController',
                    'alias' => 'admin',
                    'routes' => [
                        'index' => 'assets/index.php'
                    ]
                ],
                'user' => [
                    'controller' => 'RouteController',
                    'routes' => [
                        'home' => 'index',
                        'signup' => 'register',
                        'dosignup' => 'AuthController#register',
                        'dosignin' => 'AuthController#login',
                        '' => 'index',
                        'signin' => 'login',
                        'logout' => 'AuthController#logout',
                        "load_shop_home_items" => 'LoadItemsController',
                        'load_menu' => 'HeaderController#loadMenu',
                        'brand' => 'the-brand',
                        'product_view' => 'ProductViewController',
                        'search' => 'HeaderController#search',
						'to_cart_check' => 'ToCartCheckController',
						'rev_page' => 'ProductViewController#changePage',
						'add_review' => 'ProductViewController#addReview'
                    ]
                ]
            ];
            
    static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
