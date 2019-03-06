<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'puesto'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Reescritura: Aplicar encriptación al guardar
     * @param string $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        /* Cambiar la contraseña sólo si es una cadena no vacía */
        if (mb_strlen($password) > 0) {
            $this->attributes['password'] = $this->encriptar($password);
        }
    }

    /**
     * Método público para comparar si la contraseña es igual a la del modelo
     * @param string $password
     * @return boolean
     */
    public function comparePassword($password)
    {
        return ($this->encriptar($password) === $this->password);
    }

    /**
    * Función estática para recuperar direccion de fotografía de perfil predeterminada. Estática para poder llamar sin instanciar.
    * @return string
    */
    public static function fotografiaPredeterminada()
    {
        return self::generarUrlFotografiaByCodigo(0);
    }

    /**
     *
     * Función privada para generar la dirección de una fotografía utilizando el ID. La función es estática para trabajar con las funciones de fotografía predeterminada.
     * @param int $id
     * @return string URL pública de la imagen
     */
    private static function generarUrlFotografiaById($id)
    {
        return route('usuarios.foto', $id);
    }

    /**
     * Método privado para retornar una cadena encriptada. Hecho para mantener el algoritmo DRY
     * @param string $dato
     * @return string
    */
    private function encriptar($dato)
    {
        return bcrypt($dato);
    }
}
