<?php
$titulo = 'Agenda de Consultas';
ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Agenda de Consultas</h1>
        <p class="text-gray-600">Gerenciamento de agendamentos</p>
    </div>
    <?php if (in_array($_SESSION['usuario_perfil'], ['admin', 'recepcao'])): ?>
    <a href="/agenda/criar" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition shadow">
        <i class="fas fa-plus mr-2"></i> Novo Agendamento
    </a>
    <?php endif; ?>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horário</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($consultas)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Nenhuma consulta agendada.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($consultas as $c): ?>
                <?php
                    $statusClasses = [
                        'agendada' => 'bg-blue-100 text-blue-800',
                        'confirmada' => 'bg-green-100 text-green-800',
                        'cancelada' => 'bg-red-100 text-red-800',
                        'realizada' => 'bg-gray-100 text-gray-800',
                    ];
                    $statusClass = $statusClasses[$c['status']] ?? 'bg-gray-100 text-gray-800';
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-gray-900"><?= date('d/m/Y', strtotime($c['data_consulta'])) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?= substr($c['hora_inicio'], 0, 5) ?> - <?= substr($c['hora_fim'], 0, 5) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?= htmlspecialchars($c['paciente_nome']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?= htmlspecialchars($c['medico_nome']) ?> <span class="text-xs text-gray-400">(<?= htmlspecialchars($c['especialidade']) ?>)</span></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                            <?= ucfirst($c['status']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                        <?php if (in_array($_SESSION['usuario_perfil'], ['admin', 'recepcao'])): ?>
                        <a href="/agenda/editar?id=<?= $c['id'] ?>" class="text-blue-600 hover:text-blue-800 transition" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($_SESSION['usuario_perfil'] === 'admin'): ?>
                        <a href="/agenda/deletar?id=<?= $c['id'] ?>" class="text-red-600 hover:text-red-800 transition" title="Excluir" onclick="return confirm('Tem certeza?')">
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
