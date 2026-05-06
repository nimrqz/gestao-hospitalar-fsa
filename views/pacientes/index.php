<?php
$titulo = 'Pacientes';
ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Pacientes</h1>
        <p class="text-gray-600">Gerenciamento de pacientes</p>
    </div>
    <?php if (in_array($_SESSION['usuario_perfil'], ['admin', 'recepcao'])): ?>
    <a href="/pacientes/criar" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition shadow">
        <i class="fas fa-plus mr-2"></i> Novo Paciente
    </a>
    <?php endif; ?>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPF</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Nasc.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Convênio</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($pacientes)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Nenhum paciente cadastrado.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($pacientes as $p): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?= htmlspecialchars($p['nome']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?= htmlspecialchars($p['cpf']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?= date('d/m/Y', strtotime($p['data_nascimento'])) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?= htmlspecialchars($p['telefone'] ?? '-') ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?= htmlspecialchars($p['convenio'] ?? '-') ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                        <?php if (in_array($_SESSION['usuario_perfil'], ['admin', 'recepcao'])): ?>
                        <a href="/pacientes/editar?id=<?= $p['id'] ?>" class="text-blue-600 hover:text-blue-800 transition" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($_SESSION['usuario_perfil'] === 'admin'): ?>
                        <a href="/pacientes/deletar?id=<?= $p['id'] ?>" class="text-red-600 hover:text-red-800 transition" title="Excluir" onclick="return confirm('Tem certeza?')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conteudo = ob_get_clean();
require __DIR__ . '/layout.php';
?>
