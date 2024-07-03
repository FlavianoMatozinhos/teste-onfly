<template>
    <form @submit.prevent="register">
        <label>Name</label>
        <input
            type="text"
            class="form-control"
            v-model="form.name"
            placeholder="Name"
        />
        <label>Email address</label>
        <input
            type="email"
            v-model="form.email"
            placeholder="Email"
        />
        <label for="password">Password</label>
        <input
            type="password"
            v-model="form.password"
            placeholder="Password"
        />
        <button type="submit">Register</button>
    </form>
</template>

<script>
import axios from 'axios';

export default {
    name: "Register",
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: ''
            },
            error: null
        };
    },
    methods: {
        register() {
            axios.post('http://localhost:8000/api/register', this.form)
                .then(response => {
                    console.log('Registered successfully:', response.data);
                    this.$router.push({ name: 'Login' });
                })
                .catch(error => {
                    console.error('Error registering:', error.response.data);
                    this.error = 'Error registering: ' + error.response.data.message;
                });
        }
    }
};
</script>
