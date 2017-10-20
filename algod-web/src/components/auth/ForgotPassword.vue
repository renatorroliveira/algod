<template>
  <main>
   <img src="../../assets/logoAGdarken.png" style="width: 200px; margin: 20px auto; display: block;" />
   <h5 class="headline text-xs-center">Recuperar senha</h5>
   <v-card>
     <v-card-text>
       <h5 class="text-xs-center">insira seu e-mail para seguir as instruções de recuperação de senha.</h5>
       <form v-on:submit="recover($event)">
         <v-text-field
           label="Email"
           v-model="email"
           autofocus>
         </v-text-field>
         <div class="text-xs-right">
           <v-btn type="submit" color="primary">Enviar e-mail</v-btn>
         </div>
       </form>
     </v-card-text>
     <v-card-actions>
       <router-link class="btn mx-3 black--text" to="/auth/register">Cadastre-se</router-link>
       <router-link class="btn mx-3 black--text" to="/auth/login">Fazer login</router-link>
     </v-card-actions>
   </v-card>
 </main>
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
        this.$router.push('/auth/forgot-password/confirm');
      },
    },
  };
</script>
