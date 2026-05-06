<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Gestão Hospitalar') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800">
    <?php if (!empty($_SESSION['usuario_id'])): ?>
    <nav class="bg-blue-700 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="/dashboard" class="text-xl font-bold">
                        <i class="fas fa-hospital mr-2"></i>Hospital FSA
                    </a>
                </div>
                <div class="hidden md:flex space-x-6">
                    <a href="/dashboard" class="hover:text-blue-200 transition">Dashboard</a>
                    <a href="/pacientes" class="hover:text-blue-200 transition">Pacientes</a>
                    <a href="/agenda" class="hover:text-blue-200 transition">Agenda</a>
                    <a href="/prontuarios" class="hover:text-blue-200 transition">Prontuários</a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">
                        <i class="fas fa-user-circle mr-1"></i>
                        <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?>
                        <span class="text-xs bg-blue-800 px-2 py-1 rounded ml-1 uppercase">
                            <?= htmlspecialchars($_SESSION['usuario_perfil'] ?? '') ?>
                        </span>
                    </span>
                    <a href="/logout" class="text-sm bg-red-600 hover:bg-red-700 px-3 py-1 rounded transition">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <main class="container mx-auto px-4 py-8">
        <?php if (!empty($erro)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow">
                <p class="font-bold">Erro</p>
                <p><?= htmlspecialchars($erro) ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow">
                <p class="font-bold">Sucesso</p>
                <p><?= htmlspecialchars($sucesso) ?></p>
            </div>
        <?php endif; ?>

        <?= $conteudo ?? '' ?>
    </main>

    <footer class="bg-gray-800 text-gray-300 py-6 mt-12">
        <div class="container mx-auto px-4 text-center text-sm">
            <p>&copy; <?= date('Y') ?> Gestão Hospitalar FSA. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>
