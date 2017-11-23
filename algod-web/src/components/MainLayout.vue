<template>
  <v-app v-if="!loading">

    <v-navigation-drawer
      app
      fixed
      :clipped="$vuetify.breakpoint.width > 1264"
      v-model="drawer"
    >
      <v-list>
        <v-list-tile avatar v-if="!!user" :to="`/user/profile/${user.nickname}`">
          <v-list-tile-avatar>
            <img v-if="!!user.picture" :src="user.picture" alt="User" />
            <v-icon v-else>account_circle</v-icon>
          </v-list-tile-avatar>
          <v-list-tile-content class="black--text">
            <div class="body-1">{{user.name}}</div>
            <div class="caption">{{user.email}}</div>
          </v-list-tile-content>
        </v-list-tile>

        <v-divider class="mb-3"></v-divider>

        <v-list-tile avatar to="/">
          <v-list-tile-avatar>
            <v-icon>home</v-icon>
          </v-list-tile-avatar>
          <v-list-tile-content class="black--text">
            <v-list-tile-title>Início</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-group v-for="(item, i) in items" :key="i" v-if="item.access <= accessLevel">
          <v-list-tile slot="item">
            <v-list-tile-avatar>
              <v-icon v-html="item.icon"></v-icon>
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title v-text="item.title"></v-list-tile-title>
            </v-list-tile-content>
            <v-icon dark>keyboard_arrow_down</v-icon>
          </v-list-tile>
          <v-list-tile v-for="(child, j) in item.children" :key="j" :to="child.href">
            <v-list-tile-content>
              <v-list-tile-title v-text="child.title"></v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list-group>

        <v-list-tile dark avatar v-on:click="doLogout($event)">
          <v-list-tile-avatar>
            &nbsp;<v-icon dark>power_settings_new</v-icon>
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title>
              Sair
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

      </v-list>
    </v-navigation-drawer>

    <v-toolbar
      app
      color="blue-grey darken-3"
      dark
      dense
      fixed
      clipped-left>
        <v-toolbar-side-icon v-on:click="drawer = !drawer"></v-toolbar-side-icon>
        <v-toolbar-title>{{$route.name}}</v-toolbar-title>
        <v-spacer></v-spacer>
    </v-toolbar>

    <v-content class="grey lighten-3">
      <v-container fluid fill-height>
        <v-layout justify-center align-center>
          <router-view></router-view>
        </v-layout>
      </v-container>
    </v-content>
    <v-footer app>
      <span>&copy; 2017</span>
    </v-footer>
  </v-app>
  <div v-else></div>
</template>

<script>
  import Toastr from 'toastr';
  import UserSession from '@/store/UserSession';

  export default {
    data() {
      return {
        loading: UserSession.get('loading'),
        accessLevel: UserSession.get('accessLevel'),
        drawer: true,
        user: UserSession.get('user'),
        items: [{
          icon: 'supervisor_account',
          title: 'Usuários',
          access: 0,
          children: [{
            title: 'Perfil',
            href: `/user/profile/${this.user.nickname}`,
          }],
        }, {
          icon: 'settings',
          title: 'Sistema',
          access: 100,
          children: [{
            title: 'Instituições',
            href: '/institution/list',
          }, {
            title: 'Disciplinas',
            href: '/discipline/list',
          }, {
            title: 'Usuários',
            href: '/user/list',
          }],
        }],
        title: 'Bem-vindo',
      };
    },
    created() {
      if (!UserSession.get('loading') && !UserSession.get('logged')) {
        this.$router.push('/auth/login');
      }
    },
    mounted() {
      UserSession.on('loaded', () => {
        if (!UserSession.get('logged')) {
          this.$router.push('/auth/login');
        }
        this.user = UserSession.get('user');
        if (this.user != null) {
          this.accessLevel = this.user.accessLevel;
        }
        this.loading = false;
      }, this);
      UserSession.on('logout', () => {
        Toastr.success('Usuário deslogado');
        this.$router.push('/auth/login');
      }, this);
    },
    updated() {
      // console.log(this.user);
    },
    methods: {
      doLogout(event) {
        event.preventDefault();
        UserSession.dispatch({
          action: UserSession.ACTION_LOGOUT,
        });
      },
    },
    beforeDestroy() {
      UserSession.off(null, null, this);
    },
  };
</script>
