<h1 class="nombre-pagina">Olvide la contraseña</h1>
<p class="descripcion-pagina">Restablece tu cantraseña escribiendo tu email a continuacion</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<form class="formulario" method="POST" action="/olvide">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Tu email">
    </div>

    <input class="boton" type="submit" value="Enviar instrucciones">

    <div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
        <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
    </div>
</form>