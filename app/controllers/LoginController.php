<?php

namespace App\controllers;
use App\controllers\BaseController;
use App\models\Admin;


class LoginController extends BaseController {

    public function getSesion($request){

        $titulo = "Iniciar Sesion";
        return $this->renderHTML('login.twig' , ['titulo'=>$titulo]);


    }

    public function verificar($request){

        
        
        $dato_post = $request->GetParsedBody();

        //$nuevo_user = new Admin();

        $user = Admin::all();

        foreach ($user as $key) {
           
        
        
        if ($key->usuario == $dato_post["texto_usuario"] && $key->contraseña == $dato_post["texto_contraseña"]){

            $texto = "Bienvenido ";
            return $this->renderHTML('sesion.twig' , ['texto'=>$texto, 'usuario' => $key->usuario]);
            
        }else

        $bool = FALSE;
        

        }

        if ($bool == FALSE) {
            
        
        $mensaje1 = "El Usuario y/o Contraseña son incorrectos.";
        
        return $this->renderHTML('login.twig' , ['mensaje1' => $mensaje1]);

            
        }

        

    }


}

?>