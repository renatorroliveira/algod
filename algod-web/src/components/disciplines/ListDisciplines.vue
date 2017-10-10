<template>
 <v-container fluid>
  <v-card>
    <v-card-text>
      <v-data-table
        v-model="selected"
        :headers="headers"
        :items="discList"
        select-all
        v-bind:pagination.sync="pagination"
        item-key="name"
        class="elevation-1">
        <template slot="headers" scope="props">
          <tr>
            <th>
              <v-checkbox
                primary
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
        <template slot="headerCell" scope="props">
          <span v-tooltip:bottom="{ 'html': props.header.text }">
            {{ props.header.text }}
          </span>
        </template>
        <template slot="items" scope="props">
          <tr :active="props.selected" @click="props.selected = !props.selected">
            <td>
              <v-checkbox
              primary
              hide-details
              :input-value="props.selected"
              ></v-checkbox>
            </td>
            <td class="text-xs-center">{{ props.item.id }}</td>
            <td class="text-xs-center">{{ props.item.name }}</td>
            <td class="text-xs-center">{{ props.item.category.name }}</td>
            <td class="text-xs-center">{{ props.item.institution.name }}</td>
          </tr>
        </template>
      </v-data-table>
      <br>
      <v-btn dark to="/discipline/add">Adicionar Disciplina</v-btn>
      <v-btn v-if="selected.length === 1" dark @click.native="delDiscipline($event)">Deletar Disciplina</v-btn>
    </v-card-text>
  </v-card>
 </v-container>
</template>

<script>
  import Toastr from 'toastr';
  import DisciplineStore from '@/store/Discipline';

  export default {
    name: 'Lista-de-Discipline',

    data() {
      return {
        pagination: {
          sortBy: 'id',
        },
        selected: [],
        discList: [],
        headers: [{
          text: 'ID',
          align: 'left',
          value: 'id',
        }, {
          text: 'Nome',
          value: 'name',
          align: 'left',
        }, {
          text: 'category_name',
          value: 'desc',
          align: 'left',
        }, {
          text: 'institution_name',
          value: 'host',
          align: 'left',
        }],
      };
    },

    created() {
      const me = this;
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_LIST_ALL,
      });
      DisciplineStore.on(DisciplineStore.ACTION_DELETE, () => {
        Toastr.success('Disciplina removida');
      }, me);
      DisciplineStore.on(DisciplineStore.ACTION_LIST_ALL, (data) => {
        for (let i = 0; i < data.data.length; i += 1) {
          if (data.data[i].deleted === false) {
            me.discList.push(data.data[i]);
          }
        }
      }, me);
    },

    beforeDestroy() {
      DisciplineStore.off(null, null, this);
    },
    methods: {
      toggleAll() {
        if (this.selected.length) {
          this.selected = [];
        } else {
          this.selected = this.instList.slice();
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
        const me = this;
        if (this.selected.length === 1) {
          DisciplineStore.dispatch({
            action: DisciplineStore.ACTION_DELETE,
            data: this.selected[0],
          }, me);
        } else {
          Toastr.error('Selecione somente um por vez');
        }
      },
    },
  };
</script>
