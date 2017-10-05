<template>
  <v-container fluid>
   <v-layout row wrap>
     <v-flex xs12 sm6 offset-sm3 md4 offset-md4>
       <img src="../../assets/logoAGdarken.png" style="width: 200px; margin: 20px auto; display: block;" />
       <h5 class="headline text-xs-center">Recuperar senha</h5>
       <v-card>
         <v-card-text>
           <form v-on:submit="recover($event)">
             <v-text-field
               label="Email"
               v-model="email"
               autofocus>
             </v-text-field>
             <div class="text-xs-right">
               <v-btn type="submit" primary>Enviar e-mail</v-btn>
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

  import UserSession from '@/store/UserSession';

  export default {
    name: 'Recover-password',

    data() {
      return {
        email: '',
      };
    },

    mounted() {
    },

    beforeDestroy() {
      UserSession.off(null, null, this);
    },

    methods: {
      recover(event) {
        event.preventDefault();
        UserSession.dispatch({
          action: UserSession.ACTION_RECOVER_PASSWORD,
          data: {
            email: this.email,
          },
        });
      },
    },
  };
</script>
