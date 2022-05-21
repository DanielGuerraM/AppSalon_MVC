<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>
<form clas="formulario" method="POST" action="/">

    <div class="campo">
        <label for="email">Email</label>
            <input type="email" id="email" placeholder="Tu email" name="email" value="<?php echo s($auth -> email);?>">
    </div>

    <div class="campo">
        <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" placeholder="Tu contraseña" name="contrasena">
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">
    <div class="acciones">
        <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
        <a href="/olvide">¿Olvidaste tu contraseña?</a>
    </div>
</form>