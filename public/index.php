<?php
use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
	header('Access-Control-Allow-Origin: http://localhost:8080');
//	header('Access-Control-Allow-Origin: *');
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
