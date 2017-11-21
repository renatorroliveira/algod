<template>
  <v-layout column align-center justify-center>
    <v-flex class="text-xs-center">
      <v-card v-if="!!subscription">
        <v-card-text>
          <h4>{{discipline.name}}</h4>
          <p class="body-2">
            Inscrito
          </p>
        </v-card-text>
      </v-card>
      <v-card v-else-if="!!discipline">
        <v-card-text>
          <h4>{{discipline.name}}</h4>
          <p class="body-2">
            Inscreva-se
          </p>
        </v-card-text>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
  import DisciplineStore from '@/store/Discipline';

  export default {
    data() {
      return {
        subscription: null,
        discipline: [],
      };
    },
    created() {
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
        data: this.$router.currentRoute.params.id,
      });

      DisciplineStore.on('getSubscription', (data) => {
        if (typeof data === 'undefined') {
          DisciplineStore.dispatch({
            action: DisciplineStore.ACTION_GET_DISCIPLINE,
            data: this.$router.currentRoute.params.id,
          });
        } else {
          console.log('Go:', data);
          this.subscription = data;
          this.discipline = data.discipline;
        }
      }, this);
      DisciplineStore.on('getDiscipline', (data) => {
        this.discipline = data;
      }, this);
    },
    beforeDestroy() {
      DisciplineStore.off(null, null, this);
    },
  };
</script>
