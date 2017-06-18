import Vue from 'vue'
import Router from 'vue-router'

import Welcome from '@/components/Welcome'
import Login from '@/components/Login'
import Register from '@/components/user/Register'

import Atividade from '@/components/Atividade'
import Disciplina from '@/components/Disciplina'
import Curso from '@/components/Curso'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Bem-vindo',
      component: Welcome
    },{
      path: '/login',
      name: 'Login',
      component: Login
    },{
      path: '/register',
      name: 'Cadastre-se',
      component: Register
    },{
      path: '/atividade',
      name: 'Atividade',
      component: Atividade
    },{
      path: '/disciplina',
      name: 'Disciplina',
      component: Disciplina
    },{
      path: '/curso',
      name: 'Curso',
      component: Curso
    }
  ]
})
