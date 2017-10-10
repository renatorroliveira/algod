<template>
  <v-app fill-height class="blue-grey lighten-3" v-if="!loading">
    <v-navigation-drawer dark persistent enable-resize-watcher
      :miniVariant="miniVariant"
      v-model="drawer">
      <v-list>
        <v-list-tile dark avatar to="/">
          <v-list-tile-avatar>
            <img src="../assets/logo-50px.png" alt="AlGod Logo" />
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title>AlGod</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-divider class="mb-3"></v-divider>

        <v-list-tile v-for="(item, i) in items" dark :key="i" :to="item.href">
          <v-list-tile-action>
            <v-icon dark v-html="item.icon"></v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title v-text="item.title"></v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile v-if="access === 100 && !loading" dark to="/institution/list">
            <v-list-tile-action>
              <v-icon dark>business</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>Instituições</v-list-tile-title>
            </v-list-tile-content>
        </v-list-tile>

        <v-list-tile v-if="access === 100 && !loading" dark to="/discipline/list">
            <v-list-tile-action>
              <v-icon dark>business</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>Discipline</v-list-tile-title>
            </v-list-tile-content>
        </v-list-tile>

        <v-list-tile dark avatar @click.native="doLogout($event)">
          <v-list-tile-avatar>
            <img src="../assets/close.png" alt="Logout icon" />
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title>
              Logout
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-divider class="mt-3"></v-divider>
        <v-list-tile dark @click.native.stop="miniVariant = !miniVariant">
          <v-list-tile-action>
            <v-icon dark v-html="miniVariant ? 'chevron_right' : 'chevron_left'"></v-icon>
          </v-list-tile-action>
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
    mounted() {
      const me = this;
      UserSession.on('logout', () => {
        Toastr.success('Usuário deslogado');
      }, me);
      if (!UserSession.get('loading') && !UserSession.get('logged')) {
        this.$router.push('/auth/login');
      }
      UserSession.on('loaded', () => {
        if (!UserSession.get('logged')) {
          me.$router.push('/auth/login');
        }
        me.access = UserSession.get('accessLevel');
        me.loading = false;
      }, me);
    },
    data() {
      return {
        loading: UserSession.get('loading'),
        drawer: true,
        access: UserSession.get('accessLevel'),
        items: [{
          icon: 'web',
          title: 'Profile',
          href: '/profile',
        }, {
          icon: 'web',
          title: 'Cursos',
          href: '/courses',
        }, {
          icon: 'web',
          title: 'Sala de Aula',
          href: '/classroom',
        }, {
          icon: 'web',
          title: 'Atividade',
          href: '/activity',
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
