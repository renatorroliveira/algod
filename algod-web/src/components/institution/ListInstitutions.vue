<template>
  <v-flex xs12>
    <v-card>
      <v-card-text>
        <v-data-table
          v-if="list"
          v-model="selected"
          :headers="headers"
          :items="list"
          select-all
          v-bind:pagination.sync="pagination"
          item-key="name"
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
              <td class="text-xs-center">{{ props.item.description }}</td>
              <td class="text-xs-center">{{ props.item.site }}</td>
            </tr>
          </template>
        </v-data-table>
        <br>
        <v-btn to="/institution/add">Adicionar instituição</v-btn>
        <v-btn v-if="selected.length === 1" dark v-on:click="delInstitution($event)">Deletar Institutição</v-btn>
      </v-card-text>
    </v-card>
  </v-flex>
</template>

<script>
  import Toastr from 'toastr';
  import InstitutionStore from '@/store/Institution';

  export default {
    name: 'Lista-de-institutions',

    data() {
      return {
        pagination: {
          sortBy: 'id',
        },
        selected: [],
        list: [],
        headers: [{
          text: 'ID',
          align: 'left',
          value: 'id',
        }, {
          text: 'Nome',
          value: 'name',
          align: 'left',
        }, {
          text: 'Description',
          value: 'desc',
          align: 'left',
        }, {
          text: 'Site',
          value: 'Site',
          align: 'left',
        }],
      };
    },

    created() {
      InstitutionStore.on('delete', () => {
        this.refreshList();
        Toastr.success('Instituição removida');
      }, this);
      InstitutionStore.on('list', (data) => {
        this.list = data.data;
      }, this);
      this.refreshList();
    },

    beforeDestroy() {
      InstitutionStore.off(null, null, this);
    },
    methods: {
      refreshList() {
        InstitutionStore.dispatch({
          action: InstitutionStore.ACTION_LIST,
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
      delInstitution(event) {
        event.preventDefault();
        const me = this;
        if (this.selected.length === 1) {
          InstitutionStore.dispatch({
            action: InstitutionStore.ACTION_DELETE,
            data: this.selected[0],
          }, me);
        } else {
          Toastr.error('Selecione somente um por vez');
        }
      },
    },
  };
</script>
