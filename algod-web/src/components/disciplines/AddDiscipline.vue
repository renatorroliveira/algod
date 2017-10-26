<template>
  <v-flex sm8 md6>
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
          <v-text-field
            label="Short name"
            v-model="shortName"
            persistent-hint
          ></v-text-field>
          <v-text-field
            label="Acess Key"
            v-model="accessKey"
            persistent-hint
          ></v-text-field>
          <v-select
            v-bind:items="categorys"
            v-model="e1"
            label="Categoria da discplina"
            single-line
            bottom
          ></v-select>
          <v-select
            v-bind:items="institutions"
            v-model="e2"
            label="Institution"
            single-line
            bottom
          ></v-select>
          <div class="text-xs-right">
            <v-btn type="submit" color="secondary">Registrar</v-btn>
          </div>
        </form>
      </v-card-text>
      <v-btn class="btn mx-3" to="/discipline/list">Lista de Disciplines</v-btn>
      <v-card-actions>
      </v-card-actions>
    </v-card>
  </v-flex>
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
      accessKey: '',
      shortName: '',
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
    }, this);
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
    }, this);
    DisciplineStore.on(DisciplineStore.ACTION_LIST, (data) => {
      for (let i = 0; i < data.data.length; i += 1) {
        me.categorys.push({
          text: data.data[i].name,
          id: data.data[i].id,
        });
      }
    }, this);
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
            accessKey: this.accessKey,
            shortName: this.shortName,
          },
          category: {
            // id: this.e1.id,
            id: 1,
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
