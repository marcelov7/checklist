import React, { useState, useEffect } from 'react';
import { Card, CardHeader, CardTitle, CardContent, Badge } from '../components/ui';
import { Plus, Edit, Trash2, Search, CheckSquare, MapPin } from 'lucide-react';
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

interface Area {
  id: number;
  nome: string;
}

interface Parada {
  id: number;
  nome: string;
  descricao: string;
  area_id: number;
  area?: Area;
  ativa: boolean;
  ordem: number;
  created_at: string;
  updated_at: string;
}

const Paradas: React.FC = () => {
  const { isMobile, isTablet, getResponsiveClasses } = useResponsive();
  const [paradas, setParadas] = useState<Parada[]>([]);
  const [areas, setAreas] = useState<Area[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [filterArea, setFilterArea] = useState<number | ''>('');
  const [showForm, setShowForm] = useState(false);
  const [editingParada, setEditingParada] = useState<Parada | null>(null);
  const [formData, setFormData] = useState({
    nome: '',
    descricao: '',
    area_id: '',
    ativa: true,
    ordem: 1
  });

  // Simulação de dados - posteriormente será substituído por chamadas à API
  const mockAreas: Area[] = [
    { id: 1, nome: 'Produção' },
    { id: 2, nome: 'Qualidade' },
    { id: 3, nome: 'Manutenção' }
  ];

  const mockParadas: Parada[] = [
    {
      id: 1,
      nome: 'Verificar Equipamentos',
      descricao: 'Verificação geral dos equipamentos de produção',
      area_id: 1,
      area: { id: 1, nome: 'Produção' },
      ativa: true,
      ordem: 1,
      created_at: '2024-01-15T10:00:00Z',
      updated_at: '2024-01-15T10:00:00Z'
    },
    {
      id: 2,
      nome: 'Controle de Temperatura',
      descricao: 'Verificar e registrar temperaturas dos fornos',
      area_id: 1,
      area: { id: 1, nome: 'Produção' },
      ativa: true,
      ordem: 2,
      created_at: '2024-01-16T14:30:00Z',
      updated_at: '2024-01-16T14:30:00Z'
    },
    {
      id: 3,
      nome: 'Inspeção Visual',
      descricao: 'Inspeção visual dos produtos acabados',
      area_id: 2,
      area: { id: 2, nome: 'Qualidade' },
      ativa: true,
      ordem: 1,
      created_at: '2024-01-17T09:15:00Z',
      updated_at: '2024-01-17T09:15:00Z'
    },
    {
      id: 4,
      nome: 'Limpeza Geral',
      descricao: 'Limpeza e organização da área',
      area_id: 3,
      area: { id: 3, nome: 'Manutenção' },
      ativa: false,
      ordem: 1,
      created_at: '2024-01-18T16:45:00Z',
      updated_at: '2024-01-18T16:45:00Z'
    }
  ];

  useEffect(() => {
    // Simula carregamento de dados
    setTimeout(() => {
      setAreas(mockAreas);
      setParadas(mockParadas);
      setLoading(false);
    }, 1000);
  }, []);

  const filteredParadas = paradas.filter(parada => {
    const matchesSearch = parada.nome.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         parada.descricao.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesArea = filterArea === '' || parada.area_id === filterArea;
    return matchesSearch && matchesArea;
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    const selectedArea = areas.find(area => area.id === parseInt(formData.area_id));
    
    if (editingParada) {
      // Atualizar parada existente
      const updatedParada = {
        ...editingParada,
        nome: formData.nome,
        descricao: formData.descricao,
        area_id: parseInt(formData.area_id),
        area: selectedArea,
        ativa: formData.ativa,
        ordem: formData.ordem,
        updated_at: new Date().toISOString()
      };
      setParadas(paradas.map(parada => parada.id === editingParada.id ? updatedParada : parada));
    } else {
      // Criar nova parada
      const newParada: Parada = {
        id: Math.max(...paradas.map(p => p.id)) + 1,
        nome: formData.nome,
        descricao: formData.descricao,
        area_id: parseInt(formData.area_id),
        area: selectedArea,
        ativa: formData.ativa,
        ordem: formData.ordem,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      };
      setParadas([...paradas, newParada]);
    }

    resetForm();
  };

  const handleEdit = (parada: Parada) => {
    setEditingParada(parada);
    setFormData({
      nome: parada.nome,
      descricao: parada.descricao,
      area_id: parada.area_id.toString(),
      ativa: parada.ativa,
      ordem: parada.ordem
    });
    setShowForm(true);
  };

  const handleDelete = (id: number) => {
    if (window.confirm('Tem certeza que deseja excluir esta parada?')) {
      setParadas(paradas.filter(parada => parada.id !== id));
    }
  };

  const resetForm = () => {
    setFormData({ nome: '', descricao: '', area_id: '', ativa: true, ordem: 1 });
    setEditingParada(null);
    setShowForm(false);
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

  if (loading) {
    return (
      <div className="flex justify-center items-center" style={{ minHeight: '400px' }}>
        <div className="loading-spinner" style={{ width: '2rem', height: '2rem' }} />
        <span style={{ marginLeft: '0.5rem' }}>Carregando paradas...</span>
      </div>
    );
  }

  return (
    <div className="container">
      {/* Header */}
      <div className="flex justify-between items-center" style={{ marginBottom: '2rem' }}>
        <div>
          <h1 style={{ fontSize: '2rem', fontWeight: 'bold', marginBottom: '0.5rem' }}>
            Paradas
          </h1>
          <p style={{ color: '#6b7280' }}>
            Gerencie as paradas do checklist por área
          </p>
        </div>
        <ResponsiveButton onClick={() => setShowForm(true)}>
          <Plus style={{ width: '1rem', height: '1rem', marginRight: '0.5rem' }} />
          Nova Parada
        </ResponsiveButton>
      </div>

      {/* Formulário */}
      {showForm && (
        <Card style={{ marginBottom: '2rem' }}>
          <CardHeader>
            <CardTitle>
              {editingParada ? 'Editar Parada' : 'Nova Parada'}
            </CardTitle>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit}>
              <div style={{ display: 'grid', gap: '1rem', marginBottom: '1.5rem' }}>
                <MobileInput
                  label="Nome da Parada"
                  value={formData.nome}
                  onChange={(e) => setFormData({ ...formData, nome: e.target.value })}
                  required
                  placeholder="Digite o nome da parada"
                />
                
                <MobileInput
                  label="Descrição"
                  value={formData.descricao}
                  onChange={(e) => setFormData({ ...formData, descricao: e.target.value })}
                  placeholder="Digite a descrição da parada"
                />

                <div>
                  <label style={{ display: 'block', marginBottom: '0.5rem', fontWeight: '500' }}>
                    Área
                  </label>
                  <select
                    value={formData.area_id}
                    onChange={(e) => setFormData({ ...formData, area_id: e.target.value })}
                    required
                    style={{
                      width: '100%',
                      padding: '0.5rem',
                      border: '1px solid #d1d5db',
                      borderRadius: '0.375rem',
                      fontSize: '0.875rem'
                    }}
                  >
                    <option value="">Selecione uma área</option>
                    {areas.map(area => (
                      <option key={area.id} value={area.id}>
                        {area.nome}
                      </option>
                    ))}
                  </select>
                </div>

                <MobileInput
                  label="Ordem"
                  type="number"
                  value={formData.ordem}
                  onChange={(e) => setFormData({ ...formData, ordem: parseInt(e.target.value) || 1 })}
                  min="1"
                  required
                />

                <div>
                  <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                    <input
                      type="checkbox"
                      checked={formData.ativa}
                      onChange={(e) => setFormData({ ...formData, ativa: e.target.checked })}
                    />
                    Parada ativa
                  </label>
                </div>
              </div>

              <div className="flex gap-2">
                <ResponsiveButton type="submit">
                  {editingParada ? 'Atualizar' : 'Criar'} Parada
                </ResponsiveButton>
                <ResponsiveButton variant="secondary" type="button" onClick={resetForm}>
                  Cancelar
                </ResponsiveButton>
              </div>
            </form>
          </CardContent>
        </Card>
      )}

      {/* Filtros */}
      <Card style={{ marginBottom: '2rem' }}>
        <CardContent style={{ padding: '1rem' }}>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr auto', gap: '1rem', alignItems: 'end' }}>
            <MobileInput
              leftIcon={<Search style={{ width: '1rem', height: '1rem' }} />}
              placeholder="Buscar paradas..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
            
            <div>
              <label style={{ display: 'block', marginBottom: '0.5rem', fontSize: '0.875rem', fontWeight: '500' }}>
                Filtrar por Área
              </label>
              <select
                value={filterArea}
                onChange={(e) => setFilterArea(e.target.value === '' ? '' : parseInt(e.target.value))}
                style={{
                  padding: '0.5rem',
                  border: '1px solid #d1d5db',
                  borderRadius: '0.375rem',
                  fontSize: '0.875rem',
                  minWidth: '150px'
                }}
              >
                <option value="">Todas as áreas</option>
                {areas.map(area => (
                  <option key={area.id} value={area.id}>
                    {area.nome}
                  </option>
                ))}
              </select>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Lista de Paradas */}
      <div style={{ display: 'grid', gap: '1rem' }}>
        {filteredParadas.length === 0 ? (
          <Card>
            <CardContent style={{ textAlign: 'center', padding: '3rem' }}>
              <CheckSquare style={{ width: '3rem', height: '3rem', margin: '0 auto 1rem', color: '#9ca3af' }} />
              <h3 style={{ fontSize: '1.125rem', fontWeight: '600', marginBottom: '0.5rem' }}>
                Nenhuma parada encontrada
              </h3>
              <p style={{ color: '#6b7280' }}>
                {searchTerm || filterArea ? 'Tente ajustar os filtros de busca.' : 'Comece criando sua primeira parada.'}
              </p>
            </CardContent>
          </Card>
        ) : (
          filteredParadas
            .sort((a, b) => {
              // Ordenar por área e depois por ordem
              if (a.area_id !== b.area_id) {
                return a.area_id - b.area_id;
              }
              return a.ordem - b.ordem;
            })
            .map((parada) => (
              <Card key={parada.id}>
                <CardContent style={{ padding: '1.5rem' }}>
                  <div className="flex justify-between items-start">
                    <div style={{ flex: 1 }}>
                      <div className="flex items-center gap-2" style={{ marginBottom: '0.5rem' }}>
                        <h3 style={{ fontSize: '1.125rem', fontWeight: '600' }}>
                          {parada.nome}
                        </h3>
                        <Badge variant={parada.ativa ? 'success' : 'secondary'}>
                          {parada.ativa ? 'Ativa' : 'Inativa'}
                        </Badge>
                        <Badge variant="outline">
                          Ordem {parada.ordem}
                        </Badge>
                      </div>
                      
                      <div className="flex items-center gap-1" style={{ marginBottom: '0.5rem' }}>
                        <MapPin style={{ width: '1rem', height: '1rem', color: '#6b7280' }} />
                        <span style={{ fontSize: '0.875rem', color: '#6b7280' }}>
                          {parada.area?.nome}
                        </span>
                      </div>
                      
                      {parada.descricao && (
                        <p style={{ color: '#6b7280', marginBottom: '0.75rem' }}>
                          {parada.descricao}
                        </p>
                      )}
                      
                      <div style={{ fontSize: '0.875rem', color: '#9ca3af' }}>
                        <p>Criada em: {formatDate(parada.created_at)}</p>
                        {parada.updated_at !== parada.created_at && (
                          <p>Atualizada em: {formatDate(parada.updated_at)}</p>
                        )}
                      </div>
                    </div>
                    
                    <div className="flex gap-2">
                      <ResponsiveButton
                        variant="secondary"
                        size="small"
                        onClick={() => handleEdit(parada)}
                      >
                        <Edit style={{ width: '1rem', height: '1rem' }} />
                      </ResponsiveButton>
                      <ResponsiveButton
                        variant="danger"
                        size="small"
                        onClick={() => handleDelete(parada.id)}
                      >
                        <Trash2 style={{ width: '1rem', height: '1rem' }} />
                      </ResponsiveButton>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))
        )}
      </div>

      {/* Estatísticas */}
      <div style={{ marginTop: '2rem', display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '1rem' }}>
        <Card>
          <CardContent style={{ padding: '1.5rem', textAlign: 'center' }}>
            <div style={{ fontSize: '2rem', fontWeight: 'bold', color: '#2563eb' }}>
              {paradas.length}
            </div>
            <div style={{ color: '#6b7280' }}>Total de Paradas</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardContent style={{ padding: '1.5rem', textAlign: 'center' }}>
            <div style={{ fontSize: '2rem', fontWeight: 'bold', color: '#059669' }}>
              {paradas.filter(p => p.ativa).length}
            </div>
            <div style={{ color: '#6b7280' }}>Paradas Ativas</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardContent style={{ padding: '1.5rem', textAlign: 'center' }}>
            <div style={{ fontSize: '2rem', fontWeight: 'bold', color: '#dc2626' }}>
              {paradas.filter(p => !p.ativa).length}
            </div>
            <div style={{ color: '#6b7280' }}>Paradas Inativas</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardContent style={{ padding: '1.5rem', textAlign: 'center' }}>
            <div style={{ fontSize: '2rem', fontWeight: 'bold', color: '#7c3aed' }}>
              {areas.length}
            </div>
            <div style={{ color: '#6b7280' }}>Áreas Vinculadas</div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
};

export default Paradas;