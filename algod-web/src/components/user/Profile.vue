<template>
  <v-flex sm8 md6>
    <v-card v-if="exists">
      <v-card-text v-if="!!user">
        <v-list>
          <v-list-tile>
            <v-list-tile-avatar id="icon" v-on:click="changePic = !changePic">
              <img :src="user.picture" alt="User" />
            </v-list-tile-avatar>
            <v-list-tile-content class="black--text">
              <div style="font-size: 20px;">{{user.name}}</div>
              <div class="caption">&nbsp;{{user.email}}</div>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>

        <v-divider class="mb-3"></v-divider>

        <v-list>
          <v-list-tile>
            <v-list-tile-content v-if="user.accessLevel === 5">
              Função: Estudante
            </v-list-tile-content>
            <v-list-tile-content v-if="user.accessLevel === 30">
              Função: Professor
            </v-list-tile-content>
            <v-list-tile-content v-if="user.accessLevel === 100">
              Função: Administrador
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-card-text>
      <v-dialog v-model="changePic">
        <v-card>
          <v-card-text>
            <v-text-field
              label="Url"
              v-model="url"
              persistent-hint
              autofocus
            ></v-text-field>
            <v-btn v-on:click="sendPicture($event)">Mudar foto</v-btn>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-card>

    <v-card v-else>
      <v-card-text class="red lighten-2">
        <h5>Perfil não encontrado</h5>
        <router-link :to="{ name: 'Welcome'}" class="btn">Voltar</router-link>
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
      changePic: false,
      url: '',
      exists: false,
    };
  },
  mounted() {
    UserSession.dispatch({
      action: UserSession.ACTION_GET_USER,
      data: this.$router.currentRoute.params.nickname,
    });

    UserSession.on('changePic', () => {
      Toastr.success('Foto alterada');
    }, this);
    UserSession.on('getUser', (response) => {
      this.exists = true;
      this.user = response.data;
    }, this);
    UserSession.on('fail', (args) => {
      if (args.status === 404) {
        this.exists = false;
      }
    }, this);
  },
  beforeDestoy() {
    UserSession.off(null, null, this);
  },
  methods: {
    sendPicture(event) {
      event.preventDefault();
      console.log('kkkk');
      UserSession.dispatch({
        action: UserSession.ACTION_CHANGE_PIC,
        data: {
          user: this.user,
          url: this.url,
        },
      });
      UserSession.dispatch({
        action: UserSession.ACTION_GET_USER,
        data: this.$router.currentRoute.params.nickname,
      });
      this.changePic = !this.changePic;
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
