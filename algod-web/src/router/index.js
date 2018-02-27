import Vue from 'vue';
import Router from 'vue-router';

import Welcome from '@/components/Welcome';

import Profile from '@/components/user/Profile';
import ListUsers from '@/components/user/List';

import NewInstitution from '@/components/institution/AddInstitution';
import ListInstitutions from '@/components/institution/ListInstitutions';

import ListDisciplines from '@/components/disciplines/ListDisciplines';
import NewDiscipline from '@/components/disciplines/AddDiscipline';
import Discipline from '@/components/disciplines/Discipline';
import DisciplineUsers from '@/components/disciplines/DisciplineUsers';

import Login from '@/components/auth/Login';
import Register from '@/components/auth/Register';
import ForgotPassword from '@/components/auth/ForgotPassword';
import ResetPassword from '@/components/auth/ResetPassword';
import RecoverPassword from '@/components/auth/RecoverPassword';

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
      name: 'Welcome',
      component: Welcome,
    }, {
      path: 'user/profile/:nickname',
      name: 'Perfil',
      component: Profile,
    }, {
      path: 'user/list',
      name: 'Lista de usuários',
      component: ListUsers,
    }, {
      path: 'institution/list',
      name: 'Lista de instituições',
      component: ListInstitutions,
    }, {
      path: 'institution/add',
      name: 'Adicionar instituição',
      component: NewInstitution,
    }, {
      path: 'discipline/list',
      name: 'Lista de disciplinas',
      component: ListDisciplines,
    }, {
      path: 'discipline/add',
      name: 'Adicionar disciplina',
      component: NewDiscipline,
    }, {
      path: 'discipline/:id',
      name: 'Disciplina',
      component: Discipline,
    }, {
      path: 'discipline/:id/users',
      name: 'Usuários inscritos',
      component: DisciplineUsers,
    }],
  }],
});
