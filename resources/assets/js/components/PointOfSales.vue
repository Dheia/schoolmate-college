<template>
    <div class="container-fluid">
        <div class="heading">
          <h1>Items</h1>
        </div>
        <div class="col-3">
          Search: <input type="text" name="search" class="form-control" />
        </div>
        <items-component
          v-for="item in items"
          v-bind="item"
          :key="item.id"
          @buy="buy"
        ></items-component>
    </div>
</template>

<style>
   
    
</style>

<script>
import ItemsComponent from './ItemsComponent.vue';

    function Item({ id, name, sale_price, quantity_on_hand, barcode, code, description}) {
        this.id = id;
        this.name = name;
        this.sale_price = sale_price;
        this.quantity_on_hand = quantity_on_hand;
        this.barcode = barcode;
        this.code = code;
        this.description = description;
      }

    export default {
        data() {
            return {
                items: [],
            }
        },
        methods: {
            read() {
                window.axios.get('/admin/json-items').then(({ data }) => {
                  data.forEach(item => {
                    this.items.push(new Item(item));
                  });
                });
            },
            buy(){

            }

        },

        created() {
          this.read();
          console.log("Reading");
                
        },
        components: {
          ItemsComponent
        }
    }
    
</script>


