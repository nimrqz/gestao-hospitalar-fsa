<?php
$titulo = 'Dashboard';
ob_start();
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600">Bem-vindo, <?= htmlspecialchars($usuario['usuario_nome'] ?? '') ?>!</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total de Pacientes</p>
                <p class="text-3xl font-bold text-gray-800"><?= $totalPacientes ?></p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
        <a href="/pacientes" class="text-blue-600 text-sm mt-4 inline-block hover:underline">Ver pacientes &rarr;</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total de Consultas</p>
                <p class="text-3xl font-bold text-gray-800"><?= $totalConsultas ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-calendar-check text-green-600 text-xl"></i>
            </div>
        </div>
        <a href="/agenda" class="text-green-600 text-sm mt-4 inline-block hover:underline">Ver agenda &rarr;</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Prontuários</p>
                <p class="text-3xl font-bold text-gray-800">Histórico</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-file-medical text-purple-600 text-xl"></i>
            </div>
        </div>
        <a href="/prontuarios" class="text-purple-600 text-sm mt-4 inline-block hover:underline">Ver prontuários &rarr;</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Acesso Rápido</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="/pacientes/criar" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fas fa-user-plus text-blue-600"></i>
            </div>
            <span class="font-medium text-gray-700">Novo Paciente</span>
        </a>
        <a href="/agenda/criar" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <i class="fas fa-calendar-plus text-green-600"></i>
            </div>
            <span class="font-medium text-gray-700">Novo Agendamento</span>
        </a>
        <a href="/prontuarios" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
            <div class="bg-purple-100 p-3 rounded-full mr-4">
                <i class="fas fa-notes-medical text-purple-600"></i>
            </div>
            <span class="font-medium text-gray-700">Ver Prontuários</span>
        </a>
        <a href="/agenda" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
            <div class="bg-orange-100 p-3 rounded-full mr-4">
                <i class="fas fa-list text-orange-600"></i>
            </div>
            <span class="font-medium text-gray-700">Lista de Agenda</span>
        </a>
    </div>
</div>

<?php
$conteudo = ob_get_clean();
require __DIR__ . '/layout.php';
?>
