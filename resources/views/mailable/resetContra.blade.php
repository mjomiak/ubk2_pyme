<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<h3>Reset de Contraseña</h3>
<p>Hola <b>{{$user->name}}</b>:</p> 

<p>Se ha ejecutado un reset de contraseña para su cuenta, sus credenciales temporales son:
</p>

<b>Usuario:</b> {{$user->email}}<br>
<b>Contraseña Inicial:</b> {{$pass}}

<p>La contraseña es <i>case-sensitive</i>, es decir, distingue mayúsculas y minúscilas</p>

<p>Una vez logueado se le solicitará cambiar la contraseña de inmediato, planifique una, los requisitos son:</p>
<ul>
    <li>Longitud mínima de 8 caracteres</li>
    <li>Debe contener mayúsculas y minúsculas</li>
    <li>Debe contener números</li>
    <li>Debe contener caracteres especiales (ej: ! # $ & ¿ ? _ - . ,)  </li>
</ul> 

Atentemente.<br>
El equipo {{$empresa}}

</body>
</html>