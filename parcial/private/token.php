<?PHP

use \Firebase\JWT\JWT;

class Token
{
    public static $key = 'pro3-parcial';

    public static function NewTokenUser($email, $clave)
    {
        $payload = array("email" => $email, "clave" => $clave );

        return Token::Encode($payload);
    }

    public static function Encode($payload)
    {
        try 
        {
            $token = JWT::encode($payload, Token::$key);
            $token ? Token::MessageGenerate($token, true) : Token::MessageGenerate($token, false);
        } 
        catch (\Throwable $th) 
        {
            return null;
        }
    }

    public static function Decode($Token)
    {
        try 
        {
            return JWT::decode($Token, Token::$key, array('HS256')); 
        } 
        catch (\Throwable $th) 
        {
            return null;
        }
    }

    private static function MessageGenerate($token, $setStatus)
    {
        $objAux = array('TOKEN: ' => 'ERROR AL GENERAR', 'SU TOKEN: ' => 'NO GENERADO');

        if($setStatus)
        {
            $objAux = array('TOKEN: ' => 'GENERADO CORRECTAMENTE', 'SU TOKEN: ' => $token);
        }
        
        echo(json_encode($objAux));
    }
}