<script>
import AppLayout from '../../Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import moment from 'moment';
export default{
    props:{
        clients:Object,
        marches:Object,
        vente:Object,
    },
    components:{
        AppLayout,
    },
    data(){
        return{
            clients:this.clients,
            valid: false,
            validclient:false,
            dialog:false,
            loadingclient:false,
            loading:false,
            editedItem: {
                name:'',
            },
            status:[
                {title : 'Réglé', value : 'paid'},
                {title : 'Non Réglé', value : 'pending'},
            ],
            objetRules: [
                value => {
                if (value) return true

                return 'L\'objet" est obligatoire'
                },
            ],
            clientRules: [
                value => {
                if (value) return true

                return 'Le client est obligatoire'
                },
            ],
            unityRules: [
                value => {
                if (value) return true

                return 'L\'unité est obligatoire'
                },
            ],
            designationRules: [
                value => {
                if (value) return true

                return 'La désignation est obligatoire'
                },
            ],
            statusRules: [
                value => {
                if (value) return true

                return 'Le statut de paiement est obligatoire'
                },
            ],
            quantityRules: [
                value => {
                if (value) return true

                return 'La quantité est obligatoire'
                },
            ],
            priceperunityRules: [
                value => {
                if (/^-?\d+(\.\d+)?$/.test(value)) return true

                return 'La prix par unité doit être numérique'
                },
            ],
            tvaRules: [
                value => {
                if (value || value === 0) return true

                return 'Le taux de TVA est obligatoire'
                },
            ],
            total_textRules: [
                value => {
                if (value) return true

                return 'Le total en texte est obligatoire'
                },
            ],
            typeRules: [
                value => {
                if (value) return true

                return 'Le type de paiement est obligatoire'
                },
            ],
        }
    },
    setup(props){
        const form = useForm({
            client_id:props.vente.client.id,
            marche_id:props.vente.marche? props.vente.marche.id : null,
            objet:props.vente.objet,
            products:props.vente.products,
            total:null,
            total_text:props.vente.total_text,
            taux_tva:props.vente.taux_tva,
            date:props.vente.date,
            status:props.vente.status,
        })

        return{form}
    },
    computed:{
        totalht(){
            let totalht = 0 
            this.form.products.forEach(product => {
                if(product.quantity && product.priceperunity){
                    totalht+= product.quantity * product.priceperunity
                }else{
                    return 0
                }
            });
            return totalht
        },
    },
    methods:{
        submit(){
            this.form.total = this.totalht
            this.$refs.formulaire.validate().then(() => {
                if(this.valid){
                    this.loading = true
                    this.$inertia.post(`/ventes/${this.vente.id}`, {
                        _method: "put",
                        client_id: this.form.client_id,
                        marche_id: this.form.marche_id,
                        objet: this.form.objet,
                        products: this.form.products,
                        total: this.form.total,
                        total_text: this.form.total_text,
                        taux_tva: this.form.taux_tva,
                        date: this.form.date,
                        status: this.form.status,
                    }, {
                    preserveScroll: true,
                    onSuccess : () => {
                        this.loading = false
                    }
                });
                }
            })  
        },
        async save(){
            this.$refs.formclient.validate().then(async () => {
                if(this.validclient){
                this.loadingclient = true
                try {
                const result = await axios.post(`/client/store`,{
                            name: this.editedItem.name,
                        });
                        if (result.data.client) {
                            this.clients.push({
                                id : result.data.client.id,
                                name : result.data.client.name,
                            });
                            this.form.client_id = result.data.client.id
                        }
                    } catch (err) {
                    }
                }
                this.loadingclient = true
                this.close()
            })
        },
        close(){
            this.dialog = false
            this.editedItem.name = ''
        }
    }
}
</script>
<template>
    <AppLayout title="Modification de vente">
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Modification de vente {{vente.numero}}</h2>
        </template>
        <v-form :disabled="loading"  v-model="valid" @submit.prevent="submit" ref="formulaire">
            <v-sheet color="grey-white" class="p-6" border rounded>
                <h3 class="text-xl font-bold ">Informations relatives à la vente:</h3>
                <hr class="my-3">
                <v-row>
                    <v-col
                    cols="12"
                    >
                    <v-text-field
                    v-model="form.objet"
                    label="Objet"
                    :rules="objetRules"
                    >
                    </v-text-field>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col
                    cols="12"
                    md="6"
                    >
                    <v-select
                    v-model="form.marche_id"
                    :items="marches"
                    label="Marché"
                    item-title="name"
                    item-value="id"
                    :rules="marcheRules"
                    >
                    </v-select>
                    </v-col>
                    <v-col
                    cols="12"
                    md="6"
                    >
                    <v-select
                    v-model="form.client_id"
                    :items="clients"
                    label="Client"
                    no-data-text="Aucun client enregistré"
                    item-title="name"
                    item-value="id"
                    :rules="clientRules"
                    >
                    <template v-slot:details>
                        <v-dialog
                        v-model="dialog"
                        max-width="500px"
                        transition="dialog-top-transition"
                        persistent
                        >
                        <template v-slot:activator="{ props }">
                            <div class="flex justify-end items-center">
                                <v-btn
                                prepend-icon="mdi-plus-circle-outline"
                                color="blue-accent-4"
                                size="small"
                                variant="text"
                                v-bind="props"
                                >
                                Nouveau client
                                </v-btn>
                            </div>
                        </template>
                        <v-form v-model="validclient" :disabled="loadingclient" @submit.prevent="save" ref="formclient">
                        <v-card>
                            <v-card-title class="border-b bg-gray-50">
                                <span class="text-h6 font-bold">Nouveau client</span>
                            </v-card-title>

                            <v-card-text class="max-h-96 overflow-y-auto">
                            <v-container>
                                <v-row>
                                <v-col
                                    cols="12"
                                >
                                    <v-text-field
                                    v-model="editedItem.name"
                                    variant="outlined"
                                    label="Nom"
                                    placeholder=""
                                    :rules="nameRules"
                                    
                                    ></v-text-field>
                                </v-col>
                                </v-row>
                            </v-container>
                            </v-card-text>

                            <v-card-actions class="bg-gray-100  flex justify-end items-center border-t">
                            <v-btn color="gray" class="mt-2" variant="text" :disabled="loadingclient" @click="close">Annuler</v-btn>
                            <v-btn color="blue-accent-4" type="submit" class="mt-2" active variant="flat" :loading="loadingclient">Enregistrer</v-btn>
                            </v-card-actions>
                        </v-card>
                        </v-form>
                        </v-dialog>
                    </template>
                    </v-select>
                    </v-col>
                </v-row>
            </v-sheet>
            <v-sheet color="grey-white" class="p-6 mt-4" border rounded>
                <h3 class="text-xl font-bold ">Articles:</h3>
                <hr class="my-3">
                <div v-for="(product,index) in form.products" class="grid grid-cols-5 gap-4 border-b" :key="index">
                    <div class="col-span-5 md:col-span-2 mt-2">
                        <v-text-field
                            v-model="product.designation"
                            label="Désignation"
                            :rules="designationRules"
                            >
                        </v-text-field>
                    </div>
                    <div class="col-span-5 md:col-span-3 gap-2 mt-2 grid grid-cols-9">
                        <div class="col-span-2 flex items-center" 
                        >
                            <v-text-field
                            v-model="product.quantity"
                            type="number"
                            label="Quantité"
                            min="1"
                            max="999"
                            :rules="quantityRules"
                            ></v-text-field>
                        </div>
                        <div class="col-span-2 flex items-center"
                        >
                        <v-select
                        v-model="product.unity"
                        :items="['F','Kg','T','m','m²','m³','l','Journée','Voyage']"
                        label="Unité"
                        :rules="unityRules"
                        >
                        </v-select>
                        </div>
                        <div class="col-span-2 flex items-center"
                        >
                            <v-text-field
                            v-model="product.priceperunity"
                            label="Prix par unité"
                            suffix="DH HT"
                            min="0"
                            max="999"
                            :rules="priceperunityRules"
                            ></v-text-field>
                        </div>
                        <div class="col-span-2 flex  justify-center border items-center mb-5 rounded bg-gray-50"
                        >
                            <span v-if="!product.quantity || !product.priceperunity"> 0 </span>
                            <span v-else> {{(product.priceperunity*product.quantity).toFixed(2)}} </span>
                            <span class="text-gray-500"> &nbsp;DH HT</span>
                        </div>
                        <div class="flex justify-end col-span-1 mt-1"
                        >
                            <v-btn
                                variant="text"
                                border
                                icon="mdi-delete"
                                color="red"
                                @click="form.products.splice(index,1)"
                            >
                            </v-btn>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-2"><v-btn color="blue" density="compact" class="mb-4" prepend-icon="mdi-plus-box-outline" variant="text" @click="form.products.push({designation:null,priceperunity:null,quantity:null})">Ajouter un article</v-btn></div> 
                
                <h3 class="text-xl font-bold ">Total:</h3>
                <hr class="my-3">
                <div class="flex gap-2 justify-end">
                    <div class="flex p-4 justify-center border items-center mb-5 rounded bg-gray-50">
                        <span> {{totalht.toFixed(2)}} </span>
                        <span class="text-gray-500"> &nbsp;DH HT</span>
                    </div>
                    <div class="w-40">
                    <v-select
                    v-model="form.taux_tva"
                    :items="[0,7,10,14,20]"
                    label="Taux de TVA"
                    suffix="%"
                    :rules="tvaRules"
                    >
                
                    </v-select>
                    </div>
                    <div class="flex p-4 justify-center border items-center mb-5 rounded bg-gray-50">
                        <span v-if="(form.taux_tva || form.taux_tva === 0) && form.products.length"> {{(totalht + totalht* form.taux_tva*0.01).toFixed(2)}} </span>
                        <span class="text-gray-500"> &nbsp;DH TTC</span>
                    </div>
                </div>
            </v-sheet>
            <v-sheet color="grey-white" class="p-6 mt-4" border rounded>
                <h3 class="text-xl font-bold ">Informations relatives au paiement:</h3>
                <hr class="my-3">
                <v-row>
                    <v-col
                    cols="12"
                    md="6"
                    >
                    <v-select
                    v-model="form.status"
                    :items="status"
                    label="Statut de paiement"
                    item-title="title"
                    item-value="value"
                    :rules="statusRules"
                    >
                    </v-select>
                    </v-col>
                    <v-col
                    cols="12"
                    md="6"
                    >
                    <v-text-field
                    v-model="form.date"
                    type="date"
                    label="Date de facturation"
                    clearable
                    >
                    </v-text-field>
                    </v-col>
                </v-row>
            </v-sheet>
            <div class="flex justify-end items-center mt-4">
                <v-btn variant="flat" :loading="loading" type="submit" color="blue-accent-4">
                    Enregistrer
                </v-btn>
            </div>
        </v-form>
    </AppLayout>
</template>
<style>
.v-field__input,.v-field__input:focus{
    background: transparent;
    box-shadow: 0 0 0 0px;
}
.v-select .v-field .v-field__input > input {
    opacity: 0;
}
</style>