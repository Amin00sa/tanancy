<script>
import AppLayout from '../../Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import moment from 'moment';
export default{
    props:{
        suppliers:Object,
        marches:Object,
        banks:Object,
        supplier:String,
        marche:String,
        bank:String,
    },
    components:{
        AppLayout,
        Link,
    },
    data(){
        return{
        showfilter:false,
        totalht:0,
        totalttc:0,
        totaltva:0,
        loading:false,
        dialog:false,
        deleting:false,
        fetchingpurchases:true,
        purchases:[],
        total:0,
        filter:{
            marche:null,
            supplier:null,
            bank:null,
            date_debut:null,
            date_fin:null,
        },
        options: {
        },
        dialogDelete: false,
        itemsperpageoptions:[
            { title: '10', value: 10 },
            { title: '25', value: 25 },
            { title: '50', value: 50 },
            { title: '100', value: 100 },
            { title: 'Tous', value: -1 },
        ],
        itemsperpagetext: "Lignes par page",
        headers: [
            {
            title: 'Numéro d\'achat',
            align: 'start',
            sortable: false,
            key: 'name',
            },
            {
            title: 'Marché',
            sortable: false,
            key: 'marche',
            },
            {
            title: 'Fournisseur',
            sortable: false,
            key: 'supplier'
            },
            {
            title: 'Type de paiement',
            sortable: false,
            key: 'paiement'
            },
            {
            title: 'Banque',
            sortable: false,
            key: 'bank'
            },
            {
            title: 'Date d\'achat',
            sortable: false,
            key: 'date'
            },
            {
            title: 'Montant Total TTC',
            sortable: false,
            key: 'montant'
            },
            { title: 'Actions',
             key: 'actions',
             align: 'center',
             sortable: false 
            },
        ],
        deletedId: -1,
        deletedIndex: -1,
    }   
    },
    watch: {
        dialogDelete (val) {
            val || this.closeDelete()
        },
    },
    mounted(){
        if(this.bank){
            this.filter.bank = parseInt(this.bank)
        }
        if(this.marche){
            this.filter.marche = parseInt(this.marche)
        }
        if(this.supplier){
            this.filter.supplier = parseInt(this.supplier)
        }
        this.LoadData()
        this.$watch('filter',{
            handler(){
                return this.LoadData()
            },
            deep: true,
        },)
        this.$watch('options',{
            handler(){
                return this.LoadData()
            },
            deep: true,
        },)
    },

    computed:{
        count(){
            return this.purchases.length
        },
    },

    methods: {
        async LoadData() {
            this.fetchingpurchases = true
            try {
                const result = await axios.get(`/webapipurchases`,{
                    params: {
                        page : this.options.page,
                        itemsPerPage: this.options.itemsPerPage,
                        marche : this.filter.marche,
                        search : this.options.search,
                        supplier: this.filter.supplier,
                        date_debut: this.filter.date_debut,
                        date_fin: this.filter.date_fin,
                        bank: this.filter.bank
                    }
                });
                
                this.purchases = []
                if (result.data.purchases.data.length) {
                    this.purchases = result.data.purchases.data
                    this.total = result.data.total
                    this.totalttc = result.data.totalttc
                    this.totalht = result.data.totalht
                    this.totaltva = result.data.totaltva
                }
            } catch (err) {
            }
            this.fetchingpurchases = false
        }, 
        deleteItem (item) {
            this.deletedIndex = this.purchases.indexOf(item)
            this.deletedId = item.id
            this.dialogDelete = true
        },
        closeDelete(){
            this.deletedId = null
            this.dialogDelete = false
        },
        deleteItemConfirm () {
            this.deleting = true
            this.$page.props.flash.success = null
            this.$inertia.delete(route('purchases.destroy', this.deletedId),{
                onSuccess: () => {
                    this.deleting = false
                    this.closeDelete()
                    if(this.count === 0 && this.options.page > 1){
                        this.options.page--
                    }else{
                        this.LoadData()
                    }
                },
                preserveScroll: true,
            })
        },
        formatedDate(date){
            return moment(date).format('DD/MM/YYYY')
        }
    },
}
</script>

