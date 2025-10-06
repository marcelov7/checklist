# Sistema de Checklist de Paradas - Laravel

Este é um sistema completo para gerenciamento de checklists durante paradas de manutenção industrial, desenvolvido em Laravel com interface responsiva usando Bootstrap.

## 📋 Funcionalidades

### 🏭 Gestão de Áreas
- Cadastro de áreas da planta industrial
- Organização hierárquica dos equipamentos
- Status ativo/inativo para controle

### ⚙️ Gestão de Equipamentos
- Cadastro completo com nome, TAG única e descrição
- Vinculação a áreas específicas
- Sistema de status para controle de equipamentos ativos

### 🛑 Gestão de Paradas
- Criação de paradas de manutenção
- Controle de data/hora de início e fim
- Status: Em andamento, Concluída, Cancelada
- Geração automática de testes para todos equipamentos ativos

### ✅ Sistema de Checklist
- Interface intuitiva organizada por áreas
- Testes individuais por equipamento
- Status: Pendente, OK, Problema
- Campo para observações gerais
- Campo específico para descrição de problemas
- Registro do responsável pelo teste
- Data/hora automática dos testes

### 📊 Relatórios e Estatísticas
- Progresso geral da parada em percentual
- Progresso individual por área
- Visualização em tempo real do status dos testes
- Indicadores visuais com cores (verde=OK, vermelho=problema, cinza=pendente)

## 🛠️ Estrutura Técnica

### Models
- **Area**: Gestão das áreas da planta
- **Equipamento**: Cadastro de equipamentos com relacionamento às áreas
- **Parada**: Controle das paradas de manutenção
- **Teste**: Registros individuais dos testes realizados

### Controllers
- **AreaController**: CRUD completo de áreas
- **EquipamentoController**: CRUD completo de equipamentos
- **ParadaController**: Gestão de paradas + funcionalidade de finalização
- **TesteController**: Atualização de status dos testes via AJAX

### Migrations
```sql
-- Areas
- id, nome, descricao, ativo, timestamps

-- Equipamentos
- id, nome, tag (unique), descricao, area_id, ativo, timestamps

-- Paradas
- id, nome, descricao, data_inicio, data_fim, status, timestamps

-- Testes
- id, parada_id, equipamento_id, status, observacoes, problema_descricao, 
  data_teste, testado_por, timestamps
- unique(parada_id, equipamento_id)
```

## 🎯 Como Usar o Sistema

### 1. Configuração Inicial
1. **Cadastre as Áreas**: Organize sua planta em áreas lógicas
2. **Cadastre os Equipamentos**: Adicione equipamentos com TAGs únicos em suas respectivas áreas
3. **Crie uma Parada**: Defina nome, descrição e data de início

### 2. Durante a Parada
1. **Acesse a Parada**: A tela principal mostra todos os equipamentos organizados por área
2. **Realize os Testes**: Clique em "Testar" para cada equipamento
3. **Defina o Status**: 
   - **OK**: Equipamento aprovado no teste
   - **Problema**: Descreva o problema encontrado
   - **Pendente**: Teste ainda não realizado
4. **Acompanhe o Progresso**: Veja em tempo real o percentual de conclusão

### 3. Finalização
1. **Complete todos os testes** ou os que forem necessários
2. **Finalize a Parada**: Isso impede alterações futuras nos testes
3. **Consulte Relatórios**: Veja o resumo completo da parada

## 🔧 Instalação e Configuração

### Pré-requisitos
- PHP 8.1+
- Composer
- SQLite (ou MySQL/PostgreSQL)
- Node.js (para assets, opcional)

### Instalação
```bash
# Clone ou copie os arquivos para seu diretório
cd projeto-checklist

# Instale dependências
composer install

# Configure o ambiente
cp .env.example .env
php artisan key:generate

# Execute as migrations
php artisan migrate

# (Opcional) Execute o seeder com dados de exemplo
php artisan db:seed --class=DadosExemploSeeder

# Inicie o servidor
php artisan serve
```

