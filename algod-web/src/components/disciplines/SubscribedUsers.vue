<template>
  <v-container grid-list-xl text-xs-center>
    <v-flex sx12>
      <v-card color="blue-grey darken-1 white--text">
        <v-card-text>
          <div class="display-1">Usuários inscritos em {{discipline.name}}</div>
        </v-card-text>
        <v-spacer class="mb-3"></v-spacer>
      </v-card>
    </v-flex>
    <v-layout row wrap>
      <v-flex xs3>
        <v-card>
          <v-card-text>
            dsa
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex xs6 v-if="subscribedUsers.length > 0">
        <v-card v-for="user in subscribedUsers" :key="user.id">
          <v-card-text>
            {{user.name}}
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex xs6 v-else>
        <v-card>
          <v-card-text>
            <h5>A disciplina {{discipline.name}} não possui usuários cadastrados</h5>
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex xs3>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import DisciplineStore from '@/store/Discipline';

export default {
  name: 'Subscribed-Users',
  data() {
    return {
      discipline: [],
      subscribedUsers: [],
    };
  },
  created() {
    DisciplineStore.dispatch({
      action: DisciplineStore.ACTION_GET_DISCIPLINE,
      data: this.$route.params.id,
    });
    DisciplineStore.dispatch({
      action: DisciplineStore.ACTION_GET_SUBSCRIBED_USERS,
      data: this.$route.params.id,
    });
    DisciplineStore.on('getDiscipline', (data) => {
      this.discipline = data;
    }, this);
    DisciplineStore.on('subscribedUsers', (data) => {
      this.subscribedUsers = data;
    }, this);
  },
  beforeDestroy() {
    DisciplineStore.off(null, null, this);
  },
  methods: {
    //
  },
};
</script>
