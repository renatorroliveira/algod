<template>
  <v-container fluid>
   <v-layout row wrap>
     <v-flex xs12 sm6 offset-sm3 md4 offset-md4>
       <img src="../../assets/logoAGdarken.png" style="width: 200px; margin: 20px auto; display: block;" />
       <h5 class="headline text-xs-center">Confirme no e-mail</h5>
       <v-card>
         <v-card-text>
           <h5 class="text-xs-center green--text">Por favor, confirme o link enviado em seu e-mail</h5>
         </v-card-text>
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

  export default {
    name: 'Tela-de-alerta',

    data() {
      return {
      };
    },
  };
</script>
