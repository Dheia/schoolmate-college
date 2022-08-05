<template>
  <div class="col-lg-9">
    <div class="row pt-4">
        <div v-if="items != null" v-for="item in items">
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
              <a href="#"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
              <div class="card-body">
                <h4 class="card-title">
                  <a href="#">{{ item.name }}</a>
                </h4>
                <h5>{{ item.sale_price }}</h5>
                <p class="card-text">{{ item.description }}</p>
              </div>
              <div class="card-footer">
                <a href="#" class="btn btn-primary w-100 h-100"><i class="fa fa-cart-plus fa-1x" aria-hidden="true"></i> Add to Cart</a>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div> 
</template>

<script>
  export default {
    data() {
      return {
        items: null,
      }
    },
    ready() {
        this.getItems();
    },

    /**
     * Prepare the component (Vue 2.x).
     */
    mounted() {
        console.log('Component has been created!');
    },
    computed: {

    },
    methods: {
      getItems() {
        axios.get('/canteen/api/get/items')
        .then(response => {
            this.items = response.data;
        }); 
      }
    },
    created() {
      console.log('Component has been created!');
    }
  }
</script>
