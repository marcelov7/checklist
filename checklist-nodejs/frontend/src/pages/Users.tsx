import React, { useState, useEffect } from 'react';
import { Card, CardHeader, CardTitle, CardContent, Badge } from '../components/ui';
import { Plus, Edit, Trash2, Search, Users as UsersIcon, Mail, Shield, Eye, EyeOff } from 'lucide-react';
import { 
  EntityForm, 
  FormConfigs, 
  MobileForm, 
  MobileInput, 
  MobileSelect, 
  FormActions,
  useResponsive 
} from '../components/forms';
import ResponsiveButton, { ResponsiveButtonGroup } from '../components/ResponsiveButton';

interface User {
  id: string;
  nome: string;
  email: string;
  username: string;
  role: 'ADMIN' | 'USER';
  ativo: boolean;
  createdAt: string;
  updatedAt: string;
}

const Users: React.FC = () => {
  const { isMobile, isTablet, getResponsiveClasses } = useResponsive();
  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [filterRole, setFilterRole] = useState<string>('');
  const [filterStatus, setFilterStatus] = useState<string>('');
  const [showForm, setShowForm] = useState(false);
  const [editingUser, setEditingUser] = useState<User | null>(null);
  const [showPassword, setShowPassword] = useState(false);
  const [formData, setFormData] = useState({
    nome: '',
    email: '',
    password: '',
    confirmPassword: '',
    role: 'operador' as 'admin' | 'supervisor' | 'operador',
    ativo: true
  });

  // Simulação de dados - posteriormente será substituído por chamadas à API
  const mockUsers: User[] = [
    {
      id: 1,
      nome: 'João Silva',
      email: 'joao.silva@empresa.com',
      role: 'admin',
      ativo: true,
      ultimo_acesso: '2024-01-20T14:30:00Z',
      created_at: '2024-01-01T08:00:00Z',
      updated_at: '2024-01-20T14:30:00Z'
    },
    {
      id: 2,
      nome: 'Maria Santos',
      email: 'maria.santos@empresa.com',
      role: 'supervisor',
      ativo: true,
      ultimo_acesso: '2024-01-20T16:45:00Z',
      created_at: '2024-01-05T10:15:00Z',
      updated_at: '2024-01-20T16:45:00Z'
    },
    {
      id: 3,
      nome: 'Pedro Oliveira',
      email: 'pedro.oliveira@empresa.com',
      role: 'operador',
      ativo: true,
      ultimo_acesso: '2024-01-19T18:20:00Z',
      created_at: '2024-01-10T14:30:00Z',
      updated_at: '2024-01-19T18:20:00Z'
    },
    {
      id: 4,
      nome: 'Ana Costa',
      email: 'ana.costa@empresa.com',
      role: 'operador',
      ativo: false,
      ultimo_acesso: '2024-01-15T12:00:00Z',
      created_at: '2024-01-08T09:45:00Z',
      updated_at: '2024-01-18T11:30:00Z'
    }
  ];

  useEffect(() => {
    // Simula carregamento de dados
    setTimeout(() => {
      setUsers(mockUsers);
      setLoading(false);
    }, 1000);
  }, []);

  const filteredUsers = users.filter(user => {
    const matchesSearch = user.nome.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         user.email.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesRole = filterRole === '' || user.role === filterRole;
    const matchesStatus = filterStatus === '' || 
                         (filterStatus === 'ativo' && user.ativo) ||
                         (filterStatus === 'inativo' && !user.ativo);
    return matchesSearch && matchesRole && matchesStatus;
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    // Validação de senha
    if (!editingUser && formData.password !== formData.confirmPassword) {
      alert('As senhas não coincidem!');
      return;
    }

    if (!editingUser && formData.password.length < 6) {
      alert('A senha deve ter pelo menos 6 caracteres!');
      return;
    }
    
    if (editingUser) {
      // Atualizar usuário existente
      const updatedUser = {
        ...editingUser,
        nome: formData.nome,
        email: formData.email,
        role: formData.role,
        ativo: formData.ativo,
        updated_at: new Date().toISOString()
      };
      setUsers(users.map(user => user.id === editingUser.id ? updatedUser : user));
    } else {
      // Criar novo usuário
      const newUser: User = {
        id: Math.max(...users.map(u => u.id)) + 1,
        nome: formData.nome,
        email: formData.email,
        role: formData.role,
        ativo: formData.ativo,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      };
      setUsers([...users, newUser]);
    }

    resetForm();
  };

  const handleEdit = (user: User) => {
    setEditingUser(user);
    setFormData({
      nome: user.nome,
      email: user.email,
      password: '',
      confirmPassword: '',
      role: user.role,
      ativo: user.ativo
    });
    setShowForm(true);
  };

  const handleDelete = (id: number) => {
    if (window.confirm('Tem certeza que deseja excluir este usuário?')) {
      setUsers(users.filter(user => user.id !== id));
    }
  };

  const resetForm = () => {
    setFormData({ 
      nome: '', 
      email: '', 
      password: '', 
      confirmPassword: '', 
      role: 'operador', 
      ativo: true 
    });
    setEditingUser(null);
    setShowForm(false);
    setShowPassword(false);
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  const getRoleBadgeVariant = (role: string) => {
    switch (role) {
      case 'admin': return 'danger';
      case 'supervisor': return 'warning';
      case 'operador': return 'default';
      default: return 'default';
    }
  };

  const getRoleLabel = (role: string) => {
    switch (role) {
      case 'admin': return 'Administrador';
      case 'supervisor': return 'Supervisor';
      case 'operador': return 'Operador';
      default: return role;
    }
  };

  if (loading) {
    return (
      <div className="flex justify-center items-center" style={{ minHeight: '400px' }}>
        <div className="loading-spinner" style={{ width: '2rem', height: '2rem' }} />
        <span style={{ marginLeft: '0.5rem' }}>Carregando usuários...</span>
      </div>
    );
  }

  return (
    <div 
      className="container"
      style={{
        padding: isMobile ? 'var(--mobile-padding)' : '1rem',
        maxWidth: '100%'
      }}
    >
      {/* Header */}
      <div 
        className={getResponsiveClasses('flex justify-between items-center', 'flex-col gap-4')}
        style={{ marginBottom: '2rem' }}
      >
        <div className={isMobile ? 'text-center' : ''}>
          <h1 style={{ 
            fontSize: isMobile ? '1.75rem' : '2rem', 
            fontWeight: 'bold', 
            marginBottom: '0.5rem' 
          }}>
            Usuários
          </h1>
          <p style={{ color: '#6b7280' }}>
            Gerencie os usuários do sistema
          </p>
        </div>
        <ResponsiveButton 
          onClick={() => setShowForm(true)}
          variant="primary"
          icon={<Plus />}
          fullWidthOnMobile={true}
        >
          Novo Usuário
        </ResponsiveButton>
      </div>

      {/* Formulário */}
      {showForm && (
        <div style={{ marginBottom: '2rem' }}>
          <EntityForm
            type="user"
            data={editingUser ? {
              nome: editingUser.nome,
              email: editingUser.email,
              role: editingUser.role,
              ativo: editingUser.ativo
            } : undefined}
            onSubmit={(data) => {
              if (editingUser) {
                // Atualizar usuário existente
                const updatedUser = {
                  ...editingUser,
                  nome: data.nome,
                  email: data.email,
                  role: data.role,
                  ativo: data.ativo,
                  updated_at: new Date().toISOString()
                };
                setUsers(users.map(user => user.id === editingUser.id ? updatedUser : user));
              } else {
                // Criar novo usuário
                const newUser: User = {
                  id: Math.max(...users.map(u => u.id)) + 1,
                  nome: data.nome,
                  email: data.email,
                  role: data.role,
                  ativo: data.ativo,
                  created_at: new Date().toISOString(),
                  updated_at: new Date().toISOString()
                };
                setUsers([...users, newUser]);
              }
              resetForm();
            }}
            onCancel={resetForm}
            title={editingUser ? 'Editar Usuário' : 'Novo Usuário'}
          />
        </div>
      )}

      {/* Filtros */}
      <Card style={{ marginBottom: '2rem' }}>
        <CardContent style={{ padding: isMobile ? 'var(--mobile-padding)' : '1rem' }}>
          <div 
            className={getResponsiveClasses(
              'grid grid-cols-3 gap-4 items-end',
              'grid grid-cols-1 gap-4'
            )}
          >
            <MobileInput
              leftIcon={<Search style={{ width: '1rem', height: '1rem' }} />}
              placeholder="Buscar usuários..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
            
            <MobileSelect
              label="Função"
              value={filterRole}
              onChange={(e) => setFilterRole(e.target.value)}
              options={[
                { value: '', label: 'Todas' },
                { value: 'admin', label: 'Administrador' },
                { value: 'supervisor', label: 'Supervisor' },
                { value: 'operador', label: 'Operador' }
              ]}
            />

            <MobileSelect
              label="Status"
              value={filterStatus}
              onChange={(e) => setFilterStatus(e.target.value)}
              options={[
                { value: '', label: 'Todos' },
                { value: 'ativo', label: 'Ativos' },
                { value: 'inativo', label: 'Inativos' }
              ]}
            />
          </div>
        </CardContent>
      </Card>

      {/* Lista de Usuários */}
      <div style={{ display: 'grid', gap: '1rem' }}>
        {filteredUsers.length === 0 ? (
          <Card>
            <CardContent style={{ textAlign: 'center', padding: '3rem' }}>
              <UsersIcon style={{ width: '3rem', height: '3rem', margin: '0 auto 1rem', color: '#9ca3af' }} />
              <h3 style={{ fontSize: '1.125rem', fontWeight: '600', marginBottom: '0.5rem' }}>
                Nenhum usuário encontrado
              </h3>
              <p style={{ color: '#6b7280' }}>
                {searchTerm || filterRole || filterStatus ? 'Tente ajustar os filtros de busca.' : 'Comece criando seu primeiro usuário.'}
              </p>
            </CardContent>
          </Card>
        ) : (
          filteredUsers.map((user) => (
            <Card key={user.id}>
              <CardContent style={{ padding: '1.5rem' }}>
                <div className="flex justify-between items-start">
                  <div style={{ flex: 1 }}>
                    <div className="flex items-center gap-2" style={{ marginBottom: '0.5rem' }}>
                      <h3 style={{ fontSize: '1.125rem', fontWeight: '600' }}>
                        {user.nome}
                      </h3>
                      <Badge variant={user.ativo ? 'success' : 'secondary'}>
                        {user.ativo ? 'Ativo' : 'Inativo'}
                      </Badge>
                      <Badge variant={getRoleBadgeVariant(user.role)}>
                        {getRoleLabel(user.role)}
                      </Badge>
                    </div>
                    
                    <div className="flex items-center gap-1" style={{ marginBottom: '0.75rem' }}>
                      <Mail style={{ width: '1rem', height: '1rem', color: '#6b7280' }} />
                      <span style={{ fontSize: '0.875rem', color: '#6b7280' }}>
                        {user.email}
                      </span>
                    </div>
                    
                    <div style={{ fontSize: '0.875rem', color: '#9ca3af' }}>
                      <p>Criado em: {formatDate(user.created_at)}</p>
                      {user.ultimo_acesso && (
                        <p>Último acesso: {formatDate(user.ultimo_acesso)}</p>
                      )}
                      {user.updated_at !== user.created_at && (
                        <p>Atualizado em: {formatDate(user.updated_at)}</p>
                      )}
                    </div>
                  </div>
                  
                  <ResponsiveButtonGroup>
                    <ResponsiveButton
                      variant="outline"
                      size="sm"
                      onClick={() => handleEdit(user)}
                      icon={<Edit />}
                      iconOnly={!isMobile}
                    >
                      {isMobile ? 'Editar' : ''}
                    </ResponsiveButton>
                    <ResponsiveButton
                      variant="danger"
                      size="sm"
                      onClick={() => handleDelete(user.id)}
                      icon={<Trash2 />}
                      iconOnly={!isMobile}
                    >
                      {isMobile ? 'Excluir' : ''}
                    </ResponsiveButton>
                  </ResponsiveButtonGroup>
                </div>
              </CardContent>
            </Card>
          ))
        )}
      </div>

      {/* Estatísticas */}
      <div 
        className={getResponsiveClasses(
          'grid grid-cols-4 gap-4',
          'grid grid-cols-2 gap-3'
        )}
        style={{ marginTop: '2rem' }}
      >
        <Card>
          <CardContent style={{ 
            padding: isMobile ? 'var(--mobile-padding)' : '1.5rem', 
            textAlign: 'center' 
          }}>
            <div style={{ 
              fontSize: isMobile ? '1.5rem' : '2rem', 
              fontWeight: 'bold', 
              color: '#2563eb' 
            }}>
              {users.length}
            </div>
            <div style={{ 
              color: '#6b7280',
              fontSize: isMobile ? '0.75rem' : '0.875rem'
            }}>
              Total de Usuários
            </div>
          </CardContent>
        </Card>
        
        <Card>
          <CardContent style={{ 
            padding: isMobile ? 'var(--mobile-padding)' : '1.5rem', 
            textAlign: 'center' 
          }}>
            <div style={{ 
              fontSize: isMobile ? '1.5rem' : '2rem', 
              fontWeight: 'bold', 
              color: '#059669' 
            }}>
              {users.filter(u => u.ativo).length}
            </div>
            <div style={{ 
              color: '#6b7280',
              fontSize: isMobile ? '0.75rem' : '0.875rem'
            }}>
              Usuários Ativos
            </div>
          </CardContent>
        </Card>
        
        <Card>
          <CardContent style={{ 
            padding: isMobile ? 'var(--mobile-padding)' : '1.5rem', 
            textAlign: 'center' 
          }}>
            <div style={{ 
              fontSize: isMobile ? '1.5rem' : '2rem', 
              fontWeight: 'bold', 
              color: '#dc2626' 
            }}>
              {users.filter(u => u.role === 'admin').length}
            </div>
            <div style={{ 
              color: '#6b7280',
              fontSize: isMobile ? '0.75rem' : '0.875rem'
            }}>
              Administradores
            </div>
          </CardContent>
        </Card>
        
        <Card>
          <CardContent style={{ 
            padding: isMobile ? 'var(--mobile-padding)' : '1.5rem', 
            textAlign: 'center' 
          }}>
            <div style={{ 
              fontSize: isMobile ? '1.5rem' : '2rem', 
              fontWeight: 'bold', 
              color: '#7c3aed' 
            }}>
              {users.filter(u => u.role === 'operador').length}
            </div>
            <div style={{ 
              color: '#6b7280',
              fontSize: isMobile ? '0.75rem' : '0.875rem'
            }}>
              Operadores
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
};

export default Users;