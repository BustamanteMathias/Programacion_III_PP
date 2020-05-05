<?php
require_once dirname( __DIR__, 1) . '\src\managerArchivos.php';
require_once dirname( __DIR__, 1) . '\src\managerMessage.php';

class Profesor
{
    public $nombre, $legajo;

    public function __construct(){}

    public static function GetProfesorPost()
    {

        $Profesor                = new Profesor();
        $Profesor->nombre        = Peticion::POST('nombre');
        $Profesor->legajo        = Peticion::POST('legajo');
            
        return $Profesor;
    }

    private static function Find($path, $Profesor)
    {
        $lista = Archivo::getJSON($path);

        if($lista != null)
        {
            foreach ($lista as $value) 
            {
                if ($value->legajo == $Profesor->legajo) 
                {
                    return true;
                }
            }
        }
        return false;
    }

    private static function EndProfesor($path, $Profesor)
    {
        $lista = Archivo::getJSON($path);
        $existeLista = false;
        $existeProfesor = false;
        
        if($lista != null)
        {
            $existeLista = true;
            foreach ($lista as $value) 
            {
                if ($value->nombre == $Profesor->nombre && $value->cuatrimestre == $Profesor->cuatrimestre) 
                {
                    $existeProfesor = true;
                    break;
                }
            }
        }

        //SI EXISTE LA LISTA PERO NO EL Profesor -> genero un identificador
        if($existeLista && !$existeProfesor)
        {
            $Profesor->identificador = count($lista)+1;
        }

        return $Profesor;
    }

    public static function Save($path, $Profesor)
    {
        $rtn = false;

        $lista = Archivo::getJSON($path);
        //$ProfesorAux = Profesor::EndProfesor($path, $Profesor);

        if(!Profesor::Find($path, $Profesor))
        {
            array_push($lista, $Profesor);
            Archivo::toJSON($path, $lista);
            Message::Success($lista);
            return true;
        }
        else
        {
            Message::Error('PROFESOR REPETIDO');
            return false;
        }
        
        return $rtn;
    }

    public static function GetProfesorID($path, $id)
    {
        $lista = Archivo::getJSON($path);

        if($lista != null)
        {
            foreach ($lista as $value) 
            {
                if ($value->identificador == $id) 
                {
                    return $value;
                    break;
                }
            }
        }
        return false;
    }

    public static function Filter($lista, $key, $valor)
    {
        $listaAux = array();

        if($lista != null)
        {
            $i = 0;
            foreach ($lista as $value) 
            {
                if ($value->$key == $valor) 
                {
                    array_push($listaAux, $lista[$i]);
                }
                $i++;
            }
        }
        return $listaAux;
    }

    public static function Replace($lista, $key, $valor, $itemReplace)
    {
        if($lista != null)
        {
            $i = 0;
            foreach ($lista as $value) 
            {
                if ($value->$key == $valor) 
                {
                    $lista[$i] = $itemReplace;
                }
                $i++;
            }
        }
    }
}