<?php
/**
* If for some reason we're running a version of PHP < 5.4 create this function
*/
if (!function_exists('http_response_code'))
{
    function http_response_code($code = NULL)
    {
        static $response_code = 200;
        if($code !== NULL)
        {
            header('X-PHP-Response-Code: '.$code, true, $code);
            if( !headers_sent() )
			{
                $response_code = $code;
			}
        }       
        return $response_code;
    }
}
?>