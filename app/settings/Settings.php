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
            public $routes1 = array
            (
                // Главная страница сайта (http://localhost/)
                array(
                    // паттерн в формате Perl-совместимого реулярного выражения
                    'pattern' => '~^/$~',
                    // Имя класса обработчика 
                    'class' => 'RouteController',
                    // Имя метода класса обработчика
                    'method' => 'index'
                ),
            
                // Страница регистрации пользователя (http://localhost/registration.xhtml)
                array(
                    'pattern' => '~^/registration\.xhtml$~',
                    'class' => 'User',
                    'method' => 'registration',
                ),
            
                // Досье пользователя (http://localhost/userinfo/12345.xhtml)
                array(
                    'pattern' => '~^/userinfo/([0-9]+)\.xhtml$~',
                    'class' => 'User',
                    'method' => 'infoInfo',
                    // В aliases перечисляются имена переменных, которые должны быть в дальнейшем созданы 
                    // и заполнены значениями, взятыми на основании разбора URL адреса. 
                    // В данном случае в переменную user_id должен будет записаться числовой 
                    // идентификатор пользователя - 12345
                    'aliases' => array('user_id'),
                ),
            
                // Форум (http://localhost/forum/web-development/php/12345.xhtml)
                array(
                    'pattern' => '~^/forum(/[a-z0-9_/\-]+/)([0-9]+)\.xhtml$~',
                    'class' => 'Forum',
                    'method' => 'viewTopick',
                    // Будут созданы переменные:
                    // forum_url = '/web-development/php/'
                    // topic_id = 12345
                    'aliases' => array('forum_url', 'topic_id'),
                ),
            
                // и т.д.
            );

            
    static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}