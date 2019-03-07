<template>
    <tr>
        <th scope="row">{{ usuario.id }}</th>
        <td>{{ usuario.name }}</td>
        <td>{{ usuario.email }}</td>
        <td class="text-right">
            <button class="btn btn-info" v-on:click="editarUsuario()" data-toggle="modal" data-target="#editor" data-backdrop="static" data-keyboard="false">Editar</button>
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
        methods:{
            eliminarUsuario(){
                axios.delete('/usuarios/' + this.usuario.id + '/eliminar').then(
                    response => (this.$emit('eliminarRegistro'))
                );
            },
            editarUsuario(){
                this.$emit('editarRegistro', this.usuario);
            }
        }
    }
</script>
