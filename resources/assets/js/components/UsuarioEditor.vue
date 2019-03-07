<template>
    <div class="row marival-formulario">
        <div class="col-lg-12">
            <template v-if="editar">
                <form role="form" method="post" enctype="multipart/form-data" action="" v-on:submit.prevent="ejecutar()">
            </template>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Credenciales
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <template v-if="editar">
                                <div class="col-lg-4">
                                    <div class="form-group{{ ($errors->has('password') ? ' has-error' : '' ) }}">
                                        <label>Contrase&ntilde;a</label>
                                        <input class="form-control" type="password" name="password" value="" />
                                        <p class="help-block">
                                        @if ($errors->has('password'))
                                            <strong>{{ $errors->first('password') }}</strong>
                                        @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group{{ ($errors->has('password_confirmation') ? ' has-error' : '' ) }}">
                                        <label>Confirmar contrase&ntilde;a</label>
                                        <input class="form-control" type="password" name="password_confirmation" value="" />
                                        <p class="help-block">
                                        @if ($errors->has('password_confirmation'))
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        @endif
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group{{ ($errors->has('puesto') ? ' has-error' : '' ) }}">
                                    <label>Puesto</label>
                                    <template v-if="editar">@if($editar)
                                        <input class="form-control" type="text" name="puesto" value="{{ $tieneUsuario? $usuario->puesto : old('puesto') }}" />
                                        <p class="help-block">
                                        @if ($errors->has('puesto'))
                                            <strong>{{ $errors->first('puesto') }}</strong>
                                        @endif
                                        </p>
                                    </template>
                                    <template v-else>
                                        <p class="form-control-static">{{ usuario.puesto }}<p>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                      <i class="fa fa-user fa-fw"></i>
                      Informaci&oacute;n personal
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group{{ ($errors->has('nombre') ? ' has-error' : '' ) }}">
                                            <label>Nombre</label>
                                            <template v-if="editar">
                                                <input class="form-control" type="text" name="name" value="{{ ($tieneUsuario)? usuario.name : old('name') }}" />
                                                <p class="help-block">
                                                @if ($errors->has('name'))
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                @endif
                                                </p>
                                            </template>
                                            <template v-else-if="tieneUsuario">
                                                <p class="form-control-static">{{ $usuario.name }}<p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group{{ ($errors->has('email') ? ' has-error' : '' ) }}">
                                            <label>Correo electr&oacute;nico</label>
                                            <template v-if="editar">@if($editar)
                                                <input class="form-control" type="text" name="email" value="{{ ($tieneUsuario)? usuario.email : old('email') }}" />
                                                <p class="help-block">
                                                @if ($errors->has('email'))
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                @endif
                                                </p>
                                            </template>
                                            <template v-else-if="tieneUsuario">
                                                <p class="form-control-static">{{ usuario.email }}<p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group{{ ($errors->has('fotografia') ? ' has-error' : '' ) }}">
                                    <label>Fotograf&iacute;a</label>
                                    <template v-if="editar">
                                        <a href="#" class="marival-fotografia-proxy">
                                    </template>
                                        <img src="{{ ($tieneUsuario)? usuario.fotografia : App\Usuario::fotografiaPredeterminada() }}" alt="Fotograf&iacute;a de usuario" class="img-thumbnail img-fluid marival-fotografia-thumb" />
                                    <template v-if="editar">
                                        </a>
                                        <input type="file" class="hidden marival-fotografia" name="fotografia" accept="image/jpeg,image/gif,image/x-png" />
                                    </template>
                                    @if ($errors->has('fotografia'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('fotografia') }}</strong>
                                    </p>
                                    @else
                                    <p class="help-block"></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->

                <template v-if="editar">
                    <!-- /.panel -->
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <button type="submit" class="btn btn-default pull-right">Guardar</button>
                        </div>
                    </div>
                </template>
            <template v-if="editar">
                </form>
            </template>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                editar: false,
                tieneUsuario: false,
            }
        },
        mounted() {
            axios.get('/usuarios/1').then(response => (this.usuario = response.data));
        },
        methods: {
            ejecutar() {
                if(this.tieneUsuario){
                    /*axios.patch()*/
                }else{
                    /*axios.post()*/
                }

              /* {{ $tieneUsuario? route('usuarios.actualizar', usuario.id) : route('usuarios.guardar') }} */
            }
        }
    }
</script>
