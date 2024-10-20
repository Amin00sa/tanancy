<script>
export default {
  props: {
    centralDomain: String,
    data: Array,
  },
  data() {
    return {
      central: null,
      data: [
        {
          'url': 'http://8eb97e37-4c92-4728-a7c9-04007e24d204.',
          'image': 'ltp-removebg.png'
        },
        {
          'url': 'http://129d4bd8-dd06-443b-a925-980cd1ccc073.',
          'image': 'soltaf-removebg.png'
        }
      ]
    }
  },
  mounted() {
    if (this.centralDomain === 'localhost') {
      this.central = 'localhost:8080';
    } else {
      this.central = this.centralDomain
    }
  },
}

</script>

<template>
  <Head title="Lamane TP"/>

  <div
      class="flex justify-center gap-10 items-center flex-col sm:flex-row min-h-screen bg-gray-100"
  >
    <a v-for="item in data" :href="item.url+central">
      <div
          class="flex justify-center bg-gray-50 hover:bg-gray-200 cursor-pointer items-center border shadow-md hover:shadow-lg w-72 h-44 p-2 hover:p-0 duration-300 rounded-lg">
        <v-img :src="item.image"></v-img>
      </div>
    </a>
  </div>
</template>

<style>

</style>