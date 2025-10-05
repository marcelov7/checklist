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

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
