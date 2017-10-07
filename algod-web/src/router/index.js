import Vue from 'vue';
import Router from 'vue-router';

import Welcome from '@/components/Welcome';

import Profile from '@/components/user/Profile';

import NewInstitution from '@/components/institution/Register';
import ListInstitutions from '@/components/institution/ListInstitutions';

import Login from '@/components/auth/Login';
import Register from '@/components/auth/Register';
import ForgotPassword from '@/components/auth/ForgotPassword';
import ResetPassword from '@/components/auth/ResetPassword';
import RecoverPassword from '@/components/auth/RecoverPassword';

import Atividade from '@/components/Atividade';
import Curso from '@/components/Cursos';
import SalaDeAula from '@/components/SalaDeAula';

import AuthLayout from '@/components/AuthLayout';
import MainLayout from '@/components/MainLayout';

Vue.use(Router);

export default new Router({
  routes: [{
    path: '/auth',
    component: AuthLayout,
    children: [{
      path: 'login',
      name: 'Login',
      component: Login,
    }, {
      path: 'register',
      name: 'Cadastre-se',
      component: Register,
    }, {
      path: 'forgot-password',
      name: 'Recuperar a senha',
      component: ForgotPassword,
    }, {
      path: 'forgot-password/confirm',
      name: 'Confirmar no email',
      component: RecoverPassword,
    }, {
      path: 'forgot-password/reset/:token',
      name: 'Nova senha',
      component: ResetPassword,
    }],
  }, {
    path: '/',
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
      path: 'list',
      name: 'List-institutions',
      component: ListInstitutions,
    }, {
      path: 'add',
      name: 'add-institution',
      component: NewInstitution,
    }],
  }],
});
