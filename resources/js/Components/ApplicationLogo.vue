<template>
    <v-img v-if="tenant === 'ltp'" src="ltp-removebg.png" class="w-80 h-12"></v-img>
    <v-img v-if="tenant === 'soltaf'" src="soltaf-removebg.png" class="w-56 h-12"></v-img>
</template>
<script>
export default {
    props:{
        tenant:String,
    },
    data(){
        return{
            tenant:this.tenant
        }
    },
    mounted(){
        if(this.$page.props.ziggy.url == "http://ltp.localhost:8000" || this.$page.props.ziggy.url == "https://ltp.localhost:8000" || this.$page.props.ziggy.url == "http://ltp.lamanetp.com" || this.$page.props.ziggy.url == "https://ltp.lamanetp.com"){
            this.tenant = 'ltp'
        }else{
            this.tenant = 'soltaf'
        }
    }
}
</script>