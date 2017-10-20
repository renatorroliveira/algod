<template>
  <main>
  <v-container>
    <v-layout row wrap>
      <v-flex xs8 offset-xs4>
        <v-card>
          <v-card-text>
            <h5 class="headline text-xs-center">Adicionar disciplina</h5>
            <form v-on:submit="add($event)">
              <v-text-field
                label="Nome"
                v-model="name"
                persistent-hint
                autofocus
              ></v-text-field>
              <v-select
                v-bind:items="categorys"
                :key="e1"
                label="Categoria da discplina"
                single-line
                bottom
              ></v-select>
              <v-select
                v-bind:items="institutions"
                :key="e2"
                label="Institution"
                single-line
                bottom
              ></v-select>
              <div class="text-xs-right">
                <v-btn type="submit" primary>Registrar</v-btn>
              </div>
            </form>
          </v-card-text>
          <v-btn dark class="btn mx-3" to="/discipline/list">Lista de Disciplines</v-btn>
          <v-card-actions>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</main>
</template>

<script>
import Toastr from 'toastr';
import DisciplineStore from '@/store/Discipline';
import InstitutionStore from '@/store/Institution';

export default {
  name: 'New-Discipline',
  data() {
    return {
      name: '',
      e1: null,
      e2: null,
      categorys: [],
      institutions: [],
    };
  },
  mounted() {
    const me = this;
    DisciplineStore.on('new', () => {
      Toastr.success('Disciplina adicionada');
      me.$router.push('/discipline/list');
    }, me);
    InstitutionStore.dispatch({
      action: InstitutionStore.ACTION_LIST,
    });
    DisciplineStore.dispatch({
      action: DisciplineStore.ACTION_LIST,
    });
    InstitutionStore.on(InstitutionStore.ACTION_LIST, (data) => {
      for (let i = 0; i < data.data.length; i += 1) {
        me.institutions.push({
          text: data.data[i].name,
          id: data.data[i].id,
        });
      }
    }, me);
    DisciplineStore.on(DisciplineStore.ACTION_LIST, (data) => {
      for (let i = 0; i < data.data.length; i += 1) {
        me.categorys.push({
          text: data.data[i].name,
          id: data.data[i].id,
        });
      }
    }, me);
  },
  beforeDestroy() {
    DisciplineStore.off(null, null, this);
    InstitutionStore.off(null, null, this);
  },
  methods: {
    add(event) {
      event.preventDefault();
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_ADD,
        data: {
          discipline: {
            name: this.name,
          },
          category: {
            id: this.e1.id,
          },
          institution: {
            id: this.e2.id,
          },
        },
      });
    },
  },
};
</script>
