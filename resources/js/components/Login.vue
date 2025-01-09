<template>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-75">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8 shadow-card">
                                <div class="card-header pb-0 text-left bg-transparent">
                                    <h3 class="font-weight-bolder text-info text-gradient">Welcome</h3>
                                </div>
                                <div class="card-body">
                                    <ul v-for="error in formState.errors">
                                        <p class="text-danger">
                                            {{ error }}
                                        </p>
                                    </ul>
                                    <form @submit.prevent="login">
                                        <label>Email</label>
                                        <div class="mb-3">
                                            <input type="email" class="form-control" v-model="formState.email" id="email" placeholder="Email" value="admin@crawley-accountant.com" aria-label="Email" aria-describedby="email-addon" required >
                                        </div>
                                        <label>Password</label>
                                        <div class="mb-3">
                                            <input type="password" class="form-control" v-model="formState.password" id="password" placeholder="Password" value="secret" aria-label="Password" aria-describedby="password-addon">
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <small class="text-muted">Forgot your password? Reset you password
                                        <a href="#" class="text-info text-gradient font-weight-bold">here</a>
<!--                                        <a href="/login/forgot-password" class="text-info text-gradient font-weight-bold">here</a>-->
                                    </small>
                                    <!--                  <p class="mb-4 text-sm mx-auto">
                                                        Don't have an account?
                                                        <a href="register" class="text-info text-gradient font-weight-bold">Sign up</a>
                                                      </p>-->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-gradient-primary oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<script setup>
    import { reactive } from "vue";
    import axios from "@/axios.js";
    import { useRouter } from "vue-router";
    import store from "@/state/index.js";

    const router = useRouter();

    const formState = reactive({
        email : 'admin@google.com',
        password: 'secret',
        errors: []
    });

    const login = () => {
        axios.get('/sanctum/csrf-cookie').then(response => {
            axios.post('api/login', {
                email: formState.email,
                password: formState.password
            }).then(response => {
                const status = true;
                const token = response.data.token;
                if (store.getters.authToken) {
                    window.axios.defaults.headers.common['Authentication'] = `Bearer ${store.getters.authToken}`;
                }
                store.dispatch('checkAuthStatus', status);
                store.dispatch('setAuthToken', token);

                router.push({
                    name: 'dashboard'
                })
            }).catch(error => {
                console.log(error);
                if (error.response.status == 422) {
                    formState.errors = Object.values(error.response.data.errors).flat()
                } else {
                    formState.errors = Object.values([error.response.data.errors]).flat()
                }
            });
        });
    }


</script>
