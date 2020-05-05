<?PHP
require_once dirname( __DIR__, 1) . '\src\managerArchivos.php';
require_once __DIR__ . '\token.php';

class User
{
    public $email, $clave;
    
    public function __construct(){}
    
    public static function GetUsuarioPost()
    {
        if(Peticion::Validate('email')
        && Peticion::Validate('clave'))
        {
            $User = new User();
            $User->email    = Peticion::POST('email'); 
            $User->clave    = Peticion::POST('clave');
            return $User;
        }
        return null;
    }

    public static function UserGetLista_JSON($path)
    {
        return $lista = Archivo::getJSON($path);
    }

    public static function UserGet_JSON($path, $Usuario)
    {
        $lista = Archivo::getJSON($path);
        foreach ($lista as $value) 
        {
            if ($value->ID == $Usuario->ID) 
            {
                return $value;
            }
        }
        return null;
    }

    public static function UserFind_JSON($path, $Usuario)
    {
        $lista = Archivo::getJSON($path);
        foreach ($lista as $value) 
        {
            if ($value->email == $Usuario->email) 
            {
                return true;
            }
        }
        return false;
    }

    public static function ValidateAdmin($path, $usuario)
    {
        $lista = Archivo::getJSON($path);
        foreach ($lista as $value) 
        {
            if ($value->nombre == $usuario && $value->tipo == 'admin') 
            {
                return true;
            }
        }
        return false;
    }

    public static function UserAdd_JSON($path, $Usuario)
    {
        $rtn = false;

        $lista = Archivo::getJSON($path);
        if(!User::UserFind_JSON($path, $Usuario))
        {
            array_push($lista, $Usuario);
            Archivo::toJSON($path, $lista);
            $rtn = true;
        }

        return $rtn;
    }

    public static function UserCheckAccount($path, $email, $clave)
    {
        $lista = Archivo::getJSON($path);
        foreach ($lista as $value) 
        {
            if ($value->email == $email && $value->clave == $clave) 
            {
                Token::NewTokenUser($email, $clave);
                return true;
            }
        }
        return false;
    }
}