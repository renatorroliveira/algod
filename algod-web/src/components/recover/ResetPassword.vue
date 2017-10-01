<template>
  <v-container fluid>
   <v-layout row wrap>
     <v-flex xs12 sm6 offset-sm3 md4 offset-md4>
       <img src="../../assets/logoAGdarken.png" style="width: 200px; margin: 20px auto; display: block;" />
       <h5 class="headline text-xs-center">Nova senha</h5>
       <v-card>
         <v-card-text>
           <form v-on:submit="sendPass($event)">
             <v-text-field
               label="Nova senha"
               v-model="password"
               autofocus>
             </v-text-field>
             <v-text-field
               label="Confirme a senha"
               v-model="cpassword">
             </v-text-field>
             <div class="text-xs-right">
               <v-btn type="submit" primary>Mudar senha</v-btn>
             </div>
           </form>
         </v-card-text>
         <v-card-actions>
           <router-link class="btn mx-3" to="/register">Cadastre-se</router-link>
           <router-link class="btn mx-3" to="/login">Fazer login</router-link>
         </v-card-actions>
       </v-card>
     </v-flex>
   </v-layout>
 </v-container>
</template>

<script>
  /* eslint linebreak-style: ["error", "windows"] */
  import Toastr from 'toastr';
  import UserSession from '@/store/UserSession';

  export default {
    name: 'Reset-password',

    data() {
      return {
        password: '',
        cpassword: '',
      };
    },

    mounted() {
      const me = this;
      console.log(me.$router);
    },

    beforeDestroy() {
      UserSession.off(null, null, this);
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
