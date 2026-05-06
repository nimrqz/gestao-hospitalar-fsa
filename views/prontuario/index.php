<?php
$titulo = 'Prontuários';
ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Prontuários</h1>
        <p class="text-gray-600">Histórico clínico e exames dos pacientes</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Consulta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnóstico</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($prontuarios)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Nenhum prontuário registrado.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($prontuarios as $pr): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-gray-900"><?= date('d/m/Y', strtotime($pr['data_consulta'])) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?= htmlspecialchars($pr['paciente_nome']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?= htmlspecialchars($pr['medico_nome']) ?></td>
                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate"><?= htmlspecialchars($pr['diagnostico'] ?? '-') ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                        <a href="/prontuarios/visualizar?id=<?= $pr['id'] ?>" class="text-blue-600 hover:text-blue-800 transition" title="Visualizar">
                            <i class="fas fa-eye"></i>
                        </a>
                        <?php if (in_array($_SESSION['usuario_perfil'], ['admin', 'medico'])): ?>
                        <a href="/prontuarios/editar?id=<?= $pr['id'] ?>" class="text-yellow-600 hover:text-yellow-800 transition" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($_SESSION['usuario_perfil'] === 'admin'): ?>
                        <a href="/prontuarios/deletar?id=<?= $pr['id'] ?>" class="text-red-600 hover:text-red-800 transition" title="Excluir" onclick="return confirm('Tem certeza?')">
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
