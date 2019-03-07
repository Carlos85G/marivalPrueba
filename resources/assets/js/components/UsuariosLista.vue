<template>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <span class="h3">Lista de usuarios registrados</span>
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#editor" data-backdrop="static" data-keyboard="false" v-on:click="crearRegistroUsuario()">
                                Nuevo
                                <i class="glyphicon glyphicon-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                              <th scope="col">ID</th>
                              <th scope="col">Nombre</th>
                              <th scope="col">Correo</th>
                              <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <usuarios-usuario v-for="(usuario, index) in datosPaginacion.data" :key="usuario.id" :usuario="usuario" @eliminarRegistro="eliminarRegistroUsuario(index)" @editarRegistro="editarRegistroUsuario(usuario)"></usuarios-usuario>
                        </tbody>
                    </table>
                    <div class="pull-right">
                        <pagination :data="datosPaginacion" :limit="3" @pagination-change-page="obtenerResultados"></pagination>
                    </div>
                </div>
            </div>
        </div>

        <usuario-editor :datosUsuario="datosUsuario" v-on:recargar="recargarDatos()"></usuario-editor>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                datosPaginacion: {},
                datosUsuario: null
            }
        },
        mounted() {
            this.obtenerResultados();
        },
        methods:{
            eliminarRegistroUsuario(index){
                this.datosPaginacion.data.splice(index, 1);
                this.obtenerResultados(this.datosPaginacion.current_page);
            },
            editarRegistroUsuario(data){
                this.datosUsuario = data;
            },
            crearRegistroUsuario(){
                this.datosUsuario = null;
            },
            obtenerResultados(page = 1){
                axios.get('/usuarios?page=' + page).then(response => (this.datosPaginacion = response.data));
            },
            recargarDatos(){
                $('#editor').modal('hide');
                this.datosUsuario = null;
                this.obtenerResultados(this.datosPaginacion.current_page);
            }
        }
    }
</script>
