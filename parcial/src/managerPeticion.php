<?php 

class Peticion
{
    public static function GetPath_Info($SERVER_GLOBAL)
    {
        return $SERVER_GLOBAL['PATH_INFO'] ?? '';
    }
    
    public static function GetRequest_Method($SERVER_GLOBAL)
    {
        return $SERVER_GLOBAL['REQUEST_METHOD'] ?? '';
    }

    public static function PostFileValide($FILE_GLOBAL, $NAME)
    {
         return isset($FILE_GLOBAL[$NAME]) ? $FILE_GLOBAL : null;
    }

    public static function GetHeader($KEY)
    {
        $headers = getallheaders();

        if($headers != false)
        {
            if(isset($headers[$KEY]) && !empty($headers[$KEY]))
            {
                return $headers[$KEY];
            }
        }
        return null;
    }

    public static function Validate($KEY)
    {
        $method = Peticion::GetRequest_Method($_SERVER);

        if ($method == 'POST') 
        {
            if(isset($_POST[$KEY]) && !empty($_POST[$KEY]))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else if($method == 'GET')
        {
            if(isset($_GET[$KEY]) && !empty($_GET[$KEY]))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public static function POST($KEY)
    {
        $method = Peticion::GetRequest_Method($_SERVER);

        if ($method == 'POST') 
        {
            if(isset($_POST[$KEY]) && !empty($_POST[$KEY]))
            {
                return $_POST[$KEY];
            }
            else
            {
                return false;
            }
        }
    }

    public static function GET($KEY)
    {
        $method = Peticion::GetRequest_Method($_SERVER);

        if ($method == 'GET') 
        {
            if(isset($_GET[$KEY]) && !empty($_GET[$KEY]))
            {
                return $_GET[$KEY];
            }
            else
            {
                return false;
            }
        }
    }

    public static function GetDate()
    {
        $date   = getdate();
        $d      = $date['mday'];
        $m      = $date['mon'];
        $y      = $date['year'];
        $min    = $date['minutes'];
        $hours  = $date['hours'];

        return $d.'/'.$m.'/'.'/'.$y.' '.$hours.':'.$min;
    }
}