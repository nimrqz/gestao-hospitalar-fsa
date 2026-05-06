<?php
$titulo = ($consulta ? 'Editar' : 'Novo') . ' Agendamento';
ob_start();
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800"><?= $consulta ? 'Editar' : 'Novo' ?> Agendamento</h1>
        <p class="text-gray-600">Preencha os dados do agendamento</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Paciente *</label>
                    <select name="paciente_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        <option value="">Selecione...</option>
                        <?php foreach ($pacientes as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= (($consulta['paciente_id'] ?? '') == $p['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nome']) ?> - <?= htmlspecialchars($p['cpf']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Médico *</label>
                    <select name="medico_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        <option value="">Selecione...</option>
                        <?php foreach ($medicos as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= (($consulta['medico_id'] ?? '') == $m['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['nome']) ?> - <?= htmlspecialchars($m['especialidade']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data da Consulta *</label>
                    <input type="date" name="data_consulta" required value="<?= htmlspecialchars($consulta['data_consulta'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Início *</label>
                        <input type="time" name="hora_inicio" required value="<?= htmlspecialchars($consulta['hora_inicio'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fim *</label>
                        <input type="time" name="hora_fim" required value="<?= htmlspecialchars($consulta['hora_fim'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                    </div>
                </div>
                <?php if ($consulta): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        <option value="agendada" <?= ($consulta['status'] ?? '') === 'agendada' ? 'selected' : '' ?>>Agendada</option>
                        <option value="confirmada" <?= ($consulta['status'] ?? '') === 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                        <option value="cancelada" <?= ($consulta['status'] ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        <option value="realizada" <?= ($consulta['status'] ?? '') === 'realizada' ? 'selected' : '' ?>>Realizada</option>
                    </select>
                </div>
                <?php endif; ?>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea name="observacoes" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"><?= htmlspecialchars($consulta['observacoes'] ?? '') ?></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Exames Solicitados</label>
                    <textarea name="exames_solicitados" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"><?= htmlspecialchars($consulta['exames_solicitados'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="/agenda" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow">
                    <i class="fas fa-save mr-2"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$conteudo = ob_get_clean();
require __DIR__ . '/layout.php';
?>
