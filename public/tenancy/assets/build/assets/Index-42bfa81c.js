import{A as q}from"./AppLayout-df16f9ef.js";import{j as z,k as n,o as r,c as b,w as a,b as t,a as l,d,m as f,g as m,f as p,l as H,F as M,n as h,t as c}from"./app-8858eae8.js";import{h as P}from"./moment-fbc5633a.js";import{_ as O}from"./_plugin-vue_export-helper-c27b6911.js";import"./ApplicationLogo-e011a720.js";const Y={props:{suppliers:Object,marches:Object,banks:Object,supplier:String,marche:String,bank:String},components:{AppLayout:q,Link:z},data(){return{showfilter:!1,totalht:0,totalttc:0,totaltva:0,loading:!1,dialog:!1,deleting:!1,fetchingpurchases:!0,purchases:[],total:0,filter:{marche:null,supplier:null,bank:null,date_debut:null,date_fin:null},options:{},dialogDelete:!1,itemsperpageoptions:[{title:"10",value:10},{title:"25",value:25},{title:"50",value:50},{title:"100",value:100},{title:"Tous",value:-1}],itemsperpagetext:"Lignes par page",headers:[{title:"Numéro d'achat",align:"start",sortable:!1,key:"name"},{title:"Marché",sortable:!1,key:"marche"},{title:"Fournisseur",sortable:!1,key:"supplier"},{title:"Type de paiement",sortable:!1,key:"paiement"},{title:"Banque",sortable:!1,key:"bank"},{title:"Date d'achat",sortable:!1,key:"date"},{title:"Montant Total TTC",sortable:!1,key:"montant"},{title:"Actions",key:"actions",align:"center",sortable:!1}],deletedId:-1,deletedIndex:-1}},watch:{dialogDelete(o){o||this.closeDelete()}},mounted(){this.bank&&(this.filter.bank=parseInt(this.bank)),this.marche&&(this.filter.marche=parseInt(this.marche)),this.supplier&&(this.filter.supplier=parseInt(this.supplier)),this.LoadData(),this.$watch("filter",{handler(){return this.LoadData()},deep:!0}),this.$watch("options",{handler(){return this.LoadData()},deep:!0})},computed:{count(){return this.purchases.length}},methods:{async LoadData(){this.fetchingpurchases=!0;try{const o=await axios.get("/webapipurchases",{params:{page:this.options.page,itemsPerPage:this.options.itemsPerPage,marche:this.filter.marche,search:this.options.search,supplier:this.filter.supplier,date_debut:this.filter.date_debut,date_fin:this.filter.date_fin,bank:this.filter.bank}});this.purchases=[],o.data.purchases.data.length&&(this.purchases=o.data.purchases.data,this.total=o.data.total,this.totalttc=o.data.totalttc,this.totalht=o.data.totalht,this.totaltva=o.data.totaltva)}catch{}this.fetchingpurchases=!1},deleteItem(o){this.deletedIndex=this.purchases.indexOf(o),this.deletedId=o.id,this.dialogDelete=!0},closeDelete(){this.deletedId=null,this.dialogDelete=!1},deleteItemConfirm(){this.deleting=!0,this.$page.props.flash.success=null,this.$inertia.delete(route("purchases.destroy",this.deletedId),{onSuccess:()=>{this.deleting=!1,this.closeDelete(),this.count===0&&this.options.page>1?this.options.page--:this.LoadData()},preserveScroll:!0})},formatedDate(o){return P(o).format("DD/MM/YYYY")}}},R=t("h2",{class:"font-semibold text-3xl text-gray-800 leading-tight"},"Achats",-1),G={class:"my-2 flex justify-center items-center"},J={class:"flex flex-col justify-center mb-5 text-center"},K={class:"grid grid-cols-2 gap-4"},Q={class:"col-span-2 md:col-span-1"},W=t("label",null,"Marché:",-1),X={class:"col-span-2 md:col-span-1"},Z=t("label",null,"Fournisseur:",-1),$={class:"col-span-2 md:col-span-1"},ee=t("label",null,"Banque:",-1),te={class:"flex justify-between gap-10"},se={class:"font-bold text-gray-600"},le={key:0},ae={key:1},oe={key:2},ne={key:3},ie={key:4},re={key:0},de={key:1},ce=t("span",{class:""}," DH",-1),pe=t("span",null,"Détails",-1),ue=t("span",null,"Editer",-1),he=t("span",null,"Supprimer",-1),me=t("tr",null,[t("td",{colspan:"4",class:"border-0 h-0"})],-1),_e=t("h3",{class:"text-xl font-bold"},"Total:",-1),fe=t("hr",{class:"my-3"},null,-1),ge={class:"flex gap-2 justify-end"},be={class:"flex p-4 justify-center border items-center mb-5 rounded bg-gray-50"},ve=t("span",{class:"text-gray-500 font-bold"},"Total HT: ",-1),ye=t("span",{class:"text-gray-500"},"  DH",-1),xe={class:"flex p-4 justify-center border items-center mb-5 rounded bg-gray-50"},ke=t("span",{class:"text-gray-500 font-bold"},"Total TVA: ",-1),Ve=t("span",{class:"text-gray-500"},"  DH",-1),De={class:"flex p-4 justify-center border items-center mb-5 rounded bg-gray-50"},Ce=t("span",{class:"text-gray-500 font-bold"},"Total TTC: ",-1),we=t("span",{class:"text-gray-500"},"  DH",-1),Te={class:"rounded-full bg-opacity-20 p-2 mr-2 bg-red-500"};function Ie(o,i,v,Ae,e,g){const y=n("v-icon"),T=n("v-badge"),u=n("v-btn"),x=n("v-select"),I=n("v-card-text"),C=n("v-card"),A=n("VExpandTransition"),k=n("v-text-field"),w=n("v-sheet"),L=n("v-divider"),j=n("v-spacer"),V=n("Link"),F=n("v-toolbar"),D=n("v-tooltip"),U=n("v-data-table-server"),S=n("v-card-title"),B=n("v-card-actions"),E=n("v-dialog"),N=n("AppLayout");return r(),b(N,{title:"Achats"},{header:a(()=>[R]),default:a(()=>[t("div",G,[l(u,f(o.props,{active:e.showfilter,variant:"tonal",color:"grey-darken-4",onClick:i[0]||(i[0]=s=>e.showfilter=!e.showfilter)}),{prepend:a(()=>[e.filter.marche||e.filter.bank||e.filter.supplier?(r(),b(T,{key:0,dot:""},{default:a(()=>[l(y,null,{default:a(()=>[d("mdi-filter-outline")]),_:1})]),_:1})):(r(),b(y,{key:1},{default:a(()=>[d("mdi-filter-outline")]),_:1}))]),default:a(()=>[d("Filtre ")]),_:1},16,["active"])]),t("div",J,[l(A,null,{default:a(()=>[e.showfilter?(r(),b(C,{key:0,border:"",elevation:"0",class:"text-start"},{default:a(()=>[l(I,null,{default:a(()=>[t("div",K,[t("div",Q,[W,l(x,{modelValue:e.filter.marche,"onUpdate:modelValue":i[1]||(i[1]=s=>e.filter.marche=s),class:"mt-1",items:v.marches,"item-value":"id","item-title":"name","no-data-text":"Aucun marché",variant:"outlined",clearable:""},null,8,["modelValue","items"])]),t("div",X,[Z,l(x,{modelValue:e.filter.supplier,"onUpdate:modelValue":i[2]||(i[2]=s=>e.filter.supplier=s),class:"mt-1",items:v.suppliers,"item-value":"id","item-title":"name","no-data-text":"Aucun fournisseur",variant:"outlined",clearable:""},null,8,["modelValue","items"])]),t("div",$,[ee,l(x,{modelValue:e.filter.bank,"onUpdate:modelValue":i[3]||(i[3]=s=>e.filter.bank=s),class:"mt-1",items:v.banks,"item-value":"id","item-title":"name","no-data-text":"Aucune banque",variant:"outlined",clearable:""},null,8,["modelValue","items"])])])]),_:1})]),_:1})):m("",!0)]),_:1})]),l(w,{border:"",class:"rounded p-4 mb-6"},{default:a(()=>[t("div",te,[l(k,{type:"date",label:"Date de début",modelValue:e.filter.date_debut,"onUpdate:modelValue":i[4]||(i[4]=s=>e.filter.date_debut=s),max:e.filter.date_fin,"hide-details":"",clearable:""},null,8,["modelValue","max"]),l(k,{type:"date",label:"Date de Fin",modelValue:e.filter.date_fin,"onUpdate:modelValue":i[5]||(i[5]=s=>e.filter.date_fin=s),min:e.filter.date_debut,"hide-details":"",clearable:""},null,8,["modelValue","min"])])]),_:1}),l(U,{headers:e.headers,"items-length":e.total,items:e.purchases,"loading-text":"Chargement...",loading:e.fetchingpurchases,hover:"","items-par-page":e.options.itemsperpage,"items-per-page-text":e.itemsperpagetext,"items-per-page-options":e.itemsperpageoptions,"no-data-text":"Aucune donnée disponible dans la table",class:"border rounded shadow-lg text-sm","onUpdate:options":i[7]||(i[7]=s=>e.options=s)},{headers:a(()=>[t("tr",null,[(r(!0),p(M,null,H(e.headers,s=>(r(),p("th",null,[t("span",se,c(s.title.toUpperCase()),1)]))),256))])]),top:a(()=>[l(F,{color:"white",border:"b",flat:""},{default:a(()=>[l(k,{"prepend-inner-icon":"mdi-magnify",modelValue:e.options.search,"onUpdate:modelValue":i[6]||(i[6]=s=>e.options.search=s),variant:"underline",label:"Rechercher",clearable:"","single-line":"","hide-details":""},null,8,["modelValue"]),l(L,{class:"mx-4",inset:"",vertical:""}),l(j),l(V,{href:o.route("purchases.create")},{default:a(()=>[l(u,f({"prepend-icon":"mdi-plus-box",color:"blue-accent-4",class:"mr-3",variant:"flat"},o.props),{default:a(()=>[d(" Nouvel achat ")]),_:1},16)]),_:1},8,["href"])]),_:1})]),item:a(({item:s})=>[t("tr",null,[t("td",{class:h({"text-gray-400":e.fetchingpurchases})},c(s.numero),3),t("td",{class:h({"text-gray-400":e.fetchingpurchases})},c(s.marche.name),3),t("td",{class:h({"text-gray-400":e.fetchingpurchases})},c(s.supplier.name),3),t("td",{class:h({"text-gray-400":e.fetchingpurchases})},[s.payment.type=="virement"?(r(),p("span",le,"Virement")):m("",!0),s.payment.type=="espece"?(r(),p("span",ae,"Espèce")):m("",!0),s.payment.type=="cheque"?(r(),p("span",oe,"Chèque")):m("",!0),s.payment.type=="versement"?(r(),p("span",ne,"Versement")):m("",!0),s.payment.type=="effet"?(r(),p("span",ie,"Effet")):m("",!0)],2),t("td",{class:h({"text-gray-400":e.fetchingpurchases})},c(s.bank.code),3),t("td",{class:h({"text-gray-400":e.fetchingpurchases})},[s.date?(r(),p("span",re,c(g.formatedDate(s.date)),1)):(r(),p("span",de," - "))],2),t("td",{class:h({"text-gray-400":e.fetchingpurchases})},[d(c((parseFloat(s.total)+parseFloat(s.total)*s.payment.taux_tva*.01).toFixed(2))+" ",1),ce],2),t("td",null,[l(D,{location:"bottom",origin:""},{activator:a(({props:_})=>[l(V,{href:o.route("purchases.show",s.id)},{default:a(()=>[l(u,f({icon:"mdi-eye",variant:"text"},_,{size:"small",disabled:e.fetchingpurchases,color:"grey-darken-3",class:"me-2"}),null,16,["disabled"])]),_:2},1032,["href"])]),default:a(()=>[pe]),_:2},1024),l(V,{href:o.route("purchases.edit",s.id)},{default:a(()=>[l(D,{location:"bottom",origin:""},{activator:a(({props:_})=>[l(u,f({icon:"mdi-pencil",variant:"text"},_,{size:"small",disabled:e.fetchingpurchases,color:"grey-darken-3",class:"me-2"}),null,16,["disabled"])]),default:a(()=>[ue]),_:1})]),_:2},1032,["href"]),l(D,{location:"bottom",origin:""},{activator:a(({props:_})=>[l(u,f({icon:"mdi-delete",variant:"text",disabled:e.fetchingpurchases,color:"red",onClick:Le=>g.deleteItem(s)},_,{size:"small",class:"me-2"}),null,16,["disabled","onClick"])]),default:a(()=>[he]),_:2},1024)])]),me]),_:1},8,["headers","items-length","items","loading","items-par-page","items-per-page-text","items-per-page-options"]),l(w,{color:"grey-white",class:"p-6 mt-4",elevation:"4",border:"",rounded:""},{default:a(()=>[_e,fe,t("div",ge,[t("div",be,[ve,d("  "),t("span",null,c(e.totalht),1),ye]),t("div",xe,[ke,d("  "),t("span",null,c(e.totaltva),1),Ve]),t("div",De,[Ce,d("  "),t("span",null,c(e.totalttc),1),we])])]),_:1}),l(E,{modelValue:e.dialogDelete,"onUpdate:modelValue":i[8]||(i[8]=s=>e.dialogDelete=s),"max-width":"550px"},{default:a(()=>[l(C,null,{default:a(()=>[l(S,{class:"my-2 text-h7"},{default:a(()=>[t("span",Te,[l(y,{color:"red",size:"small",class:"mb-1",icon:"mdi-alert-outline",variant:"tonal"})]),d(" Êtes-vous sur de vouloir supprimer cet achat? ")]),_:1}),l(B,{class:"bg-gray-100 flex justify-end items-center border-t"},{default:a(()=>[l(u,{color:"gray",class:"mt-2",variant:"text",disabled:e.deleting,onClick:g.closeDelete},{default:a(()=>[d("Annuler")]),_:1},8,["disabled","onClick"]),l(u,{color:"red",class:"mt-2",active:"",variant:"flat",loading:e.deleting,onClick:g.deleteItemConfirm},{default:a(()=>[d("Confirmer")]),_:1},8,["loading","onClick"])]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})}const Ee=O(Y,[["render",Ie]]);export{Ee as default};
