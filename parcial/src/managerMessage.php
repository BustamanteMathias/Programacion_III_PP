<?php

class Message
{
    public static function Success($DATA)
    {
        $objAux = array('STATUS: ' => 'SUCCESS', 'DATA-INFO: ' => $DATA);
        echo(json_encode($objAux));
    }

    public static function Warning($DATA)
    {
        $objAux = array('STATUS: ' => 'WARNING', 'DATA-INFO: ' => $DATA);
        echo(json_encode($objAux));
    }

    public static function Error($DATA)
    {
        $objAux = array('STATUS: ' => 'FATAL ERROR', 'DATA-INFO: ' => $DATA);
        echo(json_encode($objAux));
    }

    public static function Custom($DATA, $Msg)
    {
        $objAux = array('STATUS: ' => $Msg, 'DATA-INFO: ' => $DATA);
        echo(json_encode($objAux));
    }
}