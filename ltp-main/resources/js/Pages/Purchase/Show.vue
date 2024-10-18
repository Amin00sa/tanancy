<script>
import AppLayout from '../../Layouts/AppLayout.vue';
import moment from 'moment';
export default{
    props:{
        purchase:Object
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
    
    <AppLayout :title="'Détail d\'achat : '+ purchase.numero">
        <div class="text-2xl text-gray-800 mb-5 text-center">Détail d'achat: <span class="font-bold">{{purchase.numero}}</span></div>
        <v-sheet color="grey-white" class="mb-5" border rounded>
            <h3 class="text-xl font-bold p-3">Informations relatives à l'achat:</h3>
            <hr>
            <div class="grid grid-cols-1 sm:grid-cols-2">
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Marché</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">{{purchase.marche.name}}</dd>
                </div>
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Fournisseur</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">{{purchase.supplier.name}}</dd>
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
                    v-for="(item,index) in purchase.products"
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
                        <span> {{purchase.total}} </span>
                        <span class="text-gray-500"> &nbsp;DH HT</span>
                    </div>
                    <div class="flex p-4 justify-center border items-center rounded bg-gray-50">
                        <span> TVA : {{purchase.payment.taux_tva}} % </span>
                    </div>
                    <div class="flex p-4 justify-center border items-center rounded bg-gray-50">
                        <span> {{(parseFloat(purchase.total) + parseFloat(purchase.total)* purchase.payment.taux_tva*0.01).toFixed(2)}} </span>
                        <span class="text-gray-500"> &nbsp;DH TTC</span>
                    </div>
                </div>
                </template>
            </v-table>
        </v-sheet>
        <v-sheet color="grey-white" class="mb-5" border rounded>
            <h3 class="text-xl font-bold p-3">Paiement:</h3>
            <hr>
            <div class="grid grid-cols-1 sm:grid-cols-2">
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Banque source</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">{{purchase.bank.name}} : <span class="text-xs">{{purchase.bank.rib}}</span></dd>
                </div>
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Type de paiement</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">
                        <span v-if="purchase.payment.type == 'virement'">Virement</span>
                        <span v-if="purchase.payment.type == 'espece'">Espèce</span>
                        <span v-if="purchase.payment.type == 'cheque'">Chèque</span>
                        <span v-if="purchase.payment.type == 'versement'">Versement</span>
                        <span v-if="purchase.payment.type == 'effet'">Effet</span>
                    </dd>
                </div>
            </div>
            <div v-if="purchase.payment.type === 'cheque' || purchase.payment.type ===  'effet'" class="grid grid-cols-1 sm:grid-cols-2">
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Paiement immédiat</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2"><span v-if="purchase.payment.paiement_immediat">Oui</span><span v-else>Non</span></dd>
                </div>
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Date d'échéance</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2"><span v-if="purchase.payment.paiement_immediat">-</span> <span v-else>{{formatDate(purchase.payment.echeance_date)}}</span></dd>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2">
                <div class="col-span-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Justificatif</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">
                        <span v-if="!purchase.payment.proof_file">-</span>
                        <span v-else><a :href="route('proof.download',purchase.payment.id)"><span class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline">{{purchase.payment.original_file_name}}</span></a></span>
                    </dd>
                </div>
            </div>
        </v-sheet>
    </AppLayout>
</template>