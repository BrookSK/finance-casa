<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#6366f1">
    <title>Login - <?= APP_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <i class="fas fa-wallet"></i>
                <h1><?= APP_NAME ?></h1>
                <p>Controle financeiro do casal</p>
            </div>

            <?php if (hasFlash('error')): ?>
                <div class="alert alert-error"><?= e(getFlash('error')) ?></div>
            <?php endif; ?>

            <form method="POST" action="/login" class="login-form">
                <?= csrfField() ?>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> E-mail</label>
                    <input type="email" id="email" name="email" required autofocus
                           placeholder="seu@email.com" class="form-input">
                </div>
                <div class="form-group">
                    <label for="senha"><i class="fas fa-lock"></i> Senha</label>
                    <input type="password" id="senha" name="senha" required
                           placeholder="Sua senha" class="form-input">
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </form>
        </div>
    </div>
</body>
</html>
