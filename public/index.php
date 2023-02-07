<?php
use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
	$corsUrl = $context['CORS_ALLOW_URL'] ?? 'localhost';
	header('Access-Control-Allow-Origin: '. $corsUrl);
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
