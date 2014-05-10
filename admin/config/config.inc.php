<?php
$DOMAIN_NAME = 'http://' . $_SERVER['SERVER_NAME'] . '/';

define('SERVER_NAME', $_SERVER['SERVER_NAME']);
define('BASE_URL', $DOMAIN_NAME);
define('MEI_BASE_URL', str_replace('admin.', '', $DOMAIN_NAME));
define('OA_VIEW_SWITCH', 'ON');
define('TEMPLATE_PATH',  ROOT_PATH . '/views/');
define('DOMAIN_NAME' , $DOMAIN_NAME);
define('PAGE_TITLE' , '');
define('MEI_ROOT_PATH', str_replace('/admin', '/gate',ROOT_PATH));


//
define('AD_BANNER_PATH', ROOT_PATH . '/public/data/ad/banner');
define('AD_BANNER_URL', MEI_BASE_URL . 'data/ad/banner');

define('TOPIC_PATH', ROOT_PATH . '/public/data/topic');
define('TOPIC_URL', MEI_BASE_URL . 'data/topic');

// article pic
define('ARTICLE_PIC_DIR', ROOT_PATH . '/public/data/article/pic');
define('ARTICLE_PIC_URL', MEI_BASE_URL . 'data/article/pic');

define('DEFAULT_COOKIE_PATH', '/');
define('DEFAULT_COOKIE_DOMAIN', '.hitao.com');
define('DEFAULT_COOKIE_EXPIRE', 31536000); //一年
