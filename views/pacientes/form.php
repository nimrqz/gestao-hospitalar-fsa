<?php
$titulo = ($paciente ? 'Editar' : 'Novo') . ' Paciente';
ob_start();
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800"><?= $paciente ? 'Editar' : 'Novo' ?> Paciente</h1>
        <p class="text-gray-600">Preencha os dados do paciente</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo *</label>
                    <input type="text" name="nome" required value="<?= htmlspecialchars($paciente['nome'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CPF *</label>
                    <input type="text" name="cpf" required value="<?= htmlspecialchars($paciente['cpf'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento *</label>
                    <input type="date" name="data_nascimento" required value="<?= htmlspecialchars($paciente['data_nascimento'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                    <input type="text" name="telefone" value="<?= htmlspecialchars($paciente['telefone'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($paciente['email'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Convênio</label>
                    <input type="text" name="convenio" value="<?= htmlspecialchars($paciente['convenio'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nº Carteirinha</label>
                    <input type="text" name="numero_carteirinha" value="<?= htmlspecialchars($paciente['numero_carteirinha'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                    <textarea name="endereco" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"><?= htmlspecialchars($paciente['endereco'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="/pacientes" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow">
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
