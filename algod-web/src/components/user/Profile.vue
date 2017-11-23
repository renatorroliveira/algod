<template>
  <v-flex sm8 md6>
    <v-card>
      <v-card-text>
        <img id="profile_picture" class="center" :src="user.picture">
        <div class="body-2">Nome: {{user.name}}</div>
        <div class="body-2">Apelido: {{user.nickname}}</div>
        <div class="body-2">Email: {{user.email}}</div>
        <div class="body-2">Telefone: {{user.phone}}</div>
        <div class="body-2">Criation date: {{user.creation}}</div>
        <div class="body-2">Access Level: {{user.accessLevel}}</div>
        <div class="body-2">Active: {{user.active}}</div>
        <div class="body-2">Deleted: {{user.deleted}}</div>
        <v-btn dark @click.native="toggle = !toggle" v-model="toggle">Change pic</v-btn>
        <form v-on:submit="changePic($event)" v-if="toggle">
          <v-text-field
            label="Url"
            v-model="url"
            persistent-hint
            autofocus
          ></v-text-field>
          <div class="text-xs-right">
            <v-btn type="submit" color="primary">Mudar foto</v-btn>
          </div>
        </form>
      </v-card-text>
    </v-card>
  </v-flex>
</template>

<script>
import Toastr from 'toastr';
import UserSession from '@/store/UserSession';

export default {
  name: 'Profile',
  data() {
    return {
      user: [],
      toggle: false,
      url: '',
    };
  },
  mounted() {
    UserSession.on('loaded', () => {
      UserSession.dispatch({
        action: UserSession.ACTION_GET_USER,
        data: this.$router.currentRoute.params.nickname,
      });
    }, this);
    UserSession.on('changePic', () => {
      Toastr.success('Foto alterada');
    }, this);
    UserSession.on('getUser', (response) => {
      console.log(response);
      this.user = response;
    }, this);
  },
  beforeDestoy() {
    UserSession.off(null, null, this);
  },
  methods: {
    changePic(event) {
      event.preventDefault();
      UserSession.dispatch({
        action: UserSession.ACTION_PICTURE,
        data: {
          user: this.user,
          url: this.url,
        },
      });
    },
  },
};
</script>

<style scoped>
  img#profile_picture {
    width: 120px;
    border: 3px solid black;
    padding: auto;
    margin: auto;
  }
</style>