<template>
    <AppLayout title="Achats">
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Achats</h2>
        </template>
        <div class="my-2 flex justify-center items-center">
                <v-btn v-bind="props" :active="showfilter" variant="tonal" color="grey-darken-4" @click="showfilter = !showfilter">
                    <template v-slot:prepend><v-badge v-if="filter.marche || filter.bank || filter.supplier" dot><v-icon>mdi-filter-outline</v-icon></v-badge><v-icon v-else>mdi-filter-outline</v-icon></template>Filtre
                </v-btn>
            </div>
        <div class="flex flex-col justify-center mb-5 text-center">
            <VExpandTransition>
            <v-card v-if="showfilter" border elevation="0" class="text-start ">
                <v-card-text>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                    <label>Marché:</label>
                    <v-select
                        v-model="filter.marche"
                        class="mt-1"
                        :items="marches"
                        item-value="id"
                        item-title="name"
                        no-data-text="Aucun marché"
                        variant="outlined"
                        clearable
                    >
                    </v-select>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                    <label>Fournisseur:</label>
                    <v-select
                        v-model="filter.supplier"
                        class="mt-1"
                        :items="suppliers"
                        item-value="id"
                        item-title="name"
                        no-data-text="Aucun fournisseur"
                        variant="outlined"
                        clearable
                    >
                    </v-select>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                    <label>Banque:</label>
                    <v-select
                        v-model="filter.bank"
                        class="mt-1"
                        :items="banks"
                        item-value="id"
                        item-title="name"
                        no-data-text="Aucune banque"
                        variant="outlined"
                        clearable
                    >
                    </v-select>
                    </div>
                </div>
                </v-card-text>
            </v-card>
        </VExpandTransition>
    </div>
    <v-sheet border class="rounded p-4 mb-6">
        <div class="flex justify-between gap-10">
            <v-text-field
            type="date"
            label="Date de début"
            v-model="filter.date_debut"
            :max="filter.date_fin"
            hide-details
            clearable>
            </v-text-field>

            <v-text-field
            type="date"
            label="Date de Fin"
            v-model="filter.date_fin"
            :min="filter.date_debut"
            hide-details
            clearable>
            </v-text-field>
        </div>
    </v-sheet>
        <v-data-table-server
            :headers="headers"
            :items-length="total"
            :items="purchases"
            loading-text="Chargement..."
            :loading="fetchingpurchases"
            hover
            :items-par-page="options.itemsperpage"
            :items-per-page-text="itemsperpagetext"
            :items-per-page-options="itemsperpageoptions"
            no-data-text="Aucune donnée disponible dans la table"
            class="border rounded shadow-lg text-sm"
            @update:options="options = $event"
        >
            <template v-slot:headers>
            <tr>
                <th v-for="header in headers">
                <span class="font-bold text-gray-600">{{ header.title.toUpperCase() }}</span>
                </th>
            </tr>
            </template>
            <template v-slot:top>
            <v-toolbar
                color="white"
                border="b"
                flat
            >
            <v-text-field
                prepend-inner-icon="mdi-magnify"
                v-model="options.search"
                variant="underline"
                label="Rechercher"
                clearable
                single-line
                hide-details
            ></v-text-field>
                <v-divider
                class="mx-4"
                inset
                vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <Link :href="route('purchases.create')">
                    <v-btn
                    prepend-icon="mdi-plus-box"
                    color="blue-accent-4"
                    class="mr-3"
                    variant="flat"
                    v-bind="props"
                    >
                    Nouvel achat
                    </v-btn>
            </Link>
            </v-toolbar>    
            </template> 
            <template v-slot:item="{ item }">
                <tr>
                <td :class="{'text-gray-400' : fetchingpurchases}">{{ item.numero }}</td>
                <td :class="{'text-gray-400' : fetchingpurchases}">{{ item.marche.name }}</td>
                <td :class="{'text-gray-400' : fetchingpurchases}">{{ item.supplier.name }}</td>
                <td :class="{'text-gray-400' : fetchingpurchases}">
                    <span v-if="item.payment.type == 'virement'">Virement</span>
                    <span v-if="item.payment.type == 'espece'">Espèce</span>
                    <span v-if="item.payment.type == 'cheque'">Chèque</span>
                    <span v-if="item.payment.type == 'versement'">Versement</span>
                    <span v-if="item.payment.type == 'effet'">Effet</span>
                </td>
                <td :class="{'text-gray-400' : fetchingpurchases}">{{ item.bank.code }}</td>
                <td :class="{'text-gray-400' : fetchingpurchases}"><span v-if="item.date">{{ formatedDate(item.date) }}</span><span v-else> - </span></td>
                <td :class="{'text-gray-400' : fetchingpurchases}">{{ (parseFloat(item.total)+parseFloat(item.total)*item.payment.taux_tva*0.01).toFixed(2) }} <span class=""> DH</span></td>
                <td>
                <v-tooltip
                location="bottom"
                origin
                >
                <template v-slot:activator="{ props }">
                <Link :href="route('purchases.show',item.id)">
                <v-btn
                    icon="mdi-eye"
                    variant="text"
                    v-bind="props"
                    size="small"
                    :disabled="fetchingpurchases"
                    color="grey-darken-3"
                    class="me-2"
                    >
                </v-btn>
                </Link>
                </template>
                <span>Détails</span>
                </v-tooltip>
                <Link :href="route('purchases.edit',item.id)">
                <v-tooltip
                location="bottom"
                origin
                >
                <template v-slot:activator="{ props }">
                <v-btn
                    icon="mdi-pencil"
                    variant="text"
                    v-bind="props"
                    size="small"
                    :disabled="fetchingpurchases"
                    color="grey-darken-3"
                    class="me-2"
                    >
                </v-btn>
                </template>
                <span>Editer</span>
                </v-tooltip>
                </Link>
                <v-tooltip
                location="bottom"
                origin
                >
                <template v-slot:activator="{ props }">
                    <v-btn
                    icon="mdi-delete"
                    variant="text"
                    :disabled="fetchingpurchases"
                    color="red"
                    @click="deleteItem(item)"
                    v-bind="props"
                    size="small"
                    class="me-2"
                    >
                    </v-btn>
                </template>
                <span>Supprimer</span>
                </v-tooltip>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="border-0 h-0"></td>
            </tr>
        </template>
        </v-data-table-server>
        <v-sheet color="grey-white" class="p-6 mt-4" elevation="4" border rounded>

            <h3 class="text-xl font-bold ">Total:</h3>
            <hr class="my-3">
            <div class="flex gap-2 justify-end">
                <div class="flex p-4 justify-center border items-center mb-5 rounded bg-gray-50">
                    <span class="text-gray-500 font-bold">Total HT: </span>&nbsp;
                    <span> {{totalht}} </span>
                    <span class="text-gray-500"> &nbsp;DH</span>
                </div>
                <div class="flex p-4 justify-center border items-center mb-5 rounded bg-gray-50">
                    <span class="text-gray-500 font-bold">Total TVA: </span>&nbsp;
                    <span> {{totaltva}} </span>
                    <span class="text-gray-500"> &nbsp;DH</span>
                </div>
                <div class="flex p-4 justify-center border items-center mb-5 rounded bg-gray-50">
                    <span class="text-gray-500 font-bold">Total TTC: </span>&nbsp;
                    <span> {{totalttc}} </span>
                    <span class="text-gray-500"> &nbsp;DH</span>
                </div>
            </div>
        </v-sheet>
        <v-dialog v-model="dialogDelete" max-width="550px">
            <v-card>
                <v-card-title class="my-2 text-h7">
                    <span class="rounded-full bg-opacity-20 p-2 mr-2 bg-red-500">
                        <v-icon color="red" size="small" class="mb-1" icon="mdi-alert-outline" variant="tonal"></v-icon>
                    </span>
                    Êtes-vous sur de vouloir supprimer cet achat?
                </v-card-title>
                <v-card-actions class="bg-gray-100  flex justify-end items-center border-t">
                    <v-btn color="gray" class="mt-2" variant="text" :disabled="deleting" @click="closeDelete">Annuler</v-btn>
                    <v-btn color="red" class="mt-2" active variant="flat" :loading="deleting" @click="deleteItemConfirm">Confirmer</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
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
