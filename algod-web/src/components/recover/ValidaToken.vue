<template>
  <v-container fluid>
   <v-layout row wrap>
     <v-flex xs12 sm6 offset-sm3 md4 offset-md4>
       <img src="../../assets/logoAGdarken.png" style="width: 200px; margin: 20px auto; display: block;" />
       <v-card>
         <div v-if="valid !== null">
           <v-card-text v-if="valid === true" class="center">
             <h5 class="green--text text-xs-center">Por favor clique no link abaixo para definir uma nova senha.</h5><br>
             <router-link class="btn mx-3-center" :to="{ path: `/forgot-password/reset/${token}`, params: {token: this.token} }">Criar nova senha</router-link>
           </v-card-text>
           <v-card-text v-else-if="valid === false">
             <h5>Token expirou ou é inválido</h5>
           </v-card-text>
         </div>
         <v-card-actions>
           <router-link class="btn mx-2" to="/register">Cadastre-se</router-link>
           <router-link class="btn mx-2" to="/login">Fazer login</router-link>
           <router-link class="btn mx-2" to="/forgot-password">Recuperar senha</router-link>
         </v-card-actions>
       </v-card>
     </v-flex>
   </v-layout>
 </v-container>
</template>

<script>
  import UserSession from '@/store/UserSession';

  export default {
    name: 'Valida-Token',

    data() {
      return {
        valid: null,
        token: '',
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
          me.token = me.$router.currentRoute.params.token;
        } else {
          me.valid = false;
          me.token = null;
        }
      });
    },
    beforeDestroy() {
      UserSession.off(null, null, this);
    },
    methods: {
      redirect(event) {
        event.preventDefault();
        this.$router.push(`/forgot-password/reset/${this.$router.currentRoute.params.token}`);
      },
    },
  };
</script>
