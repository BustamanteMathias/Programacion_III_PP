<?php

class Archivo
{
    public static function toJSON($pathDestino, $data)
    {
        $status = 0;

        if(is_string($pathDestino))
        {
            $file   = fopen($pathDestino, 'w');
            $status = fwrite($file, serialize($data));
            fclose($file);
        }
        return $status;
    }

    public static function getJSON($pathArchivo)
    {
        $data = array();

        if(file_exists($pathArchivo))
        {
            if(is_string($pathArchivo))
            {
                $file = fopen($pathArchivo, 'r');
                $data = fgets($file);
                $data = unserialize($data);
                fclose($file);
            }
        }
        
        return $data;
    }

    public static function SaveImage($KeyImagenPost, $pathDestino, $nameImage)
    {
        $origen         = $_FILES[$KeyImagenPost]['tmp_name'];

        $explodeString  = explode('/', $_FILES[$KeyImagenPost]['type']);
        $explodeString  = array_reverse($explodeString);
        $extensionImagen= $explodeString[0];

        $destino    = $pathDestino . $nameImage . '.' . $extensionImagen;
        
        return move_uploaded_file($origen, $destino);
    }

    public static function ImageMerge($ImgBase, $ImgMarca, $pathExitImg, $margenX, $margenY, $transparencia)
    {
        $imagenBase  = imagecreatefromjpeg($ImgBase);
        $imagenMarca = imagecreatefrompng($ImgMarca);
        $ax = imagesx($imagenMarca);
        $ay = imagesy($imagenMarca);

        if(file_exists($ImgBase) && file_exists($ImgMarca))
        {
            if($transparencia < 0 || $transparencia > 100)
            {
                $transparencia = 0;
            }
            imagecopymerge($imagenBase,$imagenMarca,imagesx($imagenBase)-$ax-$margenX,imagesy($imagenBase)-$ay-$margenY,0,0,$ax,$ay,$transparencia);
            imagepng($imagenBase,$pathExitImg);
            imagedestroy($imagenBase);
            return true;
        }
        else
        {
            return false;
        }
    }
}