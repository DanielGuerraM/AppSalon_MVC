<h1 class="nombre-pagina">Recuperar Contraseña</h1>
<p class="descripcion-pagina">Ingresa tu nueva contraseña a continuación</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<?php if($error) return; ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="Contrasena">Nueva contraseña</label>
        <input type="password" id="contrasena" name="contrasena" placeholder="Tu nueva contraseña">
    </div>

    <input class="boton" type="submit" value="Crear nueva contraseña">

    <div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
        <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
    </div>
</form>