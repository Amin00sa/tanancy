<script>
import AppLayout from '../../Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import moment from 'moment';
export default{
    props:{
        clients:Object,
        marches:Object,
        marche:String,
        client:String,
        status:String,
    },
    components:{
        AppLayout,
        Link,
    },
    data(){
        return{
        showfilter:false,
        loading:false,
        dialog:false,
        deleting:false,
        fetchingventes:true,
        ventes:[],
        total:0,
        totalht:0,
        totalttc:0,
        totaltva:0,
        filter:{
            date_debut:null,
            date_fin:null,
            client:null,
            marche:null,
            status:null,
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
            title: 'Numéro de vente',
            align: 'start',
            sortable: false,
            key: 'name',
            },
            {
            title: 'Objet',
            sortable: false,
            key: 'objet'
            },
            {
            title: 'Client',
            sortable: false,
            key: 'client',
            },
            {
            title: 'Date de facturation',
            sortable: false,
            key: 'date',
            },
            {
            title: 'Montant total TTC',
            sortable: false,
            key: 'ttc'
            },
            {
            title: 'Statut',
            sortable: false,
            key: 'statut'
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
        if(this.client){
            this.filter.client = parseInt(this.client)
        }
        if(this.marche){
            this.filter.marche = parseInt(this.marche)
        }
        if(this.status){
            this.filter.status = parseInt(this.status)
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
            return this.ventes.length
        },
    },

    methods: {
        async LoadData() {
            this.fetchingventes = true
            try {
                const result = await axios.get(`/webapiventes`,{
                    params: {
                        page : this.options.page,
                        itemsPerPage: this.options.itemsPerPage,
                        client : this.filter.client,
                        status: this.filter.status,
                        marche: this.filter.marche,
                        date_debut: this.filter.date_debut,
                        date_fin: this.filter.date_fin,
                        search : this.options.search,
                    }
                });
                this.ventes = []
                if (result.data.ventes.data.length) {
                    this.ventes = result.data.ventes.data
                    this.total = result.data.total
                    this.totalttc = result.data.totalttc
                    this.totalht = result.data.totalht
                    this.totaltva = result.data.totaltva
                }
            } catch (err) {
            }
            this.fetchingventes = false
        }, 
        deleteItem (item) {
            this.deletedIndex = this.ventes.indexOf(item)
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
            this.$inertia.delete(route('ventes.destroy', this.deletedId),{
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
    <AppLayout title="Ventes">
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Ventes</h2>
        </template>
        <div class="my-2 flex justify-center items-center">
                <v-btn v-bind="props" :active="showfilter" variant="tonal" color="grey-darken-4" @click="showfilter = !showfilter">
                    <template v-slot:prepend><v-badge v-if="filter.client || filter.status || filter.marche" dot><v-icon>mdi-filter-outline</v-icon></v-badge><v-icon v-else>mdi-filter-outline</v-icon></template>Filtre
                </v-btn>
            </div>
        <div class="flex flex-col justify-center mb-5 text-center">
            <VExpandTransition>
            <v-card v-if="showfilter" border elevation="0" class="text-start ">
                <v-card-text>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                    <label>Clients:</label>
                    <v-select
                        v-model="filter.client"
                        class="mt-1"
                        :items="clients"
                        item-value="id"
                        item-title="name"
                        no-data-text="Aucun client"
                        variant="outlined"
                        clearable
                    >
                    </v-select>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                    <label>Marches:</label>
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
                    <label>Statut de paiement:</label>
                    <v-btn-toggle
                        v-model="filter.status"
                        rounded="1"
                        variant="flat"
                        divided
                        class="mt-2 w-full border"
                    >
                        <v-btn value="paid" class="w-1/2 h-full" color="green-accent-4">
                            Réglé
                        </v-btn>
                        <v-btn value="pending" class="w-1/2 h-full" color="orange-accent-4">
                            Non réglé
                        </v-btn>
                    </v-btn-toggle>
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
            :items="ventes"
            loading-text="Chargement..."
            :loading="fetchingventes"
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
                <Link :href="route('ventes.create')">
                    <v-btn
                    prepend-icon="mdi-plus-box"
                    color="blue-accent-4"
                    class="mr-3"
                    variant="flat"
                    v-bind="props"
                    >
                    Nouvelle vente
                    </v-btn>
            </Link>
            </v-toolbar>    
            </template> 
            <template v-slot:item="{ item }">
                <tr>
                <td :class="{'text-gray-400' : fetchingventes}">{{ item.numero }}</td>
                <td :class="{'text-gray-400' : fetchingventes}">{{ item.objet }}</td>
                <td :class="{'text-gray-400' : fetchingventes}">{{ item.client.name }}<div v-if="item.client.ice" class="text-sm"><span class="font-bold">ICE:</span> {{ item.client.ice }}</div></td>
                <td :class="{'text-gray-400' : fetchingventes}"><span v-if="item.date">{{ formatedDate(item.date) }}</span><span v-else> - </span></td>
                <td :class="{'text-gray-400' : fetchingventes}">{{ (parseFloat(item.total) + (parseFloat(item.total)*parseInt(item.taux_tva)*0.01)).toFixed(2)}} DH</td>
                <td :class="{'text-gray-400' : fetchingventes}">
                    <span v-if="item.status == 'paid'" class="bg-green-200 text-green-600 rounded p-2"><v-icon>mdi-check</v-icon>Réglé</span>
                    <span v-if="item.status == 'pending'" class="bg-orange-200 text-orange-600 rounded p-2"><v-icon>mdi-clock-outline</v-icon>Non réglé</span>
                </td>
                <td>
                <v-tooltip
                location="bottom"
                origin
                >
                <template v-slot:activator="{ props }">
                <Link :href="route('ventes.show',item.id)">
                <v-btn
                    icon="mdi-eye"
                    variant="text"
                    v-bind="props"
                    size="small"
                    :disabled="fetchingventes"
                    color="grey-darken-3"
                    class="me-2"
                    >
                </v-btn>
                </Link>
                </template>
                <span>Détails</span>
                </v-tooltip>
                <Link :href="route('ventes.edit',item.id)">
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
                    :disabled="fetchingventes"
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
                    :disabled="fetchingventes"
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
                    Êtes-vous sur de vouloir supprimer cette vente?
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
