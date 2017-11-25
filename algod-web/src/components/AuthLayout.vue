<template>
  <v-container fluid>
    <v-layout row wrap v-if="!loading">
      <v-flex xs12 sm6 offset-sm3 md4 offset-md4>
        <router-view></router-view>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import UserSession from '@/store/UserSession';

  export default {
    mounted() {
      if (!UserSession.get('loading') && UserSession.get('logged')) {
        this.$router.push('/');
      }
      UserSession.on('loaded', () => {
        if (UserSession.get('logged')) {
          this.$router.push('/');
        }
        this.loading = false;
      }, this);
    },
    data() {
      return {
        name: 'Bem-vindo',
        loading: UserSession.get('loading'),
      };
    },
    beforeDestroy() {
      UserSession.off(null, null, this);
    },
  };
</script>
