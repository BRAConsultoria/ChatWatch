<?php
namespace ChatWatch;

require_once __DIR__. '/vendor/autoload.php';
use App\Core\Controller;

\define('DEFAULT_NAMESPACE', 'App');
\define('DIR_ROOT', __DIR__ . \DIRECTORY_SEPARATOR);
\define('STORAGE_ROOT', \DIR_ROOT . \DIRECTORY_SEPARATOR . 'storage' . \DIRECTORY_SEPARATOR);
\define('APP_ROOT', \DIR_ROOT . \DIRECTORY_SEPARATOR . 'src' . \DIRECTORY_SEPARATOR);


\define('DEBUG_MODE', true);
\define('LOG_FILE', true);
\define('LOG_FILE_REQUIRED', true);

function sisError($errno, $errstr, $errfile, $errline)
{
    throw new \Exception(\sprintf("%s: %s in %s on line %s", getErrorType($errno), $errstr, $errfile, $errline));
}

function getErrorType($errno)
{
    $erros = [1 => "Error",
        2 => "Warning",
        4 => "Parse error",
        8 => "Notice",
        16 => "Core error",
        32 => "Core warning",
        64 => "Compile error",
        128 => "Compile warning",
        256 => "User error",
        512 => "User warning",
        1024 => "User notice",
        6143 => "Undefined erro",
        2048 => "Strict error",
        4096 => "Recoverable error"
    ];

    return($erros[$errno] ? : $erros[6143]);
}

\set_error_handler(__NAMESPACE__ . "\\sisError", \E_WARNING | \E_NOTICE);

\header("Content-type: application/json; charset=utf-8");
print (new Controller())->run(\filter_input(\INPUT_GET, 'q'));
