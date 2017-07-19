<template>
  <v-app fill-height>
    <v-navigation-drawer dark persistent enable-resize-watcher
      :miniVariant="miniVariant"
      v-model="drawer">
      <v-list>
        <v-list-tile dark avatar to="/">
          <v-list-tile-avatar>
            <img src="./assets/logo-50px.png" alt="AlGod Logo" />
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

    <v-toolbar fixed>
      <v-toolbar-side-icon @click.native.stop="drawer = !drawer" light></v-toolbar-side-icon>
      <v-toolbar-title>{{$route.name}}</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>

    <main>
      <router-view></router-view>
    </main>

  </v-app>
</template>

<script>
  export default {
    data() {
      return {
        drawer: true,
        items: [{
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
  };
</script>

<style lang="stylus">
  @import './stylus/main'
</style>
