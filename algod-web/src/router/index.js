import Vue from 'vue';
import Router from 'vue-router';

import Welcome from '@/components/Welcome';
import Login from '@/components/user/Login';
import Register from '@/components/user/Register';

import Atividade from '@/components/Atividade';
import SalaDeAula from '@/components/SalaDeAula';
import Curso from '@/components/Cursos';

Vue.use(Router);

export default new Router({
  routes: [{
    path: '/',
    name: 'Bem-vindo',
    component: Welcome,
  }, {
    path: '/login',
    name: 'Login',
    component: Login,
  }, {
    path: '/register',
    name: 'Cadastre-se',
    component: Register,
  }, {
    path: '/activity',
    name: 'Atividades',
    component: Atividade,
  }, {
    path: '/classroom',
    name: 'Sala de Aula',
    component: SalaDeAula,
  }, {
    path: '/courses',
    name: 'Cursos',
    component: Curso,
  }, //{
    //path: '/forgot-password',
    //name: 'Esqueceu sua senha?',
    //component: ForgotPassword,
  //}
  ],
});
