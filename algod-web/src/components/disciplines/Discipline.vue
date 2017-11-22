<template>
  <v-layout column align-center justify-center>
    <v-flex class="text-xs-center">
      <v-card v-if="!!subscription">
        <v-card-text>
          <h4>{{discipline.name}}</h4>
          <p class="body-2">
            <p>Inscrito</p>
            <v-btn v-on:click="doUnsubscribe($event)">Unsubscribe</v-btn>
          </p>
        </v-card-text>
      </v-card>
      <v-card v-else-if="!!discipline">
        <v-card-text>
          <h4>{{discipline.name}}</h4>
          <p class="body-2">
            <form class="" v-on:submit="doSubscribe($event)">
              <v-text-field
                label="Senha"
                v-model="accessKey"
                autofocus>
              </v-text-field>
              <v-btn type="submit">Inscrever-se</v-btn>
            </form>
          </p>
        </v-card-text>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
  import DisciplineStore from '@/store/Discipline';
  import Toastr from 'toastr';

  export default {
    data() {
      return {
        subscription: null,
        discipline: [],
        accessKey: '',
      };
    },
    created() {
      DisciplineStore.on('fail', (err) => {
        Toastr.error(err.responseJSON.message);
      }, this);
      DisciplineStore.on('doSubscribe', () => {
        location.reload();
      }, this);
      DisciplineStore.on('doUnsubscribe', () => {
        location.reload();
      }, this);
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
    methods: {
      doSubscribe(event) {
        event.preventDefault();
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_SUBSCRIBE,
          data: {
            id: this.$router.currentRoute.params.id,
            accessKey: this.accessKey,
          },
        });
      },
      doUnsubscribe(event) {
        event.preventDefault();
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_UNSUBSCRIBE,
          data: this.discipline,
        });
      },
    },
  };
</script>
