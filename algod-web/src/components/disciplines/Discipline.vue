<template>
  <v-layout column align-center justify-center>
    <v-flex class="text-xs-center">
      <v-card>
        <v-card-text>
          <h1>Algorithm God 2</h1>
          <h5>Uma nova experiência no ensino da programação de computadores</h5>
          <p class="body-2">
            kk eae men
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
      console.log(this.$router.currentRoute.params.shortName);
      const me = this;
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
        data: me.$router.currentRoute.params.shortName,
      });
      DisciplineStore.on('getSubscription', (data) => {
        console.log(data);
        me.subscription = data;
      }, this);
    },
    beforeDestroy() {
      DisciplineStore.off(null, null, this);
    },
  };
</script>
