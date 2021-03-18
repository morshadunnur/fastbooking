<template>
    <auth-layout>
        <template #main-content>
            <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">



                <div class="flex flex-wrap">
                    <div class="bg-white">
                        <input type="email" v-model="form.email">
                        <input type="password" v-model="form.password">
                    </div>
                    <div>
                        <button class="" @click.prevent="submit">Login</button>
                    </div>
                </div>



            </div>
        </template>
    </auth-layout>
</template>

<script>
import AuthLayout from "../Layouts/AuthLayout";
export default {
    name: "Login",
    components: {AuthLayout},
    data(){
        return {
            sending: false,
            form: {
                email: 'abc@gmail.com',
                password: '123456',
                remember: null
            }
        }
    },

    methods: {
        submit() {
            const data = {
                email: this.form.email,
                password: this.form.password,
                remember: this.form.remember
            }
            this.$inertia.post(this.route('login'), data, {
                onStart: () => this.sending = true,
                onFinish: () => this.sending = false,
            })
        }
    }
}
</script>

<style scoped>

</style>
