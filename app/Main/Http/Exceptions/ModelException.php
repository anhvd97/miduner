<?php

namespace App\Main\Http\Exceptions;

use \Exception;
use App\Main\Registry;
use App\Main\Http\Request;

class ModelException extends Exception
{
    private $exception;

    public function __construct($message, $code = null)
    {
        set_exception_handler([$this, 'render']);
        $this->root = Registry::getInstance()->config['appurl'];
        parent::__construct($message, $code);
        $this->report();
    }

    public function render($exception, Request $request = null)
    {
        $this->exception = $exception;
        $header = getallheaders();
        if ($header['Accept'] == 'application/json') {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], $exception->getCode());
        }
        $layoutsException = file_get_contents($this->root . '/resources/views/Exception.php');
        $title = 'Model Exception';
        ob_start();
        echo '<hr>';
        echo "<h1 style='color:#b0413e;font-weight:bold'>{$exception->getMessage()}</h1>";
        echo "<h2>Error from file {$exception->getFile()} <br> in line {$exception->getLine()}</h2>";
        echo "<hr>";
        foreach ($exception->getTrace() as $trace) {
            $file = isset($trace['file']) ? $trace['file'] : '';
            $line = isset($trace['line']) ? $trace['line'] : '';
            $class = isset($trace['class']) ? $trace['class'] : '';
            $function = isset($trace['function']) ? $trace['function'] : '';

            if ($file !== '') {
                echo "<h5>File <strong>{$file}</strong> got error from class {$class} in function {$function}() <strong>line {$line}</strong></h5>";
                echo "<hr>";
            }
        }
        $content = ob_get_clean();
        $layoutsException = preg_replace('/\[exception\]/', $content, $layoutsException);
        $layoutsException = preg_replace('/\[title\]/', $title, $layoutsException);
        eval(' ?>' . $layoutsException);
    }

    public function report()
    {
        return true;
    }
}
