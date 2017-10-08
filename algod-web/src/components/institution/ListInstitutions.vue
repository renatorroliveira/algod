<template>
 <v-container fluid>
  <v-card>
    <v-card-text>
      <v-data-table
        v-model="selected"
        :headers="headers"
        :items="instList"
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
            <td class="text-xs-center">{{ props.item.description }}</td>
            <td class="text-xs-center">{{ props.item.host }}</td>
            <td class="text-xs-center">{{ props.item.site }}</td>
            <td class="text-xs-center">{{ props.item.baseUrl }}</td>
          </tr>
        </template>
      </v-data-table>
      <br>
      <v-btn dark to="/add">Adicionar instituição</v-btn>
      <v-btn v-if="selected.length === 1" dark @click.native="delInstitution($event)">Deletar Institutição</v-btn>
    </v-card-text>
  </v-card>
 </v-container>
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
        instList: [],
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
          text: 'Host',
          value: 'host',
          align: 'left',
        }, {
          text: 'Site',
          value: 'Site',
          align: 'left',
        }, {
          text: 'Base URL',
          value: 'Base url',
          align: 'left',
        }],
      };
    },

    created() {
      const me = this;
      InstitutionStore.dispatch({
        action: InstitutionStore.ACTION_LIST,
      });
      InstitutionStore.on(InstitutionStore.ACTION_DELETE, () => {
        Toastr.success('Instituição removida');
      }, me);
      InstitutionStore.on('listInsti', (data) => {
        for (let i = 0; i < data.data.length; i += 1) {
          if (data.data[i].deleted === false) {
            me.instList.push(data.data[i]);
          }
        }
      }, me);
    },

    beforeDestroy() {
      InstitutionStore.off(null, null, this);
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
      delInstitution(event) {
        event.preventDefault();
        const me = this;
        if (this.selected.length === 1) {
          InstitutionStore.dispatch({
            action: InstitutionStore.ACTION_DELETE,
            data: this.selected[0],
          }, me);
        } else {
          Toastr.error('Selecione um por vez');
        }
      },
    },
  };
</script>
