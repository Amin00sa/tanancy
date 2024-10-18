<script>
import AppLayout from '../../Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
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
        fetchingsuppliers:true,
        storevalid:false,
        updatevalid:false,
        suppliers:[],
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
            title: 'Téléphone',
            sortable: false,
            key: 'code',
            },
            {
            title: 'Adresse',
            sortable: false,
            key: 'rib'
            },
            { title: 'Actions',
             key: 'actions',
             align: 'center',
             sortable: false 
            },
        ],
        defaultItem:{
            name: '',
            adresse: '',
            telephone: '',
            bank_name: '',
            bank_code: '',
            bank_rib: '',
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

            return 'Le nom du fournisseur est obligatoire'
            }
        ],
        bank_nameRules: [
            value => {
            if (value) return true

            return 'Le nom de la banque est obligatoire'
            }
        ],
        bank_codeRules: [
            value => {
            if (value) return true

            return 'Le code de la banque est obligatoire'
            }
        ],
        bank_ribRules: [
            value => {
            if (value) return true

            return 'Le RIB de la banque est obligatoire'
            },
            value => {
            if (!/\D/.test(value)) return true

            return 'Le RIB ne peut contenir que des chiffres'
            },
            value => {
            if (value?.length <= 24) return true

            return 'Vous ne pouvez pas dépasser 24 chiffres'
            },
            value => {
            if (value?.length === 24) return true

            return 'Le RIB doit contenir 24 chiffres'
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
            return this.suppliers.length
        },
        formTitle () {
            return this.editedIndex === -1 ? 'Nouveau fournisseur' : 'Modification de founrisseur'
        },
    },

    methods: {
        async LoadData() {
            this.fetchingsuppliers = true
            try {
                const result = await axios.get(`/webapisuppliers`,{
                    params: {
                        page:this.options.page,
                        itemsPerPage:this.options.itemsPerPage,
                        search:this.options.search,
                    }
                });
                this.suppliers = []
                if (result.data.suppliers.data.length) {
                    this.suppliers = result.data.suppliers.data
                    this.total = result.data.total
                }
            } catch (err) {
            }
            this.fetchingsuppliers = false
        }, 
        store(){
            this.$refs.storeform.validate().then(() => {
                if(this.storevalid){
                this.loading = true
                if(this.editedIndex === -1){
                    this.$inertia.post(route('suppliers.store'),{
                    name: this.editedItem.name,
                    adresse: this.editedItem.adresse,
                    telephone: this.editedItem.telephone,
                    bank_name: this.editedItem.bank_name,
                    bank_code: this.editedItem.bank_code,
                    bank_rib: this.editedItem.bank_rib
                },{
                    onSuccess: async () => {
                    this.loading = false
                    this.close()
                    await this.LoadData()
                    },
                    preserveScroll: true,
                })
                }else{
                    this.$inertia.post(route('suppliers.update',this.editedId),{
                    _method: 'put',
                    name: this.editedItem.name,
                    adresse: this.editedItem.adresse,
                    telephone: this.editedItem.telephone,
                    bank_name: this.editedItem.bank_name,
                    bank_code: this.editedItem.bank_code,
                    bank_rib: this.editedItem.bank_rib
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
            this.deletedIndex = this.suppliers.indexOf(item)
            this.deletedId = item.id
            this.dialogDelete = true
        },
        closeDelete(){
            this.deletedId = null
            this.dialogDelete = false
        },
        deleteItemConfirm () {
            this.deleting = true
            this.$inertia.delete(route('suppliers.destroy', this.deletedId),{
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
            this.editedIndex = this.suppliers.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.editedId = item.id
            this.dialog = true
        },
    },
}
</script>

<template>
    <AppLayout title="Fournisseurs">
        <template #header>
            <h1 class="font-semibold text-3xl text-gray-800 leading-tight">Fournisseurs</h1>
        </template>
        <v-data-table-server
            :headers="headers"
            :items-length="total"
            :items="suppliers"
            loading-text="Chargement..."
            :loading="fetchingsuppliers"
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
                    prepend-icon="mdi-account-plus"
                    color="blue-accent-4"
                    class="mr-3"
                    variant="flat"
                    v-bind="props"
                    >
                    Nouveau fournisseur
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
                            placeholder="Société X"
                            :rules="nameRules"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="6"
                        >
                            <v-text-field
                            v-model="editedItem.telephone"
                            variant="outlined"
                            label="Téléphone"
                            placeholder="0666666666"
                            ></v-text-field>
                        </v-col>
                        </v-row>
                        <v-row>
                        <v-col
                            cols="12"
                        >
                            <v-text-field
                            v-model="editedItem.adresse"
                            variant="outlined"
                            class="mt-1"
                            label="Adresse"
                            ></v-text-field>
                        </v-col>
                        </v-row>
                        <v-row>
                        <v-col
                            cols="12"
                            sm="6"
                        >
                            <v-text-field
                            v-model="editedItem.bank_name"
                            variant="outlined"
                            class="mt-1"
                            label="Nom de la banque"
                            :rules="bank_nameRules"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="6"
                        >
                            <v-text-field
                            v-model="editedItem.bank_code"
                            variant="outlined"
                            class="mt-1"
                            label="Code de la banque"
                            :rules="bank_codeRules"
                            ></v-text-field>
                        </v-col>
                        </v-row>
                        <v-row>
                        <v-col
                            cols="12"
                        >
                            <v-text-field
                            v-model="editedItem.bank_rib"
                            variant="outlined"
                            class="mt-1"
                            counter="24"
                            label="RIB"
                            :rules="bank_ribRules"
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
                <td :class="{'text-gray-400' : fetchingsuppliers}">{{ item.name }}</td>
                <td :class="{'text-gray-400' : fetchingsuppliers}">{{ item.telephone }}</td>
                <td :class="{'text-gray-400' : fetchingsuppliers}">{{ item.adresse }}</td>
                <td>
                <v-tooltip
                location="bottom"
                origin
                >
                <template v-slot:activator="{ props }">
                <Link :href="route('purchases.index',{supplier:item.id})">
                <v-btn
                    icon="mdi-bank-transfer-out"
                    variant="text"
                    v-bind="props"
                    size="small"
                    :disabled="fetchingsuppliers"
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
                <v-btn
                    icon="mdi-pencil"
                    @click=editItem(item)
                    variant="text"
                    v-bind="props"
                    size="small"
                    :disabled="fetchingfournisseurs"
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
                    :disabled="fetchingfournisseurs"
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
                    Êtes-vous sur de vouloir supprimer ce fournisseur?
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
