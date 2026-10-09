<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Reportes Urbanos</title>
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
        <h2>Crear una cuenta</h2>

        <?php if (isset($_GET['error'])): ?>
            <p class="error">Error: <?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <form action="php/registrar.php" method="POST">
            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required minlength="6">

            <label for="codigo_admin">Código de administrador (opcional):</label>
            <input type="text" id="codigo_admin" name="codigo_admin" placeholder="Solo si eres administrador">

            <button type="submit">Registrarse</button>
        </form>

        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </main>
</body>
</html>