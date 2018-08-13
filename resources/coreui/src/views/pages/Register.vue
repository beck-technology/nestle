<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mx-4">
                        <div class="card-body p-4">
                            <h1>Register</h1>
                            <p class="text-muted">Create your account</p>
                            <div class="input-group mb-3">
                                <span class="input-group-addon">
                                    <i class="icon-user"></i>
                                </span>
                                <input type="text" class="form-control" v-model="user.username" placeholder="Username">
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-addon">@</span>
                                <input type="text" class="form-control" v-model="user.email" placeholder="Email">
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-addon">
                                    <i class="icon-lock"></i>
                                </span>
                                <input type="password" class="form-control" v-model="user.password" placeholder="Password">
                            </div>

                            <div class="input-group mb-4">
                                <span class="input-group-addon">
                                    <i class="icon-lock"></i>
                                </span>
                                <input type="password" class="form-control" v-model="user.repeatPassword" placeholder="Repeat password">
                            </div>

                            <button type="button" @click.prevent="register" class="btn btn-block btn-success">Create Account</button>
                        </div>
                        <div class="card-footer p-4">
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-block btn-facebook" type="button">
                                        <span>facebook</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-block btn-twitter" type="button">
                                        <span>twitter</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "company",
        data() {
            return {
                user: {
                    username: "",
                    email: "",
                    password: "",
                    repeatPassword: ""
                }
               
            }
        },

        created() {
        },
         computed: {
            counter: function() {
                return this.$store.getters.counter
            }
        },

        methods: {
            register() {
                if(this.user.password !== this.user.repeatPassword){
                    console.log('Password is not same')
                    return
                }
                this.$http.post('/api/register', this.user)
                    .then(
                        (response) => {
                            console.log(response)
                            
                        }
                    )
                    .catch(
                        (error) => {
                            console.log(error)
                            this.$swal({
                                title: "",
                                text: "IDかパスワードが違います。",
                                type: "warning",
                                confirmButtonText: "OK"
                            });
                        }
                    );
            },
        }
    };
</script>