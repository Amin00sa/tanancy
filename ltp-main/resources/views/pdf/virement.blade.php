@if($tenant == 'ltp')
<div style="margin-left: 175px;"><img style="width: 65%" src="ltp.png"></img></div>
@elseif($tenant == 'soltaf')
<div style="margin-left: 108px"><img style="width: 80%" src="soltaf.png"></img></div>
@endif

<div style="font-weight:500;font-size:large;margin-top:50px;margin-left:35px">Ordre de virement N°: {{$numero_virement}}</div>
<div style="font-weight: 700; font-size:larger; margin-left:350px; margin-top:40px">
    À L'ATTENTION DU DIRECTEUR<br>
    {{$bank}}
</div>
<div style="font-size:large; margin-top:30px; font-weight:500">Objet : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ordre de virement</div>
<div style="font-size:x-large; margin-top:40px"> &nbsp;&nbsp;&nbsp;&nbsp;Nous avons l'honneur de sollicité de votre haute bien vaillance de bien vouloir effectuer par le débit de notre compte ouvert sur votre agence sous le n° :</div>
<div style="background-color:#bcbcbc; padding: 5px; margin-top:10px">
    <div style="text-align: center;font-size:large;font-weight:500">{{$rib}}</div>
</div>
<div style="margin-top:40px;margin-left:10px;">
<div style="font-size:large;">
    <span style="font-weight:500;">Montant</span>
    <span style="margin-left:150px">{{$montant}} DH</span>
</div>
<div style="font-size:large;margin-top:5px;">
    <span style="font-weight:500;">Bénéficiaire</span>
    <span style="margin-left:122px">{{$supplier_name}}</span>
</div>
<div style="font-size:large;margin-top:5px;">
    <span style="font-weight:500;">Rib</span>
    <span style="margin-left:189px">{{$supplier_rib}}</span>
</div>
<div style="font-size:large;margin-top:5px;">
    <span style="font-weight:500;">Banque du bénéficiaire</span>
    <span style="margin-left:28px">{{$supplier_bank}}</span>
</div>
<div style="font-size:large;margin-top:5px;">
    <span style="font-weight:500;">Motif</span>
    <span style="margin-left:171px">{{$motif}}</span>
</div>
</div>
<div style="margin-top:80px;margin-left:380px;font-weight:500;font-size:large;">Errachidia le: {{$date}}</div>
<div style="margin-top:80px;margin-left:400px;font-weight:500;font-size:large;">Gérant de la société</div>