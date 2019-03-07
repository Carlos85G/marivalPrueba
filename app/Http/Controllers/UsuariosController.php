<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\User;
use DB;
use Image;

class UsuariosController extends Controller
{
    /* Propiedad protegida para designar el espacio donde están las fotografías */
    protected $direccionFoto = 'cargas/usuarios';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /* Incluir la información de autenticación */
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /* Obtener datos para mantener programación DRY. Limitar a 3 */
        $datos = User::paginate(3);

        /* Mandar la información en AJAX */
        if ($request->ajax()) {
            return $datos->toJson();
        }

        /* Ejecuta sólo si la petición es regular */
        return view('crud', [
            'datos' => $datos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('usuarios.editor', [
            'usuario' => null,
            'editar' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->escribir($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('usuarios.editor', [
            'usuario' => User::find($id),
            'editar' => false
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('usuarios.editor', [
            'usuario' => User::find($id),
            'editar' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->escribir($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* Inicio de transacción */
        DB::beginTransaction();

        try {
            /* Obtener usuario para usos futuros. El usuario no puede autoeliminarse */
            $usuario = User::findOrFail($id);

            /* Enviar mensaje en caso que el usuario haya tratado de eliminarse a sí mismo */
            if ($usuario->id == Auth::user()->id) {
                /* Deshacer cambios en base de datos */
                DB::rollback();

                /* 403: Forbidden */
                return response('0', 403);
            }

            /* Eliminar usuario */
            $usuario->delete();

            /* Todo terminó correctamente. Cometer la transacción */
            DB::commit();
        } catch (ModelNotFoundException $e) {
            /* Deshacer cambios en base de datos */
            DB::rollback();

            /* 404: Not Found */
            return response('0', 404);
        } catch (\Exception $e) {
            /* Deshacer cambios en base de datos */
            DB::rollback();

            /* 500: Internal Server Error */
            return response('0', 500);
        }

        /* 200: OK */
        return response('1', 200);
    }

    /**
     * Recuperar la imagen de perfil del usuario
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function avatar($id)
    {
        $direccionAbsoluta = storage_path($this->direccionFoto) . '/';
        $direccionDefault = $direccionAbsoluta . 'default-320.png';
        $usuario = User::find($id);

        /* Buscar usuario */
        if (!is_null($usuario)) {
            $direccionUsuario = $direccionAbsoluta . $usuario->id . '.jpg';
            if (file_exists($direccionUsuario)) {
                return Image::make($direccionUsuario)->response();
            }
        }

        /* Retornar imagen predeterminada */
        return Image::make($direccionDefault)->response();
    }

    /**
     * Resuelve los conflictos de eliminar utilizando las respuestas del usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dismiss(Request $request, $id)
    {
        /* Retorno regular de direccion */
        $retorno = redirect()->route('usuarios');

        /* Validación exitosa. Continuar cono inicio de transacción */
        DB::beginTransaction();

        try {
            /* Obtener usuario para usos futuros. El usuario no puede autoeliminarse */
            $usuario = User::findOrFail($id);

            /* Enviar mensaje en caso que el usuario haya tratado de eliminarse a sí mismo */
            if ($usuario->id == Auth::user()->id) {
                /* Deshacer cambios en base de datos */
                DB::rollback();

                return $retorno->with('fatal', '<p>No es posible eliminar el usuario actualmente en uso. Por favor, contacte a su administrador.</p>');
            }

            /* Eliminar usuario */
            $usuario->delete();

            /* Todo terminó correctamente. Cometer la transacción y redireccionar a la lista */
            DB::commit();
        } catch (\Exception $e) {
            /* Deshacer cambios en base de datos */
            DB::rollback();

            return $retorno->with('fatal', "<p>No fue posible eliminar el usuario porque hubo un fallo en el servidor. Por favor, contacte a su administrador.</p>");
        }

        return $retorno->with('listo', '<p>Los conflictos fueron resueltos y el usuario ha sido eliminado correctamente.</p>');
    }

    /**
     * Función privada para crear un validador para el editor de users
     *
     * @param \Illuminate\Http\Request  $request
     * @param int|NULL  $id
     * @return Validator
     */
    private function crearValidador($request, $id = null)
    {
        $usuariable = User::find($id);
        $esUsuariable = !is_null($usuariable);

        return Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:users,email,'.($esUsuariable? $usuariable->id : 'NULL').',id,deleted_at,NULL',
            'password' => ($esUsuariable? 'sometimes|nullable' : 'required').'|min:6|confirmed',
            'puesto' => 'required|max:191',
            'nombre' => 'required|max:191',
            'fotografia' => 'nullable|image',
        ])->setAttributeNames([
            'fotografia' => 'fotograf&iacute;a',
        ]);
    }

    /**
     * Función privada para guardar o actualizar un usuario nuevo o existente, respectivamente
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|NULL  $id
     * @return \Illuminate\Http\Response
     */
    private function escribir(Request $request, $id = null)
    {
        $esId = !is_null($id);
        $retorno = $esId? redirect()->route('usuarios.editar', $id) : redirect()->route('usuarios.nuevo');
        $validador = $this->crearValidador($request, $id);

        if ($validador->fails()) {
            return $retorno->withErrors($validador)->withInput();
        }

        /* Inicio de transacción */
        DB::beginTransaction();

        try {
            /* Recuperar los valores que sólo son del usuario, para uso futuro */
            $usuarioValores = $request->except(['_token', 'password_confirmation', 'fotografia']);

            /* Recuperar modelo eliminado para eventual reactivación */
            $usuario = User::find($id);

            /* Si no hay registro, crear */
            if (is_null($usuario)) {
                /* Crear usuario y asignar usuariable */
                $usuario = Usuario::create($usuarioValores);
            } else {
                /* Actualizar datos con información actual */
                $usuario->fill($usuarioValores)->save();
            }

            /* Guardar la nueva fotografía */
            if ($request->hasFile('fotografia')) {
                Image::make($request->fotografia->path())->resize(
                    500,
                    500,
                    function ($restriccion) {
                        $restriccion->aspectRatio();
                    }
                )->save(storage_path($this->direccionFoto) . '/' . $usuario->id . '.jpg');
            }

            /* Todo terminó correctamente. Cometer la transacción */
            DB::commit();
        } catch (\Exception $e) {
            /* Deshacer cambios en base de datos */
            DB::rollback();

            /* Retornar con error */
            return $retorno->with('fatal', 'Hubo errores al procesar la consulta. Por favor, contacte a su administrador.')->withInput();
        }

        return $retorno->with('listo', ($esCodigo? 'La informaci&oacute;n del usuario con ID ' . $usuario->id . ' ha sido actualizado.' : 'El usuario con ID ' . $usuario->id . ' ha sido guardado. <a href="'.route('usuarios.editar', $usuario->id).'" class="alert-link">Haga clic aqu&iacute; para editarlo.</a>'));
    }
}
