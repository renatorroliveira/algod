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
          <template slot="headerCell" slot-scope="props">
            <span v-tooltip:bottom="{ 'html': props.header.text }">
              {{ props.header.text }}
            </span>
          </template>
          <template slot="items" slot-scope="props">
            <tr :active="props.selected" @click="props.selected = !props.selected">
              <td>
                <v-checkbox
                  primary
                  hide-details
                  :input-value="props.selected">
                </v-checkbox>
              </td>
              <td class="text-xs-center">{{ props.item.id }}</td>
              <td class="text-xs-center">{{ props.item.name }}</td>
              <td class="text-xs-center">{{ props.item.email }}</td>
              <td class="text-xs-center">{{ props.item.accessLevel }}</td>
              <td class="text-xs-center">{{ props.item.deleted }}</td>
            </tr>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-flex>
</template>

<script>
  import UserStore from '@/store/User';

  export default {
    name: 'Lista-de-usuarios',

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
          text: 'E-mail',
          value: 'email',
          align: 'left',
        }, {
          text: 'Access Level',
          value: 'accessLevel',
          align: 'left',
        }, {
          text: 'Deleted',
          value: 'deleted',
          align: 'left',
        }],
      };
    },
    created() {
      UserStore.dispatch({
        action: UserStore.ACTION_LIST,
      });
      UserStore.on('list', (data) => {
        this.list = data.data;
      }, this);
    },

    beforeDestroy() {
      UserStore.off(null, null, this);
    },
    methods: {
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
    },
  };
</script>
