<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'tyla2');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'qxItSD$l24|aj~V#cNWp6g0}Cy{fu`3Tmuz.:S~e1JM[$?N}[T5MkxB#HP<GG1dm');
define('SECURE_AUTH_KEY',  'f|j*3,HM^C>p.gmM)Bg~7g#JmYTH#Er.mlqkT;`,:ropr/1Ul7-OS(WYktf|*(3w');
define('LOGGED_IN_KEY',    ',qH6r,NdH]0GLAa%PC0usTubLm2vX!ads8RT~P>.)R$lFdq1@:Y~WIO.B4x28qo!');
define('NONCE_KEY',        'L86kRkNyqnGX0ht#I)dK6KFo~W@c%HoeN4BZaiQO?Z/]rsAwm~KSnLd=A8(.^=Sr');
define('AUTH_SALT',        'YD}Dly-e }|{;J<F|tZQ/{GMH0%w,]_}H6GWawcQB~BlQp!lWc[<T-rv!eb;Afu+');
define('SECURE_AUTH_SALT', 'm#{w64:{,^{C/4XfDsp|V.)y%9jJ[-+N=6PLAQU/d*L_}P$w&^*9-b_:0K=-~uC?');
define('LOGGED_IN_SALT',   'YFBQQe`!C_ Nt$W,WW,B,Nsk7[c0<M5X;^YN:k(j;GMHi7rLLb8LvpDF[Q#|j10S');
define('NONCE_SALT',       'ZJr$f:_]]VEvd(xKxY5@=:8V&RZ|q7w$5*[?DR}=e%}.+A*f#|7&HGbc,6g*At$0');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
