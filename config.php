<?php

defined('VG_ACCESS') or die;

const DB_NAME = 'shop';
const DB_LOGIN = 'root';
CONST DB_PASS = 'root';

const USER_VIEWS = 'app/views/user/';
const CONTROLLERS = 'app\\controller\\';

const HEADER = 'components/header.php';
const FOOTER_JS = 'components/footer.php';
const META_LINKS = 'components/meta+links.php';

$ASSETS = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $ASSETS . '/app/assets/';

define('ASSETS', $ASSETS);
const JS = 'components/js.php';

const EMAIL_LEN = 14;
const REVIEWS_ON_PAGE = 5;