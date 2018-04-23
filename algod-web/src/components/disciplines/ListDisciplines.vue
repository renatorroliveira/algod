<template>
  <v-flex xs12>
    <v-card>
      <v-card-title>
        Disciplinas
        <v-spacer></v-spacer>
        <v-text-field
          append-icon="search"
          label="Procurar pelo nome"
          single-line
          hide-details
          v-model="search"
        ></v-text-field>
      </v-card-title>
      <v-card-text>
        <v-data-table
          v-if="list"
          v-model="selected"
          :headers="headers"
          :items="list"
          :search="search"
          select-all
          no-data-text="Nenhuma disciplina para esta Instituição"
          rows-per-page-text="Disciplinas por página"
          v-bind:pagination.sync="pagination"
          item-key="item.id"
          class="elevation-1">
          <template slot="headers" slot-scope="props">
            <tr>
              <th>
                <v-checkbox
                  color="black"
                  hide-details
                  @click.native="toggleAll"
                  :input-value="props.all"
                  :indeterminate="props.indeterminate">
                </v-checkbox>
              </th>
              <th v-for="header in props.headers" :key="header.text"
                :class="['column sortable', pagination.descending ? 'asc' : 'desc', header.value === pagination.sortBy ? 'active' : '']"
                @click="changeSort(header.value)">
                <v-icon>arrow_upward</v-icon>
                {{ header.text }}
              </th>
            </tr>
          </template>
          <template slot="headerCell" slot-scope="props">
            <span v-tooltip:bottom="{ 'html': props.header.text }">
              {{ props.header.text }}
            </span>
          </template>
          <template slot="items" slot-scope="props">
            <tr :active="props.selected" @click="props.selected = !props.selected">
              <td>
                <v-checkbox
                  color="black"
                  hide-details
                  :input-value="props.selected">
                </v-checkbox>
              </td>
              <td class="text-xs-center">{{ props.item.id }}</td>
              <td class="text-xs-center">{{ props.item.name }}</td>
              <td class="text-xs-center">{{ props.item.category.name }}</td>
              <td class="text-xs-center">{{ props.item.institution.name }}</td>
              <td class="text-xs-center"><router-link class="btn" :to="`/discipline/${props.item.id}`">Visitar</router-link></td>
            </tr>
          </template>
        </v-data-table>
        <br>
        <v-btn to="/discipline/add">Adicionar Disciplina</v-btn>
        <v-btn v-if="selected.length === 1" dark v-on:click="delDiscipline($event)">Deletar Disciplina</v-btn>
      </v-card-text>
    </v-card>
  </v-flex>
</template>

<script>
  import Toastr from 'toastr';
  import DisciplineStore from '@/store/Discipline';

  export default {
    name: 'Lista-de-Disciplines',

    data: () => ({
      search: '',
      pagination: {
        sortBy: 'id',
      },
      selected: [],
      list: [],
      headers: [{
        text: 'ID',
        value: 'id',
        align: 'left',
      }, {
        text: 'Nome',
        value: 'name',
        align: 'center',
      }, {
        text: 'Categoria',
        value: 'desc',
        align: 'center',
      }, {
        text: 'Instituição',
        value: 'host',
        align: 'center',
      }, {
        text: 'Visitar',
        value: 'visit',
        align: 'center',
      }],
    }),

    created() {
      DisciplineStore.on('delete', () => {
        this.refreshList();
        Toastr.success('Disciplina removida');
      }, this);
      DisciplineStore.on('listAll', (data) => {
        this.list = data.data;
      }, this);
      this.refreshList();
    },

    beforeDestroy() {
      DisciplineStore.off(null, null, this);
    },
    methods: {
      refreshList() {
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_LIST_ALL,
        });
        this.list = null;
        this.toggleAll();
      },
      toggleAll() {
        if (this.selected.length) {
          this.selected = [];
        } else if (this.list) {
          this.selected = this.list.slice();
        }
      },
      changeSort(column) {
        if (this.pagination.sortBy === column) {
          this.pagination.descending = !this.pagination.descending;
        } else {
          this.pagination.sortBy = column;
          this.pagination.descending = false;
        }
      },
      delDiscipline(event) {
        event.preventDefault();
        if (this.selected.length === 1) {
          DisciplineStore.dispatch({
            action: DisciplineStore.ACTION_DELETE,
            data: this.selected[0],
          }, this);
        } else {
          Toastr.error('Selecione somente um por vez');
        }
      },
    },
  };
</script>
