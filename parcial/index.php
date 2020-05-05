<?PHP
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/managerPeticion.php';
require_once __DIR__ . '/src/managerMessage.php';
require_once __DIR__ . '/src/managerArchivos.php';
require_once __DIR__ . '/private/user.php';
require_once __DIR__ . '/private/materias.php';
require_once __DIR__ . '/private/profesores.php';

//PATHS
$PATH_USUARIOS              = __DIR__ . '\archivos\users.json';
$PATH_MATERIAS              = __DIR__ . '\archivos\materias.json';
$PATH_PROFESORES            = __DIR__ . '\archivos\profesores.json';
$PATH_MATERIAS_PROFESORES   = __DIR__ . '\archivos\materias-profesores.json';

switch (Peticion::GetRequest_Method($_SERVER)) {
    case 'POST':
        switch (Peticion::GetPath_Info($_SERVER)) {
            case '/usuario':
                $Usuario = User::GetUsuarioPost();
                $Usuario ?? Message::Error('DATOS FALTANTES');

                if (!is_null($Usuario)) {
                    if (!User::UserFind_JSON($PATH_USUARIOS, $Usuario)) {
                        if (User::UserAdd_JSON($PATH_USUARIOS, $Usuario)) {
                            Message::Success($Usuario);
                        } else {
                            Message::Custom($Usuario, 'ERROR AL GUARDAR USUARIO');
                        }
                    } else {
                        Message::Custom('USUARIO REPETIDO', $Usuario);
                    }
                }
                break;

            case '/login':

                if (Peticion::Validate('email') && Peticion::Validate('clave')) {
                    $email = Peticion::POST('email');
                    $clave = Peticion::POST('clave');

                    if (!User::UserCheckAccount($PATH_USUARIOS, $email, $clave)) {
                        Message::Error('DATOS INVALIDOS');
                    }
                }
                break;

            case '/materia':

                $token = Peticion::GetHeader('token');

                if (!is_null($token)) {
                    if (!is_null(Token::Decode($token))) {
                        if (Peticion::Validate('nombre') && Peticion::Validate('cuatrimestre')) {
                            $materia = Materia::GetMateriaPost();
                            if (!Materia::Save($PATH_MATERIAS, $materia)) {
                                Message::Custom($materia, 'NO SE PUDO GUARDAR MATERIA');
                            }
                        } else {
                            Message::Error('PROBLEMAS CON LAS CLAVES');
                        }
                    } else {
                        Message::Error('TOKEN INCORRECTO');
                    }
                } else {
                    Message::Error('TOKEN NO RECIBIDO');
                }
                break;

            case '/profesor':

                $token = Peticion::GetHeader('token');

                if (!is_null($token)) {
                    if (!is_null(Token::Decode($token))) {
                        if (Peticion::Validate('nombre') && Peticion::Validate('legajo')) {
                            $profesor = Profesor::GetProfesorPost();
                            if (Profesor::Save($PATH_PROFESORES, $profesor)) {
                                $pathImagen = __DIR__ . '/imagenes/';
                                $imageName = $profesor->legajo . $profesor->nombre;
                                Archivo::SaveImage('imagen', $pathImagen, $imageName);
                            }
                        } else {
                            Message::Error('PROBLEMAS CON LAS CLAVES');
                        }
                    } else {
                        Message::Error('TOKEN INCORRECTO');
                    }
                } else {
                    Message::Error('TOKEN NO RECIBIDO');
                }
                break;

            case '/asignacion':

                $token = Peticion::GetHeader('token');

                if (!is_null($token)) {
                    if (!is_null(Token::Decode($token))) {
                        if (Peticion::Validate('legajo') && Peticion::Validate('id') && Peticion::Validate('turno')) {
                            $materiasProfesores                     = new stdClass();
                            $materiasProfesores->legajoProfesor     = Peticion::POST('legajo');
                            $materiasProfesores->idMateria          = Peticion::POST('id');
                            $materiasProfesores->turnoMateria       = Peticion::POST('turno');

                            $lista  = Archivo::getJSON($PATH_MATERIAS_PROFESORES);
                            $flag   = false;

                            if ($lista != null) {
                                foreach ($lista as $value) {
                                    if (
                                        $value->legajoProfesor  == $materiasProfesores->legajoProfesor &&
                                        $value->idMateria       == $materiasProfesores->idMateria &&
                                        $value->turnoMateria    == $materiasProfesores->turnoMateria
                                    ) {
                                        $flag = true;
                                        break;
                                    }
                                }
                            }

                            if (!$flag) {
                                array_push($lista, $materiasProfesores);
                                Archivo::toJSON($PATH_MATERIAS_PROFESORES, $lista);

                                Message::Success($materiasProfesores);
                            } else {
                                Message::Error('YA SE ENCUENTRA ESTE PROFESOR PARA ESTA MATERIA EN ESE TURNO');
                            }
                        } else {
                            Message::Error('PROBLEMAS CON LAS CLAVES');
                        }
                    } else {
                        Message::Error('TOKEN INCORRECTO');
                    }
                } else {
                    Message::Error('TOKEN NO RECIBIDO');
                }

                break;

            default:
                Message::Error('PATH NO ADMITIDO. PATH: ' . Peticion::GetPath_Info($_SERVER));
                break;
        }
        break;

    case 'GET':
        switch (Peticion::GetPath_Info($_SERVER)) {
            case '/materia':

                $token = Peticion::GetHeader('token');

                if (!is_null($token)) {
                    if (!is_null(Token::Decode($token))) {
                        echo (json_encode(Archivo::getJSON($PATH_MATERIAS)));
                    } else {
                        Message::Error('TOKEN INCORRECTO');
                    }
                } else {
                    Message::Error('TOKEN NO RECIBIDO');
                }
                break;

            case '/profesor':

                $token = Peticion::GetHeader('token');

                if (!is_null($token)) {
                    if (!is_null(Token::Decode($token))) {
                        echo (json_encode(Archivo::getJSON($PATH_PROFESORES)));
                    } else {
                        Message::Error('TOKEN INCORRECTO');
                    }
                } else {
                    Message::Error('TOKEN NO RECIBIDO');
                }
                break;

            case '/asignacion':

                $token = Peticion::GetHeader('token');

                if (!is_null($token)) {
                    if (!is_null(Token::Decode($token))) {
                        echo (json_encode(Archivo::getJSON($PATH_MATERIAS_PROFESORES)));
                    } else {
                        Message::Error('TOKEN INCORRECTO');
                    }
                } else {
                    Message::Error('TOKEN NO RECIBIDO');
                }
                break;

            default:
                Message::Error('PATH NO ADMITIDO. PATH: ' . Peticion::GetPath_Info($_SERVER));
                break;
        }
        break;
    default:
        Message::Warning('Tipo de peticion no admitida');
        break;
}
