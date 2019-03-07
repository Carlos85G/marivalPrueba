<template>
    <tr>
        <th scope="row">{{ usuario.id }}</th>
        <td>{{ usuario.name }}</td>
        <td>{{ usuario.email }}</td>
        <td>
            <button class="btn btn-info" v-on:click="editarUsuario()">Editar</button>
            <button class="btn btn-danger" v-on:click="eliminarUsuario()">Eliminar</button>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['usuario'],
        data(){
            return {
            }
        },
        mounted() {
            console.log('Usuario cargado');
        },
        methods:{
            eliminarUsuario(){
                axios.delete('/usuarios/' + this.usuario.id + '/eliminar').then(
                    response => (this.$emit('eliminarRegistro'))
                );
            },
            editarUsuario(){

            }
        }
    }
</script>
