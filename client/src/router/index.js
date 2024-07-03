import Vue from 'vue';
import Router from 'vue-router';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Users from '../views/Users.vue';
import store from '../store';

Vue.use(Router);

const router = new Router({
    mode: 'history', // Usa o modo de histórico HTML5
    routes: [
        {
            path: '/',
            name: 'Home',
            component: Home,
            meta: { requiresAuth: true }
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
        },
        {
            path: '/register',
            name: 'Register',
            component: Register,
        },
        {
            path: '/users',
            name: 'Users',
            component: Users,
            meta: { requiresAuth: true }
        },
    ],
});

// Guarda de rota para verificar a autenticação
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // Esta rota requer autenticação
        if (!store.getters.loggedIn) {
            // Se não estiver autenticado, redireciona para a página de login
            next({
                path: '/login',
                query: { redirect: to.fullPath } // Salva o caminho para redirecionar depois do login
            });
        } else {
            next(); // Prossegue para a rota
        }
    } else {
        next(); // Prossegue para a rota
    }
});

export default router;
