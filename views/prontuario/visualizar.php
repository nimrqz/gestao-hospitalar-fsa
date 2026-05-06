<?php
$titulo = 'Visualizar Prontuário';
ob_start();
?>

<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Prontuário</h1>
            <p class="text-gray-600">Detalhes do histórico clínico</p>
        </div>
        <a href="/prontuarios" class="text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b pb-4">
            <div>
                <span class="text-sm text-gray-500">Paciente</span>
                <p class="font-bold text-lg text-gray-900"><?= htmlspecialchars($prontuario['paciente_nome']) ?></p>
            </div>
            <div>
                <span class="text-sm text-gray-500">Médico</span>
                <p class="font-bold text-lg text-gray-900"><?= htmlspecialchars($prontuario['medico_nome']) ?> <span class="text-sm font-normal text-gray-500">(<?= htmlspecialchars($prontuario['especialidade']) ?>)</span></p>
            </div>
            <div>
                <span class="text-sm text-gray-500">Data da Consulta</span>
                <p class="font-medium text-gray-900"><?= date('d/m/Y', strtotime($prontuario['data_consulta'])) ?> às <?= substr($prontuario['hora_inicio'], 0, 5) ?></p>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Anamnese</h3>
            <div class="bg-gray-50 p-4 rounded-lg text-gray-800">
                <?= nl2br(htmlspecialchars($prontuario['anamnese'] ?? 'Não informado')) ?>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Diagnóstico</h3>
            <div class="bg-gray-50 p-4 rounded-lg text-gray-800">
                <?= nl2br(htmlspecialchars($prontuario['diagnostico'] ?? 'Não informado')) ?>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Prescrição</h3>
            <div class="bg-gray-50 p-4 rounded-lg text-gray-800">
                <?= nl2br(htmlspecialchars($prontuario['prescricao'] ?? 'Não informado')) ?>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Exames Realizados</h3>
            <div class="bg-gray-50 p-4 rounded-lg text-gray-800">
                <?= nl2br(htmlspecialchars($prontuario['exames_realizados'] ?? 'Não informado')) ?>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Observações</h3>
            <div class="bg-gray-50 p-4 rounded-lg text-gray-800">
                <?= nl2br(htmlspecialchars($prontuario['observacoes'] ?? 'Não informado')) ?>
            </div>
        </div>

        <?php if (!empty($prontuario['exames_solicitados'])): ?>
        <div class="border-t pt-4">
            <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wide mb-2">Exames Solicitados na Consulta</h3>
            <div class="bg-blue-50 p-4 rounded-lg text-blue-900">
                <?= nl2br(htmlspecialchars($prontuario['exames_solicitados'])) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$conteudo = ob_get_clean();
require __DIR__ . '/layout.php';
?>
