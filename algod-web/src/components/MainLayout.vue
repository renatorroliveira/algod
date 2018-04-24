<template>
  <v-app v-if="!loading">

    <v-navigation-drawer
      app
      fixed
      :clipped="$vuetify.breakpoint.width > 1264"
      v-model="drawer"
      disable-route-watcher
      v-if="!!user"
    >
      <v-list v-if="!!user">
        <span style="display:none;">{{update}}</span>
        <v-list-tile avatar :to="`/user/profile/${user.nickname}`" v-on:click="drawer = false">
          <v-list-tile-avatar>
            <img :src="user.picture" alt="User" />
          </v-list-tile-avatar>
          <v-list-tile-content class="black--text">
            <div class="body-1">{{user.name}}</div>
            <div class="caption">{{user.email}}</div>
          </v-list-tile-content>
        </v-list-tile>

        <v-divider class="mb-3"></v-divider>

        <v-list-tile avatar v-on:click="drawer = false" to="/">
          <v-list-tile-avatar>
            <v-icon>home</v-icon>
          </v-list-tile-avatar>
          <v-list-tile-content class="black--text">
            <v-list-tile-title>Início</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-group v-for="(item, i) in items" :key="i" v-if="(!loading && items.length > 0) && user.accessLevel > item.access">
          <v-list-tile avatar slot="item">
            <v-list-tile-avatar>
              <v-icon v-html="item.icon"></v-icon>
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title v-text="item.title"></v-list-tile-title>
            </v-list-tile-content>
            <v-icon dark>keyboard_arrow_down</v-icon>
          </v-list-tile>
          <v-list-tile v-for="(child, j) in item.children" :key="j" :to="child.href" v-on:click="drawer = false">
            <v-list-tile-content>
              <v-list-tile-title v-text="child.title"></v-list-tile-title>
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
          <router-view :key="$route.path"></router-view>
        </v-layout>
      </v-container>
    </v-content>
    <v-footer app>
      <span>Copyrights &copy; Algod 2017-2018</span>
    </v-footer>
  </v-app>
</template>

<script>
  import UserSession from '@/store/UserSession';

  export default {
    data: () => ({
      title: 'Bem-vindo',
      loading: UserSession.get('loading'),
      user: UserSession.get('user'),
      drawer: false,
      items: [],
      update: 'k',
    }),
    created() {
      if (!UserSession.get('loading') && !UserSession.get('logged')) {
        this.$router.push('/auth/login');
      }
      this.loading = UserSession.get('loading');

      UserSession.on('loaded', () => {
        if (!UserSession.get('logged')) {
          this.$router.push('/auth/login');
        }
        this.user = UserSession.get('user');
        console.log(this.user);

        this.loading = false;
        this.update = 'kk';
      }, this);

      this.items.push({
        icon: 'settings',
        title: 'Sistema',
        access: 30,
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
      });

      UserSession.on('logout', () => {
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
