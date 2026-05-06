<?php $titulo = 'Login - Gestão Hospitalar'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-600 to-blue-800 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <i class="fas fa-hospital text-5xl text-blue-600 mb-4"></i>
            <h1 class="text-2xl font-bold text-gray-800">Gestão Hospitalar</h1>
            <p class="text-gray-500 text-sm">Sistema de Gestão Hospitalar FSA</p>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <?php if ($erro === 'invalido'): ?>
                    E-mail ou senha incorretos.
                <?php elseif ($erro === 'inativo'): ?>
                    Usuário inativo. Entre em contato com o administrador.
                <?php elseif ($erro === 'campos'): ?>
                    Preencha todos os campos.
                <?php else: ?>
                    <?= htmlspecialchars($erro) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" id="email" required
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="admin@hospital.com">
                </div>
            </div>

            <div>
                <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="senha" id="senha" required
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="••••••">
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                <i class="fas fa-sign-in-alt mr-2"></i> Entrar
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-500">
            <p>Perfis de teste:</p>
            <p>admin@hospital.com | medico@hospital.com | recepcao@hospital.com</p>
            <p>Senha padrão: <strong>123456</strong></p>
        </div>
    </div>
</body>
</html>
