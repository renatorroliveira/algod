<template>
  <v-container fluid>
   <v-layout row wrap>
     <v-flex xs12 sm6 offset-sm3 md4 offset-md4>
       <img src="../../assets/logoAGdarken.png" style="width: 200px; margin: 20px auto; display: block;" />
       <h5 v-if="valid === true" class="headline text-xs-center">Nova senha</h5>
       <v-card>
         <v-card-text>
           <form v-on:submit="sendPass($event)">
             <v-text-field
               label="Nova senha"
               v-model="password"
               :append-icon="btnToggle ? 'visibility' : 'visibility_off'"
               :append-icon-cb="() => (btnToggle = !btnToggle)"
               :type="btnToggle ? 'text' : 'password'"
               autofocus>
             </v-text-field>
             <v-text-field
               label="Confirme a senha"
               v-model="cpassword"
               :append-icon="btnToggle2 ? 'visibility' : 'visibility_off'"
               :append-icon-cb="() => (btnToggle2 = !btnToggle2)"
               :type="btnToggle2 ? 'text' : 'password'">
             </v-text-field>
             <div class="text-xs-right">
               <v-btn type="submit" primary>Mudar senha</v-btn>
             </div>
           </form>
         </v-card-text>
         <v-card-actions>
           <router-link class="btn mx-2" to="/register">Cadastre-se</router-link>
           <router-link class="btn mx-2" to="/login">Fazer login</router-link>
           <router-link class="btn mx-3" to="/forgot-password">Recuperar senha</router-link>
         </v-card-actions>
       </v-card>
     </v-flex>
   </v-layout>
 </v-container>
</template>

<script>
  import Toastr from 'toastr';
  import UserSession from '@/store/UserSession';

  export default {
    name: 'Reset-password',

    data() {
      return {
        password: '',
        cpassword: '',
        valid: null,
        btnToggle: false,
        btnToggle2: false,
      };
    },

    mounted() {
      const me = this;
      UserSession.dispatch({
        action: UserSession.ACTION_VALIDATE_TOKEN,
        data: {
          token: me.$router.currentRoute.params.token,
        },
      });
      UserSession.on('canResetPass', (response) => {
        if (response.changed.ValidToken === true) {
          me.valid = true;
        } else {
          me.valid = false;
        }
      });
    },

    methods: {
      sendPass(event) {
        event.preventDefault();
        if (this.password === this.cpassword) {
          UserSession.dispatch({
            action: UserSession.ACTION_NEW_PASSWORD,
            data: {
              password: this.password,
              token: this.$router.currentRoute.params.token,
            },
          });
        } else {
          Toastr.error('As senhas não combinam');
        }
      },
    },
  };
</script>
