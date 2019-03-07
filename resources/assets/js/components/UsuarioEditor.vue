<template>
    <div class="modal fade" id="editor" ref="editor">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" v-on:click="cerrarModal">
                        <span>×</span>
                    </button>
                    <h4>{{ titulo }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row marival-formulario">
                        <div class="col-lg-12">
                            <form role="form" enctype="multipart/form-data" action="" v-on:submit.prevent="ejecutar">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input class="form-control" type="text" name="name" v-model="usuario.name" />
                                        <p v-if="errors && errors.name" class="help-block">
                                            <strong>{{ errors.name[0] }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Correo electr&oacute;nico</label>
                                        <input class="form-control" type="text" name="email" v-model="usuario.email" />
                                        <p v-if="errors && errors.email" class="help-block">
                                            <strong>{{ errors.email[0] }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 clearfix">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Puesto</label>
                                                <input class="form-control" type="text" name="puesto"  v-model="usuario.puesto" />
                                                <p v-if="errors && errors.puesto" class="help-block">
                                                    <strong>{{ errors.puesto[0] }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Contrase&ntilde;a</label>
                                                <input class="form-control" type="password" name="password" v-model="post.password" />
                                                <p v-if="errors && errors.password" class="help-block">
                                                    <strong>{{ errors.password[0] }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Confirmar contrase&ntilde;a</label>
                                                <input class="form-control" type="password" name="password_confirmation" v-model="post.password_confirmation" />
                                                <p v-if="errors && errors.password_confirmation" class="help-block">
                                                    <strong>{{ errors.password_confirmation[0] }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fotograf&iacute;a</label>
                                            <div class="input-group">
                                                <a href="#" v-on:click.prevent="proxyArchivo">
                                                    <img :src="fotografiaThumbnail" alt="Fotograf&iacute;a de usuario" class="img-thumbnail img-fluid marival-fotografia-thumb" />
                                                </a>
                                                <input type="file" id="fotografia" class="hidden marival-fotografia" name="fotografia" ref="fotografia" accept="image/jpeg,image/gif,image/x-png" @change="arreglarSubidaArchivo" />
                                                <p v-if="errors && errors.fotografia" class="help-block">
                                                    <strong>{{ errors.fotografia[0] }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-default pull-right">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['datosUsuario'],
        data(){
            return{
                titulo: '',
                errors: {},
                post: {
                    password: '',
                    password_confirmation: '',
                },
                fotografia: '',
                fotografiaThumbnail: '',
                showModal: false
            }
        },
        computed: {
            tieneUsuario: function(){
                return (this.datosUsuario != null);
            },
            usuario: function(){
                if(this.tieneUsuario){
                    this.titulo = 'Editando usuario con ID: ' + this.datosUsuario.id;
                    this.fotografiaThumbnail = '/usuarios/' + this.datosUsuario.id + '/foto';
                    return this.datosUsuario;
                }else{
                    this.titulo = 'Registro de nuevo usuario';
                    this.fotografiaThumbnail = '/usuarios/0/foto';
                    return {
                        id: -1,
                        name: '',
                        email: '',
                        puesto: ''
                    }
                }
            }
        },
        methods: {
            proxyArchivo(){
                this.$refs.fotografia.click()
            },
            arreglarSubidaArchivo() {
                /* Necesario para mantener contexto */
                let self = this;

                /* Actualizar variables para post */
                self.fotografia = self.$refs.fotografia.files[0];

                console.log(self.fotografia);

                /* Revisar si el arreglo de archivos está incluido y si tiene el primer valor */
                if (this.$refs.fotografia.files && this.$refs.fotografia.files[0]) {

                    let lector = new FileReader();

                    /* Definir la función de retorno */
                    lector.onload = function(e) {
                        self.fotografiaThumbnail = e.target.result;
                    };

                    /* Cargar la primera imagen */
                    lector.readAsDataURL(this.$refs.fotografia.files[0]);
                }
            },
            ejecutar() {
                const datosForm = new FormData();

                /* Sacar datos del usuario */
                Object.keys(this.usuario).forEach(key => datosForm.append(key, this.usuario[key]));

                /* Sacar datos del formulario */
                Object.keys(this.post).forEach(key => datosForm.append(key, this.post[key]));

                /* Añadir foto sólo si la hay */
                if(typeof this.fotografia.name == 'string'){
                    datosForm.append('fotografia', this.fotografia);
                }

                /* Eliminar los índices si los campos están vacíos */
                if(!this.post.password.length && !this.post.password_confirmation.length){
                    datosForm.delete('password');
                    datosForm.delete('password_confirmation');
                }

                /* Eliminar el ID si es no válido */
                if(this.usuario.id < 1){
                    datosForm.delete('id');
                }

                if(this.tieneUsuario){
                    /* Parche para request */
                    datosForm.append('_method','PATCH');

                    axios.post('/usuarios/' + this.usuario.id , datosForm, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        this.cerrarModal();
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }else{
                    axios.post('/usuarios', datosForm, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        this.cerrarModal();
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
            },
            cerrarModal(){
                this.$emit('recargar');
                $('#editor').modal('hide');
            }
        },
        watch: {
            usuario: function(val){
                if(val){
                    this.errors = {};
                }
            }
        }
    }
</script>
