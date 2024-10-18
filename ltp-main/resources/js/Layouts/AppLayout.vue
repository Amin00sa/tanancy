<script>
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

export default{
    components:{
        Link,
        Head,
        ApplicationLogo,
    },
    props:{
        title: String
    },
    data(){
        return{
            rail: false,
        }
    },
    created() {
        const savedData = localStorage.getItem("myDataValue");
        if (savedData) {
        this.rail = JSON.parse(savedData);
        }
    },
    computed: {
    myDataValue() {
      return this.rail;
    }
    },
    beforeUnmount(){
        localStorage.setItem("myDataValue", JSON.stringify(this.myDataValue));
    },
    methods:{
        // switchToTeam(team){
        //     router.put(route('current-team.update'), {
        //         team_id: team.id,
        //     }, {
        //         preserveState: false,
        //     });
        // },
        logout(){
            router.post(route('logout'));
        },
    },
}
</script>

<template>
    <v-app>
        <Head :title="title" />
            <v-navigation-drawer
            permanent
            color="white"
            v-bind="props"
            :rail="rail"
            >
                <v-list class="text-sm whitespace-nowrap">
                    
                    <v-list-item value="menu" class="mb-2" :active="false" @click="rail = !rail">
                        <template v-if="!rail" #append><v-icon>mdi-menu</v-icon></template>
                        <template v-if="rail" #prepend><v-icon>mdi-menu</v-icon></template>
                    </v-list-item>
                    <hr/>
                    <v-tooltip
                        location="right"
                        :disabled="!rail"
                        >
                        <template v-slot:activator="{ props }">
                            <Link v-bind="props" :href="route('dashboard')"><v-list-item :active="route().current('dashboard')" prepend-icon="mdi-view-dashboard" value="dashboard" active-color="indigo-accent-4"><span>Tableau de bord</span></v-list-item></Link>
                        </template>
                        <template v-slot:default>
                            <span :isActive="$page.props.rail">Tableau de bord</span>
                        </template>
                        
                    </v-tooltip>
                    <v-tooltip
                        location="right"
                        :disabled="!rail"
                        >
                        <template v-slot:activator="{ props }">
                            <Link v-bind="props" :href="route('marches.index')"><v-list-item :active="route().current('marches.index')" prepend-icon="mdi-folder" value="marche" active-color="indigo-accent-4"><span>Marchés</span></v-list-item></Link>
                        </template>
                        <template v-slot:default>
                            <span :isActive="$page.props.rail">Marchés</span>
                        </template>
                        
                    </v-tooltip>
                    <v-tooltip
                        location="right"
                        :disabled="!rail"
                        >
                        <template v-slot:activator="{ props }">
                            <Link v-bind="props" :href="route('suppliers.index')"><v-list-item :active="route().current('suppliers.*')" prepend-icon="mdi-account-tie" value="Suppliers" active-color="indigo-accent-4"><span>Fournisseurs</span></v-list-item></Link>
                        </template>
                        <span>Fournisseurs</span>
                    </v-tooltip>
                    <v-tooltip
                        location="right"
                        :disabled="!rail"
                        >
                        <template v-slot:activator="{ props }">
                            <Link v-bind="props" :href="route('purchases.index')"><v-list-item :active="route().current('purchases.*')" prepend-icon="mdi-bank-transfer-out" value="Purchases" active-color="indigo-accent-4"><span>Achats</span></v-list-item></Link>
                        </template>
                        <span>Achats</span>
                    </v-tooltip>
                    <v-tooltip
                        location="right"
                        :disabled="!rail"
                        >
                        <template v-slot:activator="{ props }">
                            <Link v-bind="props" :href="route('ventes.index')"><v-list-item :active="route().current('ventes.*')" prepend-icon="mdi-bank-transfer-in" value="Ventes" active-color="indigo-accent-4"><span>Ventes</span></v-list-item></Link>
                        </template>
                        <span>Ventes</span>
                    </v-tooltip>
                    <v-tooltip
                        location="right"
                        :disabled="!rail"
                        >
                        <template v-slot:activator="{ props }">
                            <Link v-bind="props" :href="route('banks.index')"><v-list-item :active="route().current('banks.*')" prepend-icon="mdi-bank" value="Banks" active-color="indigo-accent-4"><span>Banques</span></v-list-item></Link>
                        </template>
                        <span>Banques</span>
                    </v-tooltip>
                </v-list>
                <!-- <template v-slot:append>
                    <v-list class="text-sm whitespace-nowrap">
                        <v-tooltip
                        v-if="$page.props.permissions.some(permission => permission === 'update settings')"
                            location="right"
                            :disabled="!rail"
                        >
                        <template v-slot:activator="{ props }">
                            <Link v-bind="props" :href="route('settings.index')"><v-list-item :active="route().current('settings.*')" prepend-icon="mdi-cog" value="Roles" active-color="indigo-accent-4"><span>Paramètres</span></v-list-item></Link>
                        </template>
                            <span>Paramètres</span>
                    </v-tooltip>
                    </v-list>
                </template> -->
            </v-navigation-drawer>
            <!-- Page Heading -->
            

            <!-- Page Content -->
            <v-main class="bg-slate-100">
                <div class="flex justify-between items-center py-2 px-8">
                    <header v-if="$slots.header">
                        <div class="max-w-7xl mx-auto py-6 px-2 sm:px-6 lg:px-8">
                            <slot name="header" />
                        </div>
                    </header>
                    <ApplicationLogo/>
                    <div class="rounded-full border ml-auto my-6 mx-2 sm:mx-6 lg:mx-8">
                        <v-menu>
                        <template v-slot:activator="{ props }">
                        <v-btn v-bind="props"
                            variant="text"
                            icon="mdi-account-circle"
                        ></v-btn>
                        </template>
                        <v-list elevation="3">
                            <Link :href="route('profile.edit')"><v-list-item prepend-icon="mdi-key" value="changepassword"><span class="text-sm">Changement de mot de passe</span></v-list-item></Link>
                            <v-list-item prepend-icon="mdi-logout" @click="logout" value="logout"><span class="text-sm">Se déconnecter</span></v-list-item>
                        </v-list>
                        </v-menu>
                    </div>
                </div>
                <div class="relative flex flex-col justify-center min-h-screen  pt-10 px-4 sm:px-6 lg:px-8">
                    <Transition name="slide">
                    <div v-if="$page.props.flash.success || $page.props.flash.error" class="text-sm w-fit z-50 mx-auto fixed inset-x-0 top-3">
                        <div class="flex items-center mr-3 rounded p-2 bg-white shadow-md">
                            <div v-if="$page.props.flash.success" class="p-2 bg-green-100 rounded-lg mr-2">
                                <v-icon color="green-accent-4">mdi-check-bold</v-icon>
                            </div>
                            <div v-if="$page.props.flash.error" class="p-2 bg-red-100 rounded-lg mr-2">
                                <v-icon color="red-darken-3">mdi-alert-circle</v-icon>
                            </div>
                            {{$page.props.flash.success}} {{$page.props.flash.error}}
                            <v-btn icon="mdi-close" variant="text" @click="$page.props.flash.success=null" class="ml-2"></v-btn>
                        </div>
                    </div>
                    </Transition>
                    <slot class="mb-5"/>
                    <footer class="text-center text-sm my-2 mt-auto text-gray-600"></footer>
                </div>
            </v-main>
    </v-app>
</template>
<style>
.slide-enter-active {
    transition: all 0.3s ease-out;
}

.slide-leave-active {
    transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-enter-from,
.slide-leave-to {
    transform: translateY(-200px);
    opacity: 0;
}

</style>