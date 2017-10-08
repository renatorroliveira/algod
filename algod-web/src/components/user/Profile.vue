<template>
  <v-container>
    <v-layout row wrap>
      <v-flex xs8 offset-xs2>
        <v-card>
          <v-card-text>
            <img id="profile_picture" class="center" v-bind:src="user[0].picture">
            <h5>Nome: {{user[0].name}}</h5>
            <h5>Email: {{user[0].email}}</h5>
            <h5>Telefone: {{user[0].phone}}</h5>
            <h5>Criation date: {{user[0].creation}}</h5>
            <h5>Access Level: {{user[0].accessLevel}}</h5>
            <h5>Active: {{user[0].active}}</h5>
            <h5>Deleted: {{user[0].deleted}}</h5>
            <v-btn dark @click.native="toggle = !toggle" v-model="toggle">Change pic</v-btn>
            <form v-on:submit="changePic($event)" v-if="toggle">
              <v-text-field
                label="Url"
                v-model="url"
                persistent-hint
                autofocus
              ></v-text-field>
              <div class="text-xs-right">
                <v-btn type="submit" primary>Mudar foto</v-btn>
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
      user: [],
      toggle: false,
      url: '',
    };
  },
  mounted() {
    const me = this;
    UserSession.dispatch({
      action: UserSession.ACTION_REFRESH,
    });
    UserSession.on('changePic', () => {
      Toastr.success('Foto alterada');
    }, me);
    UserSession.on('login', (response) => {
      me.user.push(response.attributes.user);
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
          user: this.user[0],
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
