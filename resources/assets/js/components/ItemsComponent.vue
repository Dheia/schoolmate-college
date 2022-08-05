<template>
  <div class="col-md-4">
    <table class="table table-striped">
      <thead>
        <th>Item Name</th>
        <th>Sale Price</th>
        <th>Stocks</th>
      </thead>
    </tbody>
      <tr>
        <td>{{name | properCase }}</td>
        <td>{{sale_price }}</td>
        <td>{{quantity_on_hand }}</td>
      </tr>
    </table>
    </table>
    <div class="col-2">
     {{ name | properCase }}
    
    </div>
  </div>
</template>
<script>
  export default {
    computed: {
      // image() {
      //   return `/images/${this.color}.png`;
      // }
    },
    methods: {
      buy(val) {
        this.$emit('buy', this.id, val.target.selectedOptions[0].value);
      },
      del() {
        this.$emit('delete', this.id);
      }
    },
    props: ['id', 'name', 'sale_price'],
    filters: {
      properCase(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      }
    }
  }
</script>
