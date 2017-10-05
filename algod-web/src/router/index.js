import Vue from 'vue';
import Router from 'vue-router';

import Welcome from '@/components/Welcome';

import Login from '@/components/user/Login';
import Register from '@/components/user/Register';
import Profile from '@/components/user/Profile';

import InstitutionRegister from '@/components/institution/Register';

import ForgotPassword from '@/components/recover/ForgotPassword';
import ResetPassword from '@/components/recover/ResetPassword';
import RecoverPassword from '@/components/recover/RecoverPassword';

import Atividade from '@/components/Atividade';
import Curso from '@/components/Cursos';
import MainLayout from '@/components/MainLayout';
import SalaDeAula from '@/components/SalaDeAula';

Vue.use(Router);

export default new Router({
  routes: [{
    path: '/login',
    name: 'Login',
    component: Login,
  }, {
    path: '/register',
    name: 'Cadastre-se',
    component: Register,
  }, {
    path: '/forgot-password',
    name: 'Recuperar a senha',
    component: ForgotPassword,
  }, {
    path: '/forgot-password/confirm',
    name: 'Confirmar no email',
    component: RecoverPassword,
  }, {
    path: '/forgot-password/reset/:token',
    name: 'Nova senha',
    component: ResetPassword,
  }, {
    path: '/',
    name: 'Main',
    component: MainLayout,
    children: [{
      path: '',
      name: 'Bem-vindo',
      component: Welcome,
    }, {
      path: 'activity',
      name: 'Atividades',
      component: Atividade,
    }, {
      path: 'classroom',
      name: 'SalaDeAula',
      component: SalaDeAula,
    }, {
      path: 'profile',
      name: 'Profile',
      component: Profile,
    }, {
      path: 'courses',
      name: 'Cursos',
      component: Curso,
    }, {
      path: 'new-institution',
      name: 'New-institution',
      component: InstitutionRegister,
    }],
  }],
});
