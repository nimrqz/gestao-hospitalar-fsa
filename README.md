# 🏥 Sistema de Gestão Hospitalar FSA

Este projeto foi desenvolvido como atividade acadêmica para a disciplina de **Engenharia de Software** do Centro Universitário Fundação Santo André (FSA), sob orientação do **Prof. Andrei Wellington**.

O sistema visa substituir registros manuais e planilhas por uma plataforma digital integrada, eliminando conflitos de agenda e perda de informações clínicas.

---

## 🚀 Tecnologias Utilizadas

- **Linguagem:** PHP 8.2+ (Orientado a Objetos)
- **Arquitetura:** MVC (Model-View-Controller)
- **Banco de Dados:** MySQL 8.0
- **Estilização:** Tailwind CSS (via CDN)
- **Persistência:** PDO (PHP Data Objects) com Singleton Pattern
- **Autenticação:** Sistema de Sessão com RBAC (Role-Based Access Control)

---

## 📋 Levantamento de Requisitos

### Requisitos Funcionais (RF)
- **RF01:** Cadastro e gerenciamento de pacientes (CRUD).
- **RF02:** Cadastro e gerenciamento de médicos e especialidades.
- **RF03:** Agendamento de consultas com trava de segurança contra conflitos de horário.
- **RF04:** Registro de prontuário eletrônico vinculado a consultas realizadas.
- **RF05:** Controle de acesso por perfis (Administrador, Médico, Recepção).
- **RF06:** Histórico completo de atendimentos do paciente.

### Requisitos Não Funcionais (RNF)
- **RNF01:** Segurança de dados e integridade referencial via Foreign Keys.
- **RNF02:** Interface responsiva e intuitiva para uso em ambientes clínicos.
- **RNF03:** Escalabilidade para futuras integrações com laboratórios externos via API.
