<template>
  <v-flex sm8 md6>
    <v-card>
      <v-card-text>
        <h5 class="headline text-xs-center">Configurar Sua Task</h5>
        <form v-on:submit="add($event)">
          <v-text-field
            label="Titulo"
            v-model="titulo"
            persistent-hint
            autofocus
          ></v-text-field>

          <div class="text-xs-right">
            <v-btn type="submit" color="secondary">Confirmar</v-btn>
          </div>
        </form>
      </v-card-text>
      <v-card-actions>
      </v-card-actions>
    </v-card>
  </v-flex>
</template>

<script>

  export default {
    //
  };
</script>
