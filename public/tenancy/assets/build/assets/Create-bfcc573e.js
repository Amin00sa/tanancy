import{A}from"./AppLayout-5088345b.js";import{T as I,k as r,o as f,c as D,w as o,a as e,b as s,m as N,d as v,e as R,f as b,l as H,t as y,F,g as S}from"./app-6f367f05.js";import{h as E}from"./moment-fbc5633a.js";import{_ as B}from"./_plugin-vue_export-helper-c27b6911.js";import"./ApplicationLogo-691d4996.js";const M={props:{clients:Object,marches:Object},components:{AppLayout:A},data(){return{clients:this.clients,valid:!1,validclient:!1,dialog:!1,loadingclient:!1,editedItem:{name:"",ice:""},status:[{title:"Réglé",value:"paid"},{title:"Non Réglé",value:"pending"}],objetRules:[l=>l?!0:`L'objet" est obligatoire`],clientRules:[l=>l?!0:"Le client est obligatoire"],unityRules:[l=>l?!0:"L'unité est obligatoire"],designationRules:[l=>l?!0:"La désignation est obligatoire"],statusRules:[l=>l?!0:"Le statut de paiement est obligatoire"],quantityRules:[l=>l?!0:"La quantité est obligatoire"],priceperunityRules:[l=>/^-?\d+(\.\d+)?$/.test(l)?!0:"La prix par unité doit être numérique"],tvaRules:[l=>l||l===0?!0:"Le taux de TVA est obligatoire"],total_textRules:[l=>l?!0:"Le total en texte est obligatoire"],typeRules:[l=>l?!0:"Le type de paiement est obligatoire"]}},setup(){return{form:I({client_id:null,marche_id:null,objet:null,products:[],total:null,total_text:null,taux_tva:null,date:null,status:null})}},computed:{totalht(){let l=0;return this.form.products.forEach(n=>{if(n.quantity&&n.priceperunity)l+=n.quantity*n.priceperunity;else return 0}),l},now(){return E().format("YYYY-MM-DD")}},methods:{submit(){this.form.total=this.totalht,this.$refs.formulaire.validate().then(()=>{this.valid&&this.form.post(route("ventes.store"))})},async save(){this.$refs.formclient.validate().then(async()=>{if(this.validclient){this.loadingclient=!0;try{const l=await axios.post("/client/store",{name:this.editedItem.name,ice:this.editedItem.ice});l.data.client&&(this.clients.push({id:l.data.client.id,name:l.data.client.name}),this.form.client_id=l.data.client.id)}catch{}}this.loadingclient=!0,this.close()})},close(){this.dialog=!1,this.editedItem.name=""}}},Y=s("h2",{class:"font-semibold text-3xl text-gray-800 leading-tight"},"Ajout d'une nouvelle vente",-1),O=s("h3",{class:"text-xl font-bold"},"Informations relatives à la vente:",-1),P=s("hr",{class:"my-3"},null,-1),z={class:"flex justify-end items-center"},J=s("span",{class:"text-h6 font-bold"},"Nouveau client",-1),K=s("h3",{class:"text-xl font-bold"},"Articles:",-1),Q=s("hr",{class:"my-3"},null,-1),G={class:"col-span-5 md:col-span-2 mt-2"},W={class:"col-span-5 md:col-span-3 gap-2 mt-2 grid grid-cols-9"},X={class:"col-span-2 flex items-center"},Z={class:"col-span-2 flex items-center"},$={class:"col-span-2 flex items-center"},ee={class:"col-span-2 flex justify-center border items-center mb-5 rounded bg-gray-50"},te={key:0},le={key:1},oe=s("span",{class:"text-gray-500"},"  DH HT",-1),ne={class:"flex justify-end col-span-1 mt-1"},se={class:"flex justify-end mt-2"},ie=s("h3",{class:"text-xl font-bold"},"Total:",-1),ae=s("hr",{class:"my-3"},null,-1),re={class:"flex gap-2 justify-end"},de={class:"flex p-4 justify-center border items-center mb-5 rounded bg-gray-50"},ue=s("span",{class:"text-gray-500"},"  DH HT",-1),me={class:"w-40"},ce={class:"flex p-4 justify-center border items-center mb-5 rounded bg-gray-50"},_e={key:0},fe=s("span",{class:"text-gray-500"},"  DH TTC",-1),pe=s("h3",{class:"text-xl font-bold"},"Informations relatives au paiement:",-1),ve=s("hr",{class:"my-3"},null,-1),be={class:"flex justify-end items-center mt-4"};function ge(l,n,j,a,i,c){const u=r("v-text-field"),m=r("v-col"),g=r("v-row"),p=r("v-select"),_=r("v-btn"),U=r("v-card-title"),k=r("v-container"),C=r("v-card-text"),L=r("v-card-actions"),w=r("v-card"),x=r("v-form"),T=r("v-dialog"),h=r("v-sheet"),q=r("AppLayout");return f(),D(q,{title:"Création d'une nouvelle vente"},{header:o(()=>[Y]),default:o(()=>[e(x,{disabled:a.form.processing,modelValue:i.valid,"onUpdate:modelValue":n[11]||(n[11]=t=>i.valid=t),onSubmit:R(c.submit,["prevent"]),ref:"formulaire"},{default:o(()=>[e(h,{color:"grey-white",class:"p-6",border:"",rounded:""},{default:o(()=>[O,P,e(g,null,{default:o(()=>[e(m,{cols:"12"},{default:o(()=>[e(u,{modelValue:a.form.objet,"onUpdate:modelValue":n[0]||(n[0]=t=>a.form.objet=t),label:"Objet",rules:i.objetRules},null,8,["modelValue","rules"])]),_:1})]),_:1}),e(g,null,{default:o(()=>[e(m,{cols:"12",md:"6"},{default:o(()=>[e(p,{modelValue:a.form.marche_id,"onUpdate:modelValue":n[1]||(n[1]=t=>a.form.marche_id=t),items:j.marches,label:"Marché","item-title":"name","item-value":"id",clearable:"",rules:l.marcheRules},null,8,["modelValue","items","rules"])]),_:1}),e(m,{cols:"12",md:"6"},{default:o(()=>[e(p,{modelValue:a.form.client_id,"onUpdate:modelValue":n[6]||(n[6]=t=>a.form.client_id=t),items:i.clients,label:"Client","no-data-text":"Aucun client enregistré","item-title":"name","item-value":"id",rules:i.clientRules},{details:o(()=>[e(T,{modelValue:i.dialog,"onUpdate:modelValue":n[5]||(n[5]=t=>i.dialog=t),"max-width":"500px",transition:"dialog-top-transition",persistent:""},{activator:o(({props:t})=>[s("div",z,[e(_,N({"prepend-icon":"mdi-plus-circle-outline",color:"blue-accent-4",size:"small",variant:"text"},t),{default:o(()=>[v(" Nouveau client ")]),_:2},1040)])]),default:o(()=>[e(x,{modelValue:i.validclient,"onUpdate:modelValue":n[4]||(n[4]=t=>i.validclient=t),disabled:i.loadingclient,onSubmit:R(c.save,["prevent"]),ref:"formclient"},{default:o(()=>[e(w,null,{default:o(()=>[e(U,{class:"border-b bg-gray-50"},{default:o(()=>[J]),_:1}),e(C,{class:"max-h-96 overflow-y-auto"},{default:o(()=>[e(k,null,{default:o(()=>[e(g,null,{default:o(()=>[e(m,{cols:"12"},{default:o(()=>[e(u,{modelValue:i.editedItem.name,"onUpdate:modelValue":n[2]||(n[2]=t=>i.editedItem.name=t),variant:"outlined",label:"Nom",placeholder:"",rules:l.nameRules},null,8,["modelValue","rules"])]),_:1}),e(m,{cols:"12"},{default:o(()=>[e(u,{modelValue:i.editedItem.ice,"onUpdate:modelValue":n[3]||(n[3]=t=>i.editedItem.ice=t),variant:"outlined",label:"ICE",placeholder:""},null,8,["modelValue"])]),_:1})]),_:1})]),_:1})]),_:1}),e(L,{class:"bg-gray-100 flex justify-end items-center border-t"},{default:o(()=>[e(_,{color:"gray",class:"mt-2",variant:"text",disabled:i.loadingclient,onClick:c.close},{default:o(()=>[v("Annuler")]),_:1},8,["disabled","onClick"]),e(_,{color:"blue-accent-4",type:"submit",class:"mt-2",active:"",variant:"flat",loading:i.loadingclient},{default:o(()=>[v("Enregistrer")]),_:1},8,["loading"])]),_:1})]),_:1})]),_:1},8,["modelValue","disabled","onSubmit"])]),_:1},8,["modelValue"])]),_:1},8,["modelValue","items","rules"])]),_:1})]),_:1})]),_:1}),e(h,{color:"grey-white",class:"p-6 mt-4",border:"",rounded:""},{default:o(()=>[K,Q,(f(!0),b(F,null,H(a.form.products,(t,V)=>(f(),b("div",{class:"grid grid-cols-5 gap-4 border-b",key:V},[s("div",G,[e(u,{modelValue:t.designation,"onUpdate:modelValue":d=>t.designation=d,label:"Désignation",rules:i.designationRules},null,8,["modelValue","onUpdate:modelValue","rules"])]),s("div",W,[s("div",X,[e(u,{modelValue:t.quantity,"onUpdate:modelValue":d=>t.quantity=d,type:"number",label:"Quantité",min:"1",max:"999",rules:i.quantityRules},null,8,["modelValue","onUpdate:modelValue","rules"])]),s("div",Z,[e(p,{modelValue:t.unity,"onUpdate:modelValue":d=>t.unity=d,items:["F","Kg","T","m","m²","m³","l","Journée","Voyage"],label:"Unité",rules:i.unityRules},null,8,["modelValue","onUpdate:modelValue","rules"])]),s("div",$,[e(u,{modelValue:t.priceperunity,"onUpdate:modelValue":d=>t.priceperunity=d,label:"Prix par unité",suffix:"DH HT",min:"0",max:"999",rules:i.priceperunityRules},null,8,["modelValue","onUpdate:modelValue","rules"])]),s("div",ee,[!t.quantity||!t.priceperunity?(f(),b("span",te," 0 ")):(f(),b("span",le,y((t.priceperunity*t.quantity).toFixed(2)),1)),oe]),s("div",ne,[e(_,{variant:"text",border:"",icon:"mdi-delete",color:"red",onClick:d=>a.form.products.splice(V,1)},null,8,["onClick"])])])]))),128)),s("div",se,[e(_,{color:"blue",density:"compact",class:"mb-4","prepend-icon":"mdi-plus-box-outline",variant:"text",onClick:n[7]||(n[7]=t=>a.form.products.push({designation:null,priceperunity:null,quantity:null}))},{default:o(()=>[v("Ajouter un article")]),_:1})]),ie,ae,s("div",re,[s("div",de,[s("span",null,y(c.totalht.toFixed(2)),1),ue]),s("div",me,[e(p,{modelValue:a.form.taux_tva,"onUpdate:modelValue":n[8]||(n[8]=t=>a.form.taux_tva=t),items:[0,7,10,14,20],label:"Taux de TVA",suffix:"%",rules:i.tvaRules},null,8,["modelValue","rules"])]),s("div",ce,[(a.form.taux_tva||a.form.taux_tva===0)&&a.form.products.length?(f(),b("span",_e,y((c.totalht+c.totalht*a.form.taux_tva*.01).toFixed(2)),1)):S("",!0),fe])])]),_:1}),e(h,{color:"grey-white",class:"p-6 mt-4",border:"",rounded:""},{default:o(()=>[pe,ve,e(g,null,{default:o(()=>[e(m,{cols:"12",md:"6"},{default:o(()=>[e(p,{modelValue:a.form.status,"onUpdate:modelValue":n[9]||(n[9]=t=>a.form.status=t),items:i.status,label:"Statut de paiement","item-title":"title","item-value":"value",rules:i.statusRules},null,8,["modelValue","items","rules"])]),_:1}),e(m,{cols:"12",md:"6"},{default:o(()=>[e(u,{modelValue:a.form.date,"onUpdate:modelValue":n[10]||(n[10]=t=>a.form.date=t),type:"date",label:"Date de facturation",clearable:""},null,8,["modelValue"])]),_:1})]),_:1})]),_:1}),s("div",be,[e(_,{variant:"flat",loading:a.form.processing,type:"submit",color:"blue-accent-4"},{default:o(()=>[v(" Enregistrer ")]),_:1},8,["loading"])])]),_:1},8,["disabled","modelValue","onSubmit"])]),_:1})}const je=B(M,[["render",ge]]);export{je as default};
