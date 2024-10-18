<html>
<head>
    <style>
    .headtable, .headtable th, .headtable td {
    border: 1px solid black;
    border-collapse: collapse;
    
    }
    .mytable {
        border-collapse: collapse;
    }
    .mytable th, .border{
        border: 1px solid black;
    
    }
    .headtable th,.headtable td {
    padding-left: 10px;
    padding-right: 10px;
    padding-top: 6px;
    padding-bottom: 6px;
    text-align: center;
    }
    .mytable th {
    padding-left: 2px;
    padding-right: 2px;
    padding-top: 6px;
    padding-bottom: 6px;
    text-align: center;
    }
    .mytable td {
    text-align: center;
    }
    .main-content {
        break-after: always;
    }
    footer {
        position: fixed; 
        bottom: -100px;
        font-size:small;
        width: 100%;
        text-align: center;
    }
    footer {
        position: fixed; 
        bottom: -100px;
        font-size:small;
        width: 100%;
        text-align: center;
    }
    header {
        position: fixed; 
        top: -100px;
        width: 100%;
    }
    @page {
        margin-bottom: 120px;
        margin-top: 140px;
    }
    </style>
</head>
<body>
    <header>
        @if($tenant == 'ltp')
        <div style="margin-left: 168px;"><img style="width: 70%" src="ltp.png"></img></div>
        @elseif($tenant == 'soltaf')
        <div style="margin-left: 108px"><img style="width: 80%" src="soltaf.png"></img></div>
        @endif
    </header>
    <footer>
        @if($tenant == 'ltp')
        Ste LTP&nbsp; R.C: 7519/2009 &nbsp;  ICE N°000050095000003&nbsp;  Patente: 19204484&nbsp; CNSS: 8192875&nbsp;  IF: 40117465
        <br> Capital 1.000.000 Dhs 
        <br> ADRESSE DU SIEGE:  N°56 lot Sijilmassa Errachidia
        <br> Compte Bancaire : 148 210 212 119 891 090 000 238 à la BP ,Agence Targa Errachidia
        @elseif($tenant == 'soltaf')
        Ste SOLTAF SARL &nbsp; R.C: 14605 &nbsp;PATENTE: 19203191 &nbsp;IF: 50137231 &nbsp;ICE: N°002689867000013 &nbsp;CNSS: 2490019
        <br> CAPITAL 100.000 Dhs
        <br> ADRESSE DU SIEGE:  N°56 lot Sijilmassa Errachidia
        <br> Compte Bancaire : 225 210 000 936 628 651 010 497 au Crédit Agricole Errachidia
        @endif
    </footer>
<main>
    <table class="headtable" style="margin-left: 400px;margin-top:30px">
        <tbody>
            <tr>
                <td colspan="2" style="background-color: #2d2e2e;"> 
                    <span style="color:white; font-weight: 600"> FACTURE </span>
                </td>
            </tr>
            <tr>
                <td> <span style="font-weight: 600;">Facture N°</span> </td>
                <td style="width:150px"> <span >{{ $numero }}</span> </td>
            </tr>
            <tr>
                <td> <span style="font-weight: 600">Date</span> </td>
                <td style="width:150px"> <span >{{ $date }}</span> </td>
            </tr>
        </tbody>
    </table>
    <div style="margin-top:50px">
        <span style="font-weight:600;">Doit: </span>
        <span> {{ $client }}</span>
        @if(isset($ice))
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span style="font-weight:600;">ICE: </span> {{ $ice }}
        @endif
    </div>
    <div style="margin-top:5px">
        <span style="font-weight:600;">Objet: </span>
        <span> {{ $objet }}</span>
    </div>
    <div style="margin-top:15px">
        <table class="mytable" style="width:100%;">
            <thead>
                <tr style="background-color: #a3a3a3;">
                    <th style="width:7%;">N° du prix</th>
                    <th style="width:35%;">Désignation</th>
                    <th style="width:13%;">Unité</th>
                    <th>Quantité</th>
                    <th style="width:17%;">Prix unitaire en chiffre (DH)</th>
                    <th style="width:15%;">Prix total HT (DH)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $index => $article)
                <tr>
                    <td style="padding-top:5px;padding-bottom:5px" class="border"> 
                        <span> {{$index+1}}</span>
                    </td>
                    <td style="padding-top:5px;padding-bottom:5px" class="border"> 
                        <span> {{$article['designation']}} </span>
                    </td>
                    <td style="padding-top:5px;padding-bottom:5px" class="border"> 
                        <span style="font-size:large"> {{$article['unity']}} </span>
                    </td>
                    <td style="padding-top:5px;padding-bottom:5px" class="border"> 
                        <span style="font-size:large"> {{$article['quantity']}} </span>
                    </td>
                    <td style="padding-top:5px;padding-bottom:5px" class="border"> 
                        <span style="font-size:large"> {{$article['priceperunity']}} </span>
                    </td>
                    <td style="padding-top:5px;padding-bottom:5px" class="border"> 
                        <span style="font-size:large"> {{$article['subtotal']}} </span>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="border" colspan="2" style="background-color: #a3a3a3;padding-bottom:15px;padding-top:15px;font-weight:600">Montant Total Hors TVA (DH)</td>
                    <td class="border" style="font-size:large;"> {{$totalht}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="border" colspan="2" style="background-color: #a3a3a3;padding-bottom:15px;padding-top:15px;font-weight:600">Montant TVA ({{ $tva }} %)</td>
                    <td class="border" style="font-size:large;"> {{round($totalht*$tva*0.01,2)}} </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="border" colspan="2" style="background-color: #a3a3a3;padding-bottom:15px;padding-top:15px;font-weight:600">Montant Total TTC</td>
                    <td class="border" style="font-size:large;">{{ $totalttc }} </td>
                </tr>
            </tbody>
        </table>
    </div>
<div style="margin-top:30px;font-weight:600">
    ARRÊTE LE PRÉSENT FACTURE À LA SOMME : {{ $total_text }}
</div>
</main>