### Configuração do Banco
O sistema está configurado para usar SQLite por padrão. Para usar MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=checklist
DB_USERNAME=root
DB_PASSWORD=
```

## 📱 Interface do Usuário

### Características da Interface
- **Responsiva**: Funciona em desktop, tablet e mobile
- **Bootstrap 5**: Interface moderna e consistente
- **Font Awesome**: Ícones intuitivos
- **AJAX**: Atualizações sem recarregar a página
- **Sidebar**: Navegação fácil entre seções
- **Modals**: Formulários em janelas modais
- **Progress Bars**: Visualização clara do progresso

### Cores e Status
- 🟢 **Verde**: Teste OK, área/parada concluída
- 🔴 **Vermelho**: Problema encontrado
- 🔵 **Azul**: Em andamento, teste pendente
- ⚫ **Cinza**: Inativo, cancelado

## 🚀 Funcionalidades Avançadas

### Sistema de Relatórios
- Progresso em tempo real
- Estatísticas por área
- Histórico de paradas anteriores
- Exportação de dados (pode ser implementada)

### Segurança
- Validação de dados em todos os formulários
- Proteção CSRF
- Sanitização de entradas
- Controle de acesso (pode ser expandido)

### Performance
- Relacionamentos otimizados com Eloquent
- Consultas eficientes para grandes volumes
- Cache de estatísticas (pode ser implementado)
- Paginação automática (pode ser adicionada)

## 🔮 Possíveis Expansões

### Funcionalidades Futuras
1. **Sistema de Usuários**: Login, permissões, auditoria
2. **Notificações**: Email/SMS para problemas críticos
3. **Mobile App**: Aplicativo nativo para tablets
4. **Integração**: APIs para sistemas de manutenção (SAP, Maximo)
5. **Relatórios Avançados**: PDF, Excel, dashboards
6. **Fotos**: Upload de imagens dos problemas
7. **QR Codes**: Identificação rápida de equipamentos
8. **Offline**: Funcionamento sem internet
9. **Cronômetro**: Tempo de execução dos testes
10. **Checklist Customizável**: Diferentes tipos de teste por equipamento

### Integrações Possíveis
- **CMMS** (Computerized Maintenance Management System)
- **ERP** (Enterprise Resource Planning)
- **SCADA** (Supervisory Control and Data Acquisition)
- **PI System** (Plant Information System)
- **Sistemas de Workflow** para aprovações

## 📊 Exemplo de Dados

O sistema inclui um seeder com dados de exemplo:
- 3 Áreas (Produção, Utilidades, Tratamento)
- 10 Equipamentos diversos
- 1 Parada em andamento
- Testes com diferentes status para demonstração

## 🎥 Demonstração

A demonstração está disponível no arquivo `demo-sistema-checklist.html` que simula a interface principal do sistema com dados de exemplo.

Execute: `python -m http.server 8080` e acesse `http://localhost:8080/demo-sistema-checklist.html`

## 📞 Suporte

Para dúvidas sobre implementação ou customizações:
1. Consulte a documentação do Laravel
2. Verifique os comentários no código
3. Teste as funcionalidades com dados de exemplo
4. Adapte conforme suas necessidades específicas

---

**Desenvolvido com ❤️ usando Laravel + Bootstrap**

## 🔧 Funcionalidades Implementadas Recentemente

### ✨ Sistema Completo de Relatórios
- **Relatório Geral**: Visualização completa de todas as áreas e equipamentos com status dinâmico
- **Relatório de Pendências**: Foco específico em equipamentos com problemas ou não testados
- **Versões para Impressão**: Layouts otimizados para documentação física
- **Cálculo Dinâmico**: Status calculado em tempo real baseado nos itens de checklist

### 📱 Interface Responsiva Completa
- Design mobile-first otimizado para tablets industriais
- Navegação intuitiva com breadcrumbs e botões de ação
- Modais para visualização de imagens e detalhes
- Cards organizados por área com indicadores visuais claros

### 📊 Dashboard Funcional
- Métricas em tempo real de progresso das paradas
- Gráficos de equipamentos por status
- Contadores dinâmicos de áreas e equipamentos
- Navegação direta para relatórios específicos

### 🖼️ Sistema de Imagens
- Upload e armazenamento de fotos dos equipamentos
- Galeria de imagens nos relatórios
- Visualização em modal com zoom
- Integração nas versões para impressão

### 🖨️ Otimização para Impressão
- Layouts especializados para documentação
- Quebras de página inteligentes
- Campos para assinatura e carimbo
- Imagens incorporadas nos relatórios impressos

### 🔄 Cálculo Dinâmico de Status
- Status baseado em itens de checklist individuais
- Tratamento especial para itens "N/A" (Não Aplicável)
- Consistência entre todas as interfaces do sistema
- Atualização automática sem necessidade de refresh

## 📂 Estrutura de Arquivos Principais

### Views (Blade Templates)
- `resources/views/paradas/show.blade.php` - Interface principal de testes
- `resources/views/paradas/relatorio.blade.php` - Relatório geral completo
- `resources/views/paradas/pendencias.blade.php` - Relatório de pendências
- `resources/views/paradas/pendencias-print.blade.php` - Versão para impressão
- `resources/views/dashboard.blade.php` - Dashboard principal

### Controllers
- `app/Http/Controllers/ParadaController.php` - Gestão completa de paradas
- `app/Http/Controllers/DashboardController.php` - Métricas e estatísticas
- `app/Http/Controllers/TesteController.php` - Atualização de testes via AJAX

### Models
- `app/Models/Parada.php` - Métodos para cálculo dinâmico de status
- `app/Models/Teste.php` - Relacionamentos e validações
- `app/Models/Equipamento.php` - Gestão de equipamentos e imagens

## 🚀 Deployment

Este sistema está preparado para deployment em ambiente de produção:

- Configuração otimizada para Laravel 11.x
- Banco SQLite para facilidade de deployment
- Assets compilados e otimizados
- Configurações de cache prontas para produção

### Comandos de Deployment
```bash
# Otimizar para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Criar link simbólico para storage
php artisan storage:link
```

## 📞 Suporte Técnico

### Resolução de Problemas Comuns
1. **Images não aparecem**: Verificar se `php artisan storage:link` foi executado
2. **Status não atualiza**: Confirmar se campos do checklist estão preenchidos
3. **Layout quebrado**: Verificar se Bootstrap 5 está carregado corretamente

### Debugging
- Logs disponíveis em `storage/logs/laravel.log`
- Debug mode pode ser ativado via `.env` com `APP_DEBUG=true`
- Banco SQLite em `database/database.sqlite`

## 📄 License

Este projeto utiliza o framework Laravel sob licença MIT.
