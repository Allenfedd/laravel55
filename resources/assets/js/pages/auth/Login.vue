<template>
    <div class="login-box">
        <div class="login-logo">
            <b>LaraVue</b>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form @submit.prevent="signIn" action="" method="post">
                <div v-if="errors.message" class="alert alert-danger">
                    {{errors.message}}
                </div>

                <div class="form-group has-feedback" :class="{ 'has-error': errors.email }">
                    <input v-model="user.email" @keydown="clearSignInError()" :disabled="loading" type="email"
                           class="form-control"
                           placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <span v-show="errors.email" class="help-block">{{ errors.email }}</span>
                </div>
                <div class="form-group has-feedback" :class="{ 'has-error': errors.password }">
                    <input v-model="user.password" @keydown="clearSignInError()" :disabled="loading" type="password"
                           class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <span v-show="errors.password" class="help-block">{{ errors.password }}</span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" :disabled="loading">
                            <span v-if="loading">Loading....</span>
                            <span v-else="!loading">Sign In</span>
                        </button>
                    </div>
                </div>
            </form>
            <!--
                        <div class="social-auth-links text-center">
                            <p>- OR -</p>
                            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign
                                in using
                                Facebook</a>
                            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign
                                in using
                                Google+</a>
                        </div>

            -->
            <!--<router-link :to="{ name: 'password.email'}" class="pull-right">Forgot Your Password?</router-link>-->
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
                user: {
                    email: '',
                    password: '',

                },
                errors: {
                    email: false,
                    password: false,
                    message: false
                }
            }
        },
        methods: {
            signIn() {
                this.loading = true;
                this.$http.post('/login', this.user)
                    .then(response => {
                        this.loading = false;
                        this.$store.dispatch('setToken', response.data.token);

                        this.$router.push({name: 'admin.dashboard'});
                    })
                    .catch(error => {
                        this.loading = false;

                        const {status, data} = error.response;
                        if (status === 401) {
                            this.errors.message = data.message;
                        }
                        if (status === 422) {
                            this.signInError(data);
                        }
                    });
            },
            signInError(data) {
                if (data.errors) {
                    this.errors.email = data.errors.email ? data.errors.email[0] : null;
                    this.errors.password = data.errors.password ? data.errors.password[0] : null;
                }
            },
            clearSignInError() {
                this.errors.email = this.errors.password = this.errors.message = null;
            }
        },
    }
</script>