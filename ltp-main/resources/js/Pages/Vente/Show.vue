<script>
import AppLayout from '../../Layouts/AppLayout.vue';
import moment from 'moment';
export default{
    props:{
        vente:Object
    },
    components:{
        AppLayout,
    },
    data(){
        return{

        }
    },
    methods:{
        formatDate(date){
            return moment(date).format('DD/MM/YYYY')
        }
    }
}
</script>

<template>
    
    <AppLayout :title="'Détail de vente : '+ vente.numero">
        <div class="text-2xl text-gray-800 mb-5 text-center">Détail de vente: <span class="font-bold">{{vente.numero}}</span></div>
        <v-sheet color="grey-white" class="mb-5" border rounded>
            <h3 class="text-xl font-bold p-3">Informations relatives à la vente:</h3>
            <hr>
            <div class="grid grid-cols-1 sm:grid-cols-2">
                <div class="col-span-2 px-4 py-5 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Objet</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ vente.objet }}</dd>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2">
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Marché</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2"><span v-if="vente.marche">{{ vente.marche.name }}</span> <span v-else> - </span></dd>
                </div>
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Client</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">{{vente.client.name}}</dd>
                </div>
            </div>
        </v-sheet>
        <v-sheet color="grey-white" class="mb-5" border rounded>
            <h3 class="text-xl font-bold p-3">Articles:</h3>
            <hr>
            <v-table hover class="text-sm">
                <thead>
                <tr class="">
                    <th class="text-left">
                        <span class="font-bold">Désignation</span>
                    </th>
                    <th class="text-left">
                        <span class="font-bold">Quantité</span>
                    </th>
                    <th class="text-left">
                        <span class="font-bold">Unité</span>
                    </th>
                    <th class="text-left">
                        <span class="font-bold">Prix par unité</span>
                    </th>
                    <th class="text-left">
                        <span class="font-bold">Subtotal</span>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(item,index) in vente.products"
                    :key="index"
                >
                    <td class="border-b">{{ item.designation }}</td>
                    <td class="border-b">{{ item.quantity }}</td>
                    <td class="border-b">{{ item.unity}}</td>
                    <td class="border-b">{{ item.priceperunity}} <span class="text-gray-500">DH HT</span></td>
                    <td class="border-b">{{ item.subtotal }} <span class="text-gray-500">DH HT</span></td>
                </tr>
                </tbody>
                <template v-slot:bottom>
                    <div class="p-4 font-bold text-xl">Total:</div>
                    <hr>
                    <div class="flex gap-2 justify-end my-2 mr-2">
                    <div class="flex p-4 justify-center border items-center rounded bg-gray-50">
                        <span> {{vente.total}} </span>
                        <span class="text-gray-500"> &nbsp;DH HT</span>
                    </div>
                    <div class="flex p-4 justify-center border items-center rounded bg-gray-50">
                        <span> TVA : {{vente.taux_tva}} % </span>
                    </div>
                    <div class="flex p-4 justify-center border items-center rounded bg-gray-50">
                        <span> {{(parseFloat(vente.total) + parseFloat(vente.total)* vente.taux_tva*0.01).toFixed(2)}} </span>
                        <span class="text-gray-500"> &nbsp;DH TTC</span>
                    </div>
                </div>
                </template>
            </v-table>
            <hr class="mb-3">
            <div class="col-span-2 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Total TTC en texte</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ vente.total_text }}</dd>
                </div>
        </v-sheet>
        <v-sheet color="grey-white" class="mb-5" border rounded>
            <h3 class="text-xl font-bold p-3">Paiement:</h3>
            <hr>
            <div class="grid grid-cols-1 sm:grid-cols-2">
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">
                        <span v-if="vente.status == 'paid'" class="bg-green-200 text-green-600 rounded p-2"><v-icon>mdi-check</v-icon>Réglé</span>
                        <span v-if="vente.status == 'pending'" class="bg-orange-200 text-orange-600 rounded p-2"><v-icon>mdi-clock-outline</v-icon>Non réglé</span>
                    </dd>
                </div>
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Date de facturation</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">
                        <span v-if="vente.date">{{ formatDate(vente.date) }}</span> <span v-else> - </span>
                    </dd>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2">
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Facture</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">
                        <span v-if="!vente.proof_file">-</span>
                        <span v-else><a :href="route('facture.download',vente.id)"><span class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline">{{vente.original_file_name}}</span></a></span>
                    </dd>
                </div>
            </div>
        </v-sheet>
    </AppLayout>
</template>