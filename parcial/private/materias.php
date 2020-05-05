<?php
require_once dirname( __DIR__, 1) . '\src\managerArchivos.php';
require_once dirname( __DIR__, 1) . '\src\managerMessage.php';

class Materia
{
    public $nombre, $cuatrimestre;
    public $identificador = 1;

    public function __construct(){}

    public static function GetMateriaPost()
    {

        $Materia                = new Materia();
        $Materia->nombre        = Peticion::POST('nombre');
        $Materia->cuatrimestre  = Peticion::POST('cuatrimestre');
            
        return $Materia;
    }

    private static function Find($path, $Materia)
    {
        $lista = Archivo::getJSON($path);

        if($lista != null)
        {
            foreach ($lista as $value) 
            {
                if ($value->nombre == $Materia->nombre && $value->cuatrimestre == $Materia->cuatrimestre) 
                {
                    return true;
                    break;
                }
            }
        }
        return false;
    }

    private static function EndMateria($path, $Materia)
    {
        $lista = Archivo::getJSON($path);
        $existeLista = false;
        $existeMateria = false;
        
        if($lista != null)
        {
            $existeLista = true;
            foreach ($lista as $value) 
            {
                if ($value->nombre == $Materia->nombre && $value->cuatrimestre == $Materia->cuatrimestre) 
                {
                    $existeMateria = true;
                    break;
                }
            }
        }

        //SI EXISTE LA LISTA PERO NO EL Materia -> genero un identificador
        if($existeLista && !$existeMateria)
        {
            $Materia->identificador = count($lista)+1;
        }

        return $Materia;
    }

    public static function Save($path, $Materia)
    {
        $rtn = false;

        $lista = Archivo::getJSON($path);
        $MateriaAux = Materia::EndMateria($path, $Materia);

        if($MateriaAux != null)
        {
            if(!Materia::Find($path, $MateriaAux))
            {
                array_push($lista, $MateriaAux);
            }
            else
            {
                Message::Error('MATERIA REPETIDA');
            }
            
            Archivo::toJSON($path, $lista);
            Message::Success($lista);
            $rtn = true;
        }
        
        return $rtn;
    }

    public static function GetMateriaID($path, $id)
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