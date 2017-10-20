<template>
  <v-container>
    <v-layout row wrap>
      <v-flex xs8 offset-xs4>
        <v-card>
          <v-card-text>
            <img id="profile_picture" class="center" :src="user.picture">
            <h5>Nome: {{user.name}}</h5>
            <h5>Email: {{user.email}}</h5>
            <h5>Telefone: {{user.phone}}</h5>
            <h5>Criation date: {{user.creation}}</h5>
            <h5>Access Level: {{user.accessLevel}}</h5>
            <h5>Active: {{user.active}}</h5>
            <h5>Deleted: {{user.deleted}}</h5>
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
    </v-layout>
  </v-container>
</template>

<script>
import Toastr from 'toastr';
import UserSession from '@/store/UserSession';

export default {
  name: 'Profile',
  data() {
    return {
      user: UserSession.get('user'),
      toggle: false,
      url: '',
    };
  },
  mounted() {
    const me = this;
    UserSession.on('loaded', () => {
      me.user = UserSession.get('user');
    }, me);
    UserSession.on('changePic', () => {
      Toastr.success('Foto alterada');
    }, me);
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
