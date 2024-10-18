<script>
import AppLayout from '../../Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import moment from 'moment';
export default{
    components:{
        AppLayout,
        Link,
    },
    data(){
        return{
        loading:false,
        dialog:false,
        deleting:false,
        fetchingmarches:true,
        storevalid:false,
        updatevalid:false,
        marches:[],
        total:0,
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
            title: 'Nom',
            align: 'start',
            sortable: false,
            key: 'name',
            },
            {
            title: 'Maître d\'ouvrage',
            sortable: false,
            key: 'maitre_ouvrage',
            },
            {
            title: 'ville',
            sortable: false,
            key: 'ville'
            },
            {
            title: 'Date d\'ordre de service',
            sortable: false,
            key: 'date_service'
            },
            {
            title: 'Montant',
            sortable: false,
            key: 'montant'
            },
            { title: 'Actions',
             key: 'actions',
             align: 'center',
             sortable: false 
            },
        ],
        defaultItem:{
            name: '',
            maitre_ouvrage: '',
            ville: '',
            date_service: '',
            montant: ''
        },
        deletedId: -1,
        deletedIndex: -1,
        editedIndex: -1,
        editedId: -1,
        editedItem: [],
        editedIndex: -1,
        nameRules: [
            value => {
            if (value) return true

            return 'Le nom de marche est obligatoire'
            }
        ],
        maitre_ouvrageRules: [
            value => {
            if (value) return true

            return 'Le maître d\'ouvrage est obligatoire'
            }
        ],
        date_serviceRules: [
            value => {
            if (value) return true

            return 'La date d\'ordre de service est obligatoire'
            }
        ],
    }   
    },

    watch: {
        dialogDelete (val) {
            val || this.closeDelete()
        },
    },
    mounted(){
        this.LoadData()
        this.$watch('options',{
            handler(){
                return this.LoadData()
            },
            deep: true,
        },)
    },

    computed:{
        count(){
            return this.marches.length
        },
        formTitle () {
            return this.editedIndex === -1 ? 'Nouveau marché' : 'Modification de marché'
        },
        

    },

    methods: {
        async LoadData() {
            this.fetchingmarches = true
            try {
                const result = await axios.get(`/webapimarches`,{
                    params: {
                        page:this.options.page,
                        itemsPerPage:this.options.itemsPerPage,
                        search:this.options.search,
                    }
                });
                this.marches = []
                if (result.data.marches.data.length) {
                    this.marches = result.data.marches.data
                    this.total = result.data.total
                }
            } catch (err) {
            }
            this.fetchingmarches = false
        }, 
        store(){
            this.$refs.storeform.validate().then(() => {
                if(this.storevalid){
                this.loading = true
                if(this.editedIndex === -1){
                    this.$inertia.post(route('marches.store'),{
                    name: this.editedItem.name,
                    maitre_ouvrage: this.editedItem.maitre_ouvrage,
                    ville: this.editedItem.ville,
                    date_service: this.editedItem.date_service,
                    montant: this.editedItem.montant
                },{
                    onSuccess: async () => {
                    this.loading = false
                    this.close()
                    await this.LoadData()
                    },
                    preserveScroll: true,
                })
                }else{
                    this.$inertia.post(route('marches.update',this.editedId),{
                    _method: 'put',
                    name: this.editedItem.name,
                    maitre_ouvrage: this.editedItem.maitre_ouvrage,
                    ville: this.editedItem.ville,
                    date_service: this.editedItem.date_service,
                    montant: this.editedItem.montant
                },{
                    onSuccess: async () => {
                    this.loading = false
                    this.close()
                    await this.LoadData()
                    },
                    preserveScroll: true,
                })
                }
                
            }
            })
        },
        close(){
            this.dialog = false
            this.editedItem = Object.assign({},this.defaultItem)
            this.editedIndex = -1
            this.editedId = -1
        },
        deleteItem (item) {
            this.deletedIndex = this.marches.indexOf(item)
            this.deletedId = item.id
            this.dialogDelete = true
        },
        closeDelete(){
            this.deletedId = null
            this.dialogDelete = false
        },
        deleteItemConfirm () {
            this.deleting = true
            this.$inertia.delete(route('marches.destroy', this.deletedId),{
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
        editItem (item) {
            this.editedIndex = this.marches.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.editedId = item.id
            this.dialog = true
        },
        formatedDate(date){
            return moment(date).format('DD/MM/YYYY')
        }
    },
}
</script>

<template>
    <AppLayout title="Marchés">
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Marchés</h2>
        </template>
        <v-data-table-server
            :headers="headers"
            :items-length="total"
            :items="marches"
            loading-text="Chargement..."
            :loading="fetchingmarches"
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
            <v-dialog
                v-model="dialog"
                max-width="500px"
                persistent
                >
                <template v-slot:activator="{ props }">
                    <v-btn
                    prepend-icon="mdi-folder-plus"
                    color="blue-accent-4"
                    class="mr-3"
                    variant="flat"
                    v-bind="props"
                    >
                    Nouveau marché
                    </v-btn>
                </template>
                <v-form v-model="storevalid" :disabled="loading" @submit.prevent="store" ref="storeform">
                <v-card>
                    <v-card-title class="border-b bg-gray-50">
                    <span class="text-h6 font-bold">{{ formTitle }}</span>
                    </v-card-title>

                    <v-card-text class="max-h-96 overflow-y-auto">
                    <v-container>
                        <v-row>
                        <v-col
                            cols="12"
                            sm="6"
                        >
                            <v-text-field
                            v-model="editedItem.name"
                            label="Nom"
                            variant="outlined"
                            :rules="nameRules"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="6"
                        >
                            <v-text-field
                            v-model="editedItem.maitre_ouvrage"
                            variant="outlined"
                            label="Maître d'ouvrage"
                            :rules="maitre_ouvrageRules"
                            ></v-text-field>
                        </v-col>
                        </v-row>
                        <v-row>
                        <v-col
                            cols="12"
                            sm="6"
                        >
                            <v-text-field
                            v-model="editedItem.ville"
                            variant="outlined"
                            label="Ville"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="6"
                        >
                            <v-text-field
                            v-model="editedItem.montant"
                            variant="outlined"
                            label="Montant"
                            ></v-text-field>
                        </v-col>
                        </v-row>
                        <v-row>
                        <v-col
                            cols="12"
                            sm="12"
                        >
                            <v-text-field
                            v-model="editedItem.date_service"
                            type="date"
                            variant="outlined"
                            label="Date d'ordre de service"
                            :rules="date_serviceRules"
                            ></v-text-field>
                        </v-col>
                        </v-row>
                    </v-container>
                    </v-card-text>

                    <v-card-actions class="bg-gray-100  flex justify-end items-center border-t">
                    <v-btn color="gray" class="mt-2" variant="text" :disabled="loading" @click="close">Annuler</v-btn>
                    <v-btn color="blue-accent-4" type="submit" class="mt-2" active variant="flat" :loading="loading">Enregistrer</v-btn>
                    </v-card-actions>
                </v-card>
                </v-form>
                </v-dialog>
            </v-toolbar>
            </template>
            <template v-slot:item="{ item }">
                <tr>
                <td :class="{'text-gray-400' : fetchingmarches}">{{ item.name }}</td>
                <td :class="{'text-gray-400' : fetchingmarches}">{{ item.maitre_ouvrage }}</td>
                <td :class="{'text-gray-400' : fetchingmarches}">{{ item.ville }}</td>
                <td :class="{'text-gray-400' : fetchingmarches}">{{ formatedDate(item.date_service) }}</td>
                <td :class="{'text-gray-400' : fetchingmarches}">{{ item.montant }}</td>
                <td>
                <v-tooltip
                location="bottom"
                origin
                >
                <template v-slot:activator="{ props }">
                <Link :href="route('purchases.index',{marche:item.id})">
                <v-btn
                    icon="mdi-bank-transfer-out"
                    variant="text"
                    v-bind="props"
                    size="small"
                    :disabled="fetchingmarches"
                    color="grey-darken-3"
                    class="me-2"
                    >
                </v-btn>
                </Link>
                </template>
                <span>Achats</span>
                </v-tooltip>
                <v-tooltip
                location="bottom"
                origin
                >
                <template v-slot:activator="{ props }">
                <Link :href="route('ventes.index',{marche:item.id})">
                <v-btn
                    icon="mdi-bank-transfer-in"
                    variant="text"
                    v-bind="props"
                    size="small"
                    :disabled="fetchingmarches"
                    color="grey-darken-3"
                    class="me-2"
                    >
                </v-btn>
                </Link>
                </template>
                <span>Vente</span>
                </v-tooltip>
                <v-tooltip
                location="bottom"
                origin
                >
                <template v-slot:activator="{ props }">
                <v-btn
                    icon="mdi-pencil"
                    @click=editItem(item)
                    variant="text"
                    v-bind="props"
                    size="small"
                    :disabled="fetchingmarches"
                    color="grey-darken-3"
                    class="me-2"
                    >
                </v-btn>
                </template>
                <span>Editer</span>
                </v-tooltip>
                <v-tooltip
                location="bottom"
                origin
                >
                <template v-slot:activator="{ props }">
                    <v-btn
                    icon="mdi-delete"
                    variant="text"
                    :disabled="fetchingmarches"
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
        <v-dialog v-model="dialogDelete" max-width="550px">
            <v-card>
                <v-card-title class="my-2 text-h7">
                    <span class="rounded-full bg-opacity-20 p-2 mr-2 bg-red-500">
                        <v-icon color="red" size="small" class="mb-1" icon="mdi-alert-outline" variant="tonal"></v-icon>
                    </span>
                    Êtes-vous sur de vouloir supprimer ce marché?
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
</style>
