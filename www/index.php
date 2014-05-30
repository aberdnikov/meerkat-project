<?php
    #выберите глобальный режим (по-умолчанию режим production - ошибки выключены)
    #глобальный режим можно будет переопределить на уровне домена
    $_SERVER['KOHANA_ENV'] = 'DEVELOPMENT'; //(DEVELOPMENT/PRODUCTION)

    define('DOCROOT', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
    define('MODPATH', realpath(dirname(DOCROOT).'/modules/') . DIRECTORY_SEPARATOR);

    #раскомментируйте, если требуется хранить кэш и логи сайта в директориях доменов
    #define('TMP_IN_DOMAIN', true);

    require MODPATH.'meerkat-core/bootstrap.php';

    if (PHP_SAPI == 'cli') { // Try and load minion
        class_exists('Minion_Task') OR die('Please enable the Minion module for CLI support.');
        set_exception_handler(array('Minion_Exception', 'handler'));
        Minion_Task::factory(Minion_CLI::options())->execute();
    } else {
        /**
         * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
         * If no source is specified, the URI will be automatically detected.
         */
        $echo = Request::factory(TRUE, array(), FALSE)
            ->execute()
            ->send_headers(TRUE)
            ->body();
        echo $echo;
        #раскомментируйте, если хотите увидеть профайлер внизу страницы
        //echo View::factory('profiler/stats');
    } 