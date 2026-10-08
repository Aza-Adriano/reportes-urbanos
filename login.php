<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Reportes Urbanos</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1>Reportes Urbanos</h1>
        <p>Macrodistrito Centro - La Paz</p>
    </header>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="login.php">Iniciar Sesión</a>
        <a href="registro.php">Registrarse</a>
    </nav>

    <main>
        <h2>Iniciar Sesión</h2>

        <?php if (isset($_GET['mensaje'])): ?>
            <p class="exito"><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <p class="error">Error: <?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <form action="php/iniciar_sesion.php" method="POST">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </main>
</body>
</html>