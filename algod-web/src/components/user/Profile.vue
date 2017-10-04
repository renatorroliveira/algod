<template>
 <v-container fluid>
  <v-card>
    <v-card-text>
      <h5>Nome: {{user[0].name}}</h5>
      <h5>Email: {{user[0].email}}</h5>
      <h5>Telefone: {{user[0].phone}}</h5>
      <h5>Criation date: {{user[0].creation}}</h5>
    </v-card-text>
  </v-card>
 </v-container>
</template>

<script>
import UserSession from '@/store/UserSession';

export default {
  name: 'Profile',
  data() {
    return {
      user: [],
    };
  },
  mounted() {
    const me = this;
    UserSession.dispatch({
      action: UserSession.ACTION_GET_LOGGED_USER,
    });
    UserSession.on('getLoggedUser', (response) => {
      me.user.push(response.attributes.user);
      console.log(me.user);
    });
  },
  beforeDestoy() {
    UserSession.off(null, null, this);
  },
};
</script>
