<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Lista de Usuarios</div>
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
                                <tr v-for="usuario in usuarios">
                                    <th scope="row">{{ usuario.id }}</th>
                                    <td>{{ usuario.nombre }}</td>
                                    <td>{{ usuario.email }}</td>
                                    <td>Editar - Eliminar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



</template>
<script>
    export default {
        data(){
            usuarios: []
        },
        mounted() {
            axios.get('/usuarios').then(response => (this.usuarios = response.data));
        }
    }
</script>
