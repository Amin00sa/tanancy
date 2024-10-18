<script>
import { Head, Link } from '@inertiajs/vue3';
export default{
    props:{
        centralDomain:String
    },
data(){
    return{
        central:null
    }
},
mounted(){
    if(this.centralDomain == 'localhost'){
        this.central = 'localhost:8000'
    }else{
        this.central = this.centralDomain
    }
},
}

</script>

<template>
    <Head title="Lamane TP" />
    
    <div
        class="flex justify-center gap-10 items-center flex-col sm:flex-row min-h-screen bg-gray-100"
    >
    <a :href="'http://ltp.'+central">
    <div class="flex justify-center bg-gray-50 hover:bg-gray-200 cursor-pointer items-center border shadow-md hover:shadow-lg w-72 h-44 p-2 hover:p-0 duration-300 rounded-lg">
        <v-img src="ltp-removebg.png"></v-img>
    </div>
    </a>
    <a :href="'http://soltaf.'+central">
    <div class="flex justify-center bg-gray-50 hover:bg-gray-200 cursor-pointer items-center border shadow-md hover:shadow-lg w-72 h-44 p-10 hover:p-6 duration-300 rounded-lg">
        <v-img src="soltaf-removebg.png"></v-img>
    </div>
    </a>
    </div>
</template>

<style>

</style>