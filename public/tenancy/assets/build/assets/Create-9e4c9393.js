import{A as T}from"./AppLayout-df16f9ef.js";import{T as w,k as u,o as d,c as x,w as n,a as l,f as c,b as a,t as V,g as _,l as D,F as A,d as U,e as E,D as F,E as H}from"./app-8858eae8.js";import{h as I}from"./moment-fbc5633a.js";import{_ as B}from"./_plugin-vue_export-helper-c27b6911.js";import"./ApplicationLogo-e011a720.js";const S={props:{suppliers:Object,banks:Object,marches:Object},components:{AppLayout:T},data(){return{files:[],paiement_immediat:!1,valid:!1,marcheRules:[o=>o?!0:"Le marché est obligatoire"],supplierRules:[o=>o?!0:"Le fournisseur est obligatoire"],unityRules:[o=>o?!0:"L'unité est obligatoire"],designationRules:[o=>o?!0:"La désignation est obligatoire"],quantityRules:[o=>o?!0:"La quantité est obligatoire"],priceperunityRules:[o=>/^-?\d+(\.\d+)?$/.test(o)?!0:"La prix par unité doit être numérique"],tvaRules:[o=>o||o===0?!0:"Le taux de TVA est obligatoire"],bankRules:[o=>o?!0:"La banque source est obligatoire"],typeRules:[o=>o?!0:"Le type de paiement est obligatoire"],dateRules:[o=>o?!0:"La date d'achat est obligatoire"],echeance_dateRules:[o=>o||this.form.paiement_immediat?!0:"La date d'échéance est obligatoire"]}},setup(){return{form:w({marche_id:null,supplier_id:null,bank_id:null,products:[],taux_tva:null,paiement_immediat:!1,echeance_date:null,total:null,date:null,type:null,proof_file:null,motif:null})}},computed:{totalht(){let o=0;return this.form.products.forEach(i=>{if(i.quantity&&i.priceperunity)o+=i.quantity*i.priceperunity;else return 0}),o},now(){return I().format("YYYY-MM-DD")}},methods:{submit(){this.form.total=this.totalht,this.$refs.formulaire.validate().then(()=>{this.valid&&this.form.post(route("purchases.store"))})},setfile(){this.files.length?this.form.proof_file=this.files[0]:this.form.proof_file=null}}},r=o=>(F("data-v-5a536893"),o=o(),H(),o),M=r(()=>a("h2",{class:"font-semibold text-3xl text-gray-800 leading-tight"},"Ajout d'un nouvel achat",-1)),N=r(()=>a("h3",{class:"text-xl font-bold"},"Informations relatives à l'achat:",-1)),Y=r(()=>a("hr",{class:"my-3"},null,-1)),O={key:0,class:"flex justify-end"},J=r(()=>a("h3",{class:"text-xl font-bold"},"Articles:",-1)),P=r(()=>a("hr",{class:"my-3"},null,-1)),K={class:"col-span-5 md:col-span-2 mt-2"},Q={class:"col-span-5 md:col-span-3 gap-2 mt-2 grid grid-cols-9"},z={class:"col-span-2 flex items-center"},G={class:"col-span-2 flex items-center"},W={class:"col-span-2 flex items-center"},X={class:"col-span-2 flex justify-center border items-center mb-5 rounded bg-gray-50"},Z={key:0},$={key:1},ee=r(()=>a("span",{class:"text-gray-500"},"  DH HT",-1)),te={class:"flex justify-end col-span-1 mt-1"},le={class:"flex justify-end mt-2"},oe=r(()=>a("h3",{class:"text-xl font-bold"},"Total:",-1)),ie=r(()=>a("hr",{class:"my-3"},null,-1)),ae={class:"flex gap-2 justify-end"},ne={class:"flex p-4 justify-center border items-center mb-5 rounded bg-gray-50"},se=r(()=>a("span",{class:"text-gray-500"},"  DH HT",-1)),re={class:"w-40"},de={class:"flex p-4 justify-center border items-center mb-5 rounded bg-gray-50"},ue={key:0},me=r(()=>a("span",{class:"text-gray-500"},"  DH TTC",-1)),fe=r(()=>a("h3",{class:"text-xl font-bold"},"Informations relatives au paiement:",-1)),ce=r(()=>a("hr",{class:"my-3"},null,-1)),_e={key:0,class:"flex justify-end"},pe={key:0,class:"flex justify-end"},ye=r(()=>a("div",null," Un ordre de virement sera généré automatiquement après l'enregistrement de l'achat",-1)),he=[ye],be={class:"flex justify-end items-center mt-4"};function ve(o,i,p,t,s,y){const h=u("v-select"),m=u("v-col"),b=u("v-row"),v=u("v-text-field"),g=u("v-sheet"),k=u("v-btn"),q=u("v-checkbox"),j=u("v-file-input"),L=u("v-form"),C=u("AppLayout");return d(),x(C,{title:"Création d'un nouvel achat"},{header:n(()=>[M]),default:n(()=>[l(L,{disabled:t.form.processing,modelValue:s.valid,"onUpdate:modelValue":i[11]||(i[11]=e=>s.valid=e),onSubmit:E(y.submit,["prevent"]),ref:"formulaire"},{default:n(()=>[l(g,{color:"grey-white",class:"p-6",border:"",rounded:""},{default:n(()=>[N,Y,l(b,null,{default:n(()=>[l(m,{cols:"12",md:"6"},{default:n(()=>[l(h,{modelValue:t.form.marche_id,"onUpdate:modelValue":i[0]||(i[0]=e=>t.form.marche_id=e),items:p.marches,label:"Marché","item-title":"name","item-value":"id",rules:s.marcheRules},null,8,["modelValue","items","rules"])]),_:1}),l(m,{cols:"12",md:"6"},{default:n(()=>[l(h,{modelValue:t.form.supplier_id,"onUpdate:modelValue":i[1]||(i[1]=e=>t.form.supplier_id=e),items:p.suppliers,label:"Fournisseur","item-title":"name","item-value":"id",rules:s.supplierRules},{details:n(()=>[t.form.supplier_id!=null?(d(),c("div",O,[a("div",null,V(p.suppliers.filter(e=>e.id==t.form.supplier_id)[0].bank_name)+" : "+V(p.suppliers.filter(e=>e.id==t.form.supplier_id)[0].bank_rib),1)])):_("",!0)]),_:1},8,["modelValue","items","rules"])]),_:1})]),_:1}),l(b,null,{default:n(()=>[l(m,{cols:"12",md:"6"},{default:n(()=>[l(v,{modelValue:t.form.date,"onUpdate:modelValue":i[2]||(i[2]=e=>t.form.date=e),type:"date",label:"Date d'achat",rules:s.dateRules},null,8,["modelValue","rules"])]),_:1})]),_:1})]),_:1}),l(g,{color:"grey-white",class:"p-6 mt-4",border:"",rounded:""},{default:n(()=>[J,P,(d(!0),c(A,null,D(t.form.products,(e,R)=>(d(),c("div",{class:"grid grid-cols-5 gap-4 border-b",key:R},[a("div",K,[l(v,{modelValue:e.designation,"onUpdate:modelValue":f=>e.designation=f,label:"Désignation",rules:s.designationRules},null,8,["modelValue","onUpdate:modelValue","rules"])]),a("div",Q,[a("div",z,[l(v,{modelValue:e.quantity,"onUpdate:modelValue":f=>e.quantity=f,type:"number",label:"Quantité",min:"1",max:"999",rules:s.quantityRules},null,8,["modelValue","onUpdate:modelValue","rules"])]),a("div",G,[l(h,{modelValue:e.unity,"onUpdate:modelValue":f=>e.unity=f,items:["F","Kg","T","m","m²","m³","l","Journée","Voyage"],label:"Unité",rules:s.unityRules},null,8,["modelValue","onUpdate:modelValue","rules"])]),a("div",W,[l(v,{modelValue:e.priceperunity,"onUpdate:modelValue":f=>e.priceperunity=f,label:"Prix par unité",suffix:"DH HT",min:"0",max:"999",rules:s.priceperunityRules},null,8,["modelValue","onUpdate:modelValue","rules"])]),a("div",X,[!e.quantity||!e.priceperunity?(d(),c("span",Z," 0 ")):(d(),c("span",$,V((e.priceperunity*e.quantity).toFixed(2)),1)),ee]),a("div",te,[l(k,{variant:"text",border:"",icon:"mdi-delete",color:"red",onClick:f=>t.form.products.splice(R,1)},null,8,["onClick"])])])]))),128)),a("div",le,[l(k,{color:"blue",density:"compact",class:"mb-4","prepend-icon":"mdi-plus-box-outline",variant:"text",onClick:i[3]||(i[3]=e=>t.form.products.push({designation:null,priceperunity:null,quantity:null}))},{default:n(()=>[U("Ajouter un article")]),_:1})]),oe,ie,a("div",ae,[a("div",ne,[a("span",null,V(y.totalht.toFixed(2)),1),se]),a("div",re,[l(h,{modelValue:t.form.taux_tva,"onUpdate:modelValue":i[4]||(i[4]=e=>t.form.taux_tva=e),items:[0,7,10,14,20],label:"Taux de TVA",suffix:"%",rules:s.tvaRules},null,8,["modelValue","rules"])]),a("div",de,[(t.form.taux_tva||t.form.taux_tva===0)&&t.form.products.length?(d(),c("span",ue,V((y.totalht+y.totalht*t.form.taux_tva*.01).toFixed(2)),1)):_("",!0),me])])]),_:1}),l(g,{color:"grey-white",class:"p-6 mt-4",border:"",rounded:""},{default:n(()=>[fe,ce,l(b,null,{default:n(()=>[l(m,{cols:"12",md:"6"},{default:n(()=>[l(h,{modelValue:t.form.bank_id,"onUpdate:modelValue":i[5]||(i[5]=e=>t.form.bank_id=e),items:p.banks,label:"Banque","item-title":"name","item-value":"id",rules:s.bankRules},{details:n(()=>[t.form.bank_id!=null?(d(),c("div",_e,[a("div",null," RIB : "+V(p.banks.filter(e=>e.id==t.form.bank_id)[0].rib),1)])):_("",!0)]),_:1},8,["modelValue","items","rules"])]),_:1}),l(m,{cols:"12",md:"6"},{default:n(()=>[l(h,{modelValue:t.form.type,"onUpdate:modelValue":i[6]||(i[6]=e=>t.form.type=e),items:["Espèce","Chèque","Virement","Effet","Versement"],label:"Type de paiement",rules:s.typeRules},{details:n(()=>[t.form.type==="Virement"?(d(),c("div",pe,he)):_("",!0)]),_:1},8,["modelValue","rules"])]),_:1})]),_:1}),t.form.type==="Virement"?(d(),x(b,{key:0},{default:n(()=>[l(m,{cols:"12",md:"6"},{default:n(()=>[l(v,{modelValue:t.form.motif,"onUpdate:modelValue":i[7]||(i[7]=e=>t.form.motif=e),label:"Motif de virement"},null,8,["modelValue"])]),_:1})]),_:1})):_("",!0),t.form.type==="Chèque"||t.form.type==="Effet"?(d(),x(b,{key:1},{default:n(()=>[l(m,{cols:"12",md:"6"},{default:n(()=>[l(q,{modelValue:t.form.paiement_immediat,"onUpdate:modelValue":i[8]||(i[8]=e=>t.form.paiement_immediat=e),label:"Paiement immédiat"},null,8,["modelValue"])]),_:1}),l(m,{cols:"12",md:"6"},{default:n(()=>[l(v,{modelValue:t.form.echeance_date,"onUpdate:modelValue":i[9]||(i[9]=e=>t.form.echeance_date=e),label:"Date d'échéance",type:"date",min:y.now,rules:s.echeance_dateRules,disabled:t.form.paiement_immediat},null,8,["modelValue","min","rules","disabled"])]),_:1})]),_:1})):_("",!0),t.form.type!="Virement"&&t.form.type!=null?(d(),x(b,{key:2},{default:n(()=>[l(m,{cols:"12",md:"6"},{default:n(()=>[l(j,{label:"Justificatif",modelValue:s.files,"onUpdate:modelValue":i[10]||(i[10]=e=>s.files=e),accept:"image/*,application/pdf","update:modelValue":y.setfile()},null,8,["modelValue","update:modelValue"])]),_:1})]),_:1})):_("",!0)]),_:1}),a("div",be,[l(k,{variant:"flat",loading:t.form.processing,type:"submit",color:"blue-accent-4"},{default:n(()=>[U(" Enregistrer ")]),_:1},8,["loading"])])]),_:1},8,["disabled","modelValue","onSubmit"])]),_:1})}const Ue=B(S,[["render",ve],["__scopeId","data-v-5a536893"]]);export{Ue as default};
