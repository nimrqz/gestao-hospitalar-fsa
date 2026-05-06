<?php
$titulo = ($prontuario ? 'Editar' : 'Novo') . ' Prontuário';
ob_start();
?>

<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800"><?= $prontuario ? 'Editar' : 'Novo' ?> Prontuário</h1>
        <p class="text-gray-600">Registro de histórico clínico</p>
    </div>

    <?php if ($consulta): ?>
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="font-bold text-blue-800 mb-2">Consulta Vinculada</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-blue-900">
            <div><strong>Paciente:</strong> <?= htmlspecialchars($consulta['paciente_nome']) ?></div>
            <div><strong>Médico:</strong> <?= htmlspecialchars($consulta['medico_nome']) ?></div>
            <div><strong>Data:</strong> <?= date('d/m/Y', strtotime($consulta['data_consulta'])) ?> <?= substr($consulta['hora_inicio'], 0, 5) ?></div>
        </div>
        <?php if (!empty($consulta['exames_solicitados'])): ?>
        <div class="mt-2 text-sm text-blue-900">
            <strong>Exames Solicitados na Consulta:</strong><br>
            <?= nl2br(htmlspecialchars($consulta['exames_solicitados'])) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anamnese</label>
                    <textarea name="anamnese" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"><?= htmlspecialchars($prontuario['anamnese'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diagnóstico</label>
                    <textarea name="diagnostico" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"><?= htmlspecialchars($prontuario['diagnostico'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prescrição</label>
                    <textarea name="prescricao" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"><?= htmlspecialchars($prontuario['prescricao'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Exames Realizados</label>
                    <textarea name="exames_realizados" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"><?= htmlspecialchars($prontuario['exames_realizados'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea name="observacoes" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"><?= htmlspecialchars($prontuario['observacoes'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="/prontuarios" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition shadow">
                    <i class="fas fa-save mr-2"></i> Salvar Prontuário
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$conteudo = ob_get_clean();
require __DIR__ . '/layout.php';
?>
