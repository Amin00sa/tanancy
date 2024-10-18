<script>
import AppLayout from '../../Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import moment from 'moment';
export default{
    props:{
        suppliers:Object,
        banks:Object,
        marches:Object
    },
    components:{
        AppLayout,
    },
    data(){
        return{
            files:[],
            paiement_immediat:false,
            valid: false,
            marcheRules: [
                value => {
                if (value) return true

                return 'Le marché est obligatoire'
                }
            ],
            supplierRules: [
                value => {
                if (value) return true

                return 'Le fournisseur est obligatoire'
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
            bankRules: [
                value => {
                if (value) return true

                return 'La banque source est obligatoire'
                },
            ],
            typeRules: [
                value => {
                if (value) return true

                return 'Le type de paiement est obligatoire'
                },
            ],
            dateRules: [
                value => {
                if (value) return true

                return 'La date d\'achat est obligatoire'
                },
            ],
            echeance_dateRules: [
                value => {
                if (value || this.form.paiement_immediat) return true

                return 'La date d\'échéance est obligatoire'
                },
            ],
        }
    },
    setup(){
        const form = useForm({
            marche_id:null,
            supplier_id:null,
            bank_id:null,
            products:[],
            taux_tva:null,
            paiement_immediat:false,
            echeance_date:null,
            total:null,
            date:null,
            type:null,
            proof_file:null,
            motif:null
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
        now(){
            return moment().format('YYYY-MM-DD')
        }
    },
    methods:{
        submit(){
            this.form.total = this.totalht
            this.$refs.formulaire.validate().then(() => {
                if(this.valid){
                    this.form.post(route('purchases.store'));
                }
            })  
        },
        setfile(){
            if(this.files.length){
                this.form.proof_file = this.files[0]
            }else{
                this.form.proof_file = null
            }
        }
    }
}
</script>
<template>
    <AppLayout title="Création d'un nouvel achat">
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Ajout d'un nouvel achat</h2>
        </template>
        <v-form :disabled="form.processing"  v-model="valid" @submit.prevent="submit" ref="formulaire">
            <v-sheet color="grey-white" class="p-6" border rounded>
                <h3 class="text-xl font-bold ">Informations relatives à l'achat:</h3>
                <hr class="my-3">
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
                    v-model="form.supplier_id"
                    :items="suppliers"
                    label="Fournisseur"
                    item-title="name"
                    item-value="id"
                    :rules="supplierRules"
                    >
                    <template v-slot:details>
                        <div v-if="form.supplier_id != null" class="flex justify-end">
                            <div> {{suppliers.filter(supplier => supplier.id == form.supplier_id)[0].bank_name}} : {{suppliers.filter(supplier => supplier.id == form.supplier_id)[0].bank_rib}}</div>
                        </div>
                    </template>
                    </v-select>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col
                    cols="12"
                    md="6"
                    >
                    <v-text-field
                        v-model="form.date"
                        type="date"
                        label="Date d'achat"
                        :rules="dateRules"
                        >
                    </v-text-field>
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
                    v-model="form.bank_id"
                    :items="banks"
                    label="Banque"
                    item-title="name"
                    item-value="id"
                    :rules="bankRules"
                    >
                    <template v-slot:details>
                        <div v-if="form.bank_id != null" class="flex justify-end">
                            <div> RIB : {{banks.filter(bank => bank.id == form.bank_id)[0].rib}}</div>
                        </div>
                    </template>
                    </v-select>
                    </v-col>
                    <v-col
                    cols="12"
                    md="6"
                    >
                    <v-select
                    v-model="form.type"
                    :items="['Espèce','Chèque','Virement','Effet','Versement']"
                    label="Type de paiement"
                    :rules="typeRules"
                    >
                    <template v-slot:details>
                        <div v-if="form.type === 'Virement'" class="flex justify-end">
                            <div> Un ordre de virement sera généré automatiquement après l'enregistrement de l'achat</div>
                        </div>
                    </template>
                    </v-select>
                    </v-col>
                </v-row>
                <v-row v-if="form.type === 'Virement'">
                    <v-col
                    cols="12"
                    md="6"
                    >
                    <v-text-field
                    v-model="form.motif"
                    label="Motif de virement"
                    ></v-text-field>
                    </v-col>
                </v-row>
                <v-row v-if="form.type === 'Chèque' || form.type ===  'Effet'">
                    <v-col
                    cols="12"
                    md="6"
                    >
                    <v-checkbox
                    v-model="form.paiement_immediat"
                    label="Paiement immédiat"
                    ></v-checkbox>
                    </v-col>
                    <v-col
                    cols="12"
                    md="6"
                    >
                    <v-text-field
                    v-model="form.echeance_date"
                    label="Date d'échéance"
                    type="date"
                    :min="now"
                    :rules="echeance_dateRules"
                    :disabled="form.paiement_immediat"
                    ></v-text-field>
                    </v-col>
                </v-row>
                <v-row
                v-if="form.type != 'Virement' && form.type !=null"
                >
                    <v-col
                    cols="12"
                    md="6"
                    >
                        <v-file-input label="Justificatif" v-model="files" accept="image/*,application/pdf" :update:modelValue="setfile()"></v-file-input>
                    </v-col>
                </v-row>
            </v-sheet>
            <div class="flex justify-end items-center mt-4">
                <v-btn variant="flat" :loading="form.processing" type="submit" color="blue-accent-4">
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
<style scoped>
.button_position {
    top: -20px;
    right: -20px;
}
.position_absolute {
    position: absolute !important;
}
</style>