<template>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <span class="h3">Lista de usuarios registrados</span>
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-lg" aria-expanded="false">
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
                            <usuarios-usuario v-for="(usuario, index) in datosPaginacion.data" :key="usuario.id" :usuario="usuario" @eliminarRegistro="eliminarRegistroUsuario(index)"></usuarios-usuario>
                        </tbody>
                    </table>
                    <pagination :data="datosPaginacion" @pagination-change-page="obtenerResultados"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                datosPaginacion: {}
            }
        },
        mounted() {
            this.obtenerResultados();
        },
        methods:{
            eliminarRegistroUsuario(index){
                console.log('eliminando del DOM');
                this.datosPaginacion.data.splice(index, 1);
                this.obtenerResultados(this.datosPaginacion.current_page);
            },
            obtenerResultados(page = 1){
                axios.get('/usuarios?page=' + page).then(response => (this.datosPaginacion = response.data));
            }
        }
    }
</script>
