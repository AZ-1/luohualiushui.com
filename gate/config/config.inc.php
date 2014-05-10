<?php
$DOMAIN_NAME = "http://" . $_SERVER['SERVER_NAME'] . "/";

define('SERVER_NAME', $_SERVER['SERVER_NAME']);
define('BASE_URL', $DOMAIN_NAME);
define("OA_VIEW_SWITCH", 'ON');
define("TEMPLATE_PATH",  ROOT_PATH . '/views/');
define("DOMAIN_NAME" , $DOMAIN_NAME);
define("PAGE_TITLE" , "");

$meiUrl = 'http://mei.hitao.com/';

// static 
define('STATIC', ROOT_PATH . '/public/static');

// article pic
define('ARTICLE_PIC_URL', $meiUrl . 'data/article/pic');
define('ARTICLE_PIC_DIR', ROOT_PATH . '/public/data/article/pic');

// avatar
define('AVATAR_URL', BASE_URL . 'data/article/pic');
define('AVATAR_DIR', ROOT_PATH . '/public/data/article/pic');


// qq APPKEY
define('QZONE_ID', '100560477');
define('QZONE_KEY', '870acaef88497b6262a2e6a7fa92dcdb');

// sina weibo  APPKEY
define('WEIBO_AKEY', '2820726400');
define('WEIBO_SKEY', '8620d3efdf72f4f9076111fba4f41e2b');

// taobao
define('TAOBAO_APPKEY_AUTH', '21681440');
define('TAOBAO_APPSECRET_AUTH', '47098d6f7e915e7fc62cecbcd3fe3532');

// 打通  hitao.com 
define('HITAO_LOGIN_COOKIE', '_HN');
define('HITAO_LOGIN_COOKIE_IS', '_HN_IS');



define('DEFAULT_COOKIE_PATH', '/');
define('DEFAULT_COOKIE_DOMAIN', '.hitao.com');
define('DEFAULT_COOKIE_EXPIRE', 31536000); //一年
