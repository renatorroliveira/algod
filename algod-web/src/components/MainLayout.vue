<template>
  <v-app fill-height class="blue-grey lighten-5" v-if="!loading">
    <v-navigation-drawer dark persistent enable-resize-watcher
      :miniVariant="miniVariant"
      v-model="drawer">
      <v-list>
        <v-list-tile dark avatar to="/">
          <v-list-tile-avatar>
            <img v-if="!!user.picture" :src="user.picture" alt="User" />
            <v-icon v-else dark>account_circle</v-icon>
          </v-list-tile-avatar>
          <v-list-tile-content>
            <div class="body-1 white--text">{{user.name}}</div>
            <div class="caption white--text">{{user.email}}</div>
          </v-list-tile-content>
        </v-list-tile>

        <v-divider class="mb-3"></v-divider>

        <v-list-group v-for="(item, i) in items" :key="i">
          <v-list-tile dark slot="item">
            <v-list-tile-avatar dark>
              <v-icon dark v-html="item.icon"></v-icon>
            </v-list-tile-avatar>
            <v-list-tile-content dark>
              <v-list-tile-title dark v-text="item.title"></v-list-tile-title>
            </v-list-tile-content>
            <v-list-action dark>
              <v-icon dark>keyboard_arrow_down</v-icon>
            </v-list-action>
          </v-list-tile>
          <v-list-tile dark v-for="(child, j) in item.children" :key="j" :to="child.href">
            <v-list-tile-content dark>
              <v-list-tile-title dark v-text="child.title"></v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list-group>

        <v-list-tile dark avatar v-on:click="doLogout($event)">
          <v-list-tile-avatar>
            <v-icon dark>power_settings_new</v-icon>
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title>
              Sair
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-divider class="mt-3"></v-divider>

        <v-list-tile avatar dark @click.native.stop="miniVariant = !miniVariant">
          <v-list-tile-avatar>
            <v-icon dark v-html="miniVariant ? 'chevron_right' : 'chevron_left'"></v-icon>
          </v-list-tile-avatar>
          <v-list-tile-content>
            Recolher menu
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>

    <v-toolbar fixed dark>
      <v-toolbar-side-icon @click.native.stop="drawer = !drawer" light></v-toolbar-side-icon>
      <v-toolbar-title>{{$route.name}}</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>

    <main>
      <router-view></router-view>
    </main>

  </v-app>
  <div v-else></div>
</template>

<script>
  import Toastr from 'toastr';
  import UserSession from '../store/UserSession';

  export default {
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
        this.loading = false;
      }, this);
      UserSession.on('logout', () => {
        Toastr.success('Usuário deslogado');
      }, this);
    },
    updated() {
      console.log(this.user);
    },
    data() {
      return {
        loading: UserSession.get('loading'),
        drawer: true,
        user: UserSession.get('user'),
        items: [{
          icon: 'supervisor_account',
          title: 'Usuários',
          children: [{
            title: 'Perfil',
            href: '/profile',
          }],
        }, {
          icon: 'settings',
          title: 'Sistema',
          children: [{
            title: 'Instituições',
            href: '/institution/list',
          }, {
            title: 'Disciplinas',
            href: '/discipline/list',
          }],
        }],
        miniVariant: false,
        title: 'Bem-vindo',
      };
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
