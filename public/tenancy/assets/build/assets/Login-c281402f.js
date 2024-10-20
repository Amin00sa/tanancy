import{h as b,i as h,v as w,o as m,f as k,T as y,c,w as i,a as o,u as s,Z as v,b as r,j as x,d as u,g as V,n as B,e as C}from"./app-6f367f05.js";import{_ as S}from"./GuestLayout-0b32c89c.js";import{_ as p,a as f,b as _}from"./TextInput-fb583b4f.js";import{P as $}from"./PrimaryButton-81444ce2.js";import"./ApplicationLogo-691d4996.js";import"./_plugin-vue_export-helper-c27b6911.js";const q=["value"],N={__name:"Checkbox",props:{checked:{type:[Array,Boolean],required:!0},value:{default:null}},emits:["update:checked"],setup(l,{emit:e}){const d=l,n=b({get(){return d.checked},set(t){e("update:checked",t)}});return(t,a)=>h((m(),k("input",{type:"checkbox",value:l.value,"onUpdate:modelValue":a[0]||(a[0]=g=>n.value=g),class:"rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"},null,8,q)),[[w,n.value]])}},P=["onSubmit"],U={class:"mt-4"},M={class:"block mt-4"},j={class:"flex items-center"},E=r("span",{class:"ml-2 text-sm text-gray-600"},"Se rappeler de moi",-1),F={class:"flex items-center justify-end mt-4"},Z={__name:"Login",props:{canResetPassword:{type:Boolean},status:{type:String},tenant:{type:String}},setup(l){const e=y({email:"",password:"",remember:!1}),d=()=>{e.post(route("login"),{onFinish:()=>e.reset("password")})};return(n,t)=>(m(),c(S,{tenant:l.tenant},{default:i(()=>[o(s(v),{title:"Connexion"}),r("form",{onSubmit:C(d,["prevent"])},[r("div",null,[o(p,{for:"email",value:"Email"}),o(f,{id:"email",type:"email",class:"mt-1 block w-full border",modelValue:s(e).email,"onUpdate:modelValue":t[0]||(t[0]=a=>s(e).email=a),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),o(_,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),r("div",U,[o(p,{for:"password",value:"Mot de passe"}),o(f,{id:"password",type:"password",class:"mt-1 block w-full border",modelValue:s(e).password,"onUpdate:modelValue":t[1]||(t[1]=a=>s(e).password=a),required:"",autocomplete:"current-password"},null,8,["modelValue"]),o(_,{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),r("div",M,[r("label",j,[o(N,{name:"remember",class:"border",checked:s(e).remember,"onUpdate:checked":t[2]||(t[2]=a=>s(e).remember=a)},null,8,["checked"]),E])]),r("div",F,[l.canResetPassword?(m(),c(s(x),{key:0,href:n.route("password.request"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:i(()=>[u(" Forgot your password? ")]),_:1},8,["href"])):V("",!0),o($,{class:B(["ml-4",{"opacity-25":s(e).processing}]),disabled:s(e).processing},{default:i(()=>[u(" Se connecter ")]),_:1},8,["class","disabled"])])],40,P)]),_:1},8,["tenant"]))}};export{Z as default};
