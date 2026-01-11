import React, { useState, useEffect } from 'react';
import { Card, CardHeader, CardTitle, CardContent, Badge } from '../components/ui';
import { Plus, Edit, Trash2, Search, MapPin } from 'lucide-react';
import { areaService } from '../services/areaService';
import { useAreaStore, Area } from '../stores/areaStore';
import { 
  EntityForm, 
  FormConfigs, 
  MobileForm, 
  MobileInput, 
  FormActions,
  useResponsive 
} from '../components/forms';
import ResponsiveButton, { ResponsiveButtonGroup } from '../components/ResponsiveButton';

const Areas: React.FC = () => {
  const { areas, isLoading, error, setAreas, addArea, updateArea, removeArea, setLoading, setError } = useAreaStore();
  const { isMobile, isTablet, getResponsiveClasses } = useResponsive();
  const [searchTerm, setSearchTerm] = useState('');
  const [showForm, setShowForm] = useState(false);
  const [editingArea, setEditingArea] = useState<Area | null>(null);
  const [formData, setFormData] = useState({
    nome: '',
    descricao: '',
    ativa: true
  });

  // Carregar áreas da API
  const loadAreas = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await areaService.getAreas();
      setAreas(response.areas || []);
    } catch (err: any) {
      setError(err.message || 'Erro ao carregar áreas');
      console.error('Erro ao carregar áreas:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadAreas();
  }, []);

  const filteredAreas = areas.filter(area =>
    area.nome.toLowerCase().includes(searchTerm.toLowerCase()) ||
    area.descricao.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    try {
      setError(null);
      
      if (editingArea) {
        // Atualizar área existente
        const updatedArea = await areaService.updateArea(editingArea.id, {
          nome: formData.nome,
          descricao: formData.descricao,
          ativo: formData.ativa
        });
        updateArea(editingArea.id, updatedArea);
      } else {
        // Criar nova área
        const newArea = await areaService.createArea({
          nome: formData.nome,
          descricao: formData.descricao,
          ativo: formData.ativa
        });
        addArea(newArea);
      }

      resetForm();
    } catch (err: any) {
      setError(err.message || 'Erro ao salvar área');
      console.error('Erro ao salvar área:', err);
    }
  };

  const handleEdit = (area: Area) => {
    setEditingArea(area);
    setFormData({
      nome: area.nome,
      descricao: area.descricao || '',
      ativa: area.ativo
    });
    setShowForm(true);
  };

  const handleDelete = async (id: string) => {
    if (window.confirm('Tem certeza que deseja excluir esta área?')) {
      try {
        setError(null);
        await areaService.deleteArea(id);
        removeArea(id);
      } catch (err: any) {
        setError(err.message || 'Erro ao excluir área');
        console.error('Erro ao excluir área:', err);
      }
    }
  };

  const resetForm = () => {
    setFormData({ nome: '', descricao: '', ativa: true });
    setEditingArea(null);
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

  if (isLoading) {
    return (
      <div className="flex justify-center items-center" style={{ minHeight: '400px' }}>
        <div className="loading-spinner" style={{ width: '2rem', height: '2rem' }} />
        <span style={{ marginLeft: '0.5rem' }}>Carregando áreas...</span>
      </div>
    );
  }

  return (
    <div className="container">
      {/* Header */}
      <div className="flex justify-between items-center" style={{ marginBottom: '2rem' }}>
        <div>
          <h1 style={{ fontSize: '2rem', fontWeight: 'bold', marginBottom: '0.5rem' }}>
            Áreas
          </h1>
          <p style={{ color: '#6b7280' }}>
            Gerencie as áreas do sistema de checklist
          </p>
        </div>
        <ResponsiveButton onClick={() => setShowForm(true)}>
          <Plus style={{ width: '1rem', height: '1rem', marginRight: '0.5rem' }} />
          Nova Área
        </ResponsiveButton>
      </div>

      {error && (
        <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
          {error}
        </div>
      )}

      {/* Formulário */}
      {showForm && (
        <Card style={{ marginBottom: '2rem' }}>
          <CardHeader>
            <CardTitle>
              {editingArea ? 'Editar Área' : 'Nova Área'}
            </CardTitle>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit}>
              <div style={{ display: 'grid', gap: '1rem', marginBottom: '1.5rem' }}>
                <MobileInput
                  label="Nome da Área"
                  value={formData.nome}
                  onChange={(e) => setFormData({ ...formData, nome: e.target.value })}
                  required
                  placeholder="Digite o nome da área"
                />
                
                <MobileInput
                  label="Descrição"
                  value={formData.descricao}
                  onChange={(e) => setFormData({ ...formData, descricao: e.target.value })}
                  placeholder="Digite a descrição da área"
                />

                <div>
                  <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                    <input
                      type="checkbox"
                      checked={formData.ativa}
                      onChange={(e) => setFormData({ ...formData, ativa: e.target.checked })}
                    />
                    Área ativa
                  </label>
                </div>
              </div>

              <div className="flex gap-2">
                <ResponsiveButton type="submit">
                  {editingArea ? 'Atualizar' : 'Criar'} Área
                </ResponsiveButton>
                <ResponsiveButton variant="secondary" type="button" onClick={resetForm}>
                  Cancelar
                </ResponsiveButton>
              </div>
            </form>
          </CardContent>
        </Card>
      )}

      {/* Busca */}
      <Card style={{ marginBottom: '2rem' }}>
        <CardContent style={{ padding: '1rem' }}>
          <MobileInput
            leftIcon={<Search style={{ width: '1rem', height: '1rem' }} />}
            placeholder="Buscar áreas..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
        </CardContent>
      </Card>

      {/* Lista de Áreas */}
      <div style={{ display: 'grid', gap: '1rem' }}>
        {filteredAreas.length === 0 ? (
          <Card>
            <CardContent style={{ textAlign: 'center', padding: '3rem' }}>
              <MapPin style={{ width: '3rem', height: '3rem', margin: '0 auto 1rem', color: '#9ca3af' }} />
              <h3 style={{ fontSize: '1.125rem', fontWeight: '600', marginBottom: '0.5rem' }}>
                Nenhuma área encontrada
              </h3>
              <p style={{ color: '#6b7280' }}>
                {searchTerm ? 'Tente ajustar os termos de busca.' : 'Comece criando sua primeira área.'}
              </p>
            </CardContent>
          </Card>
        ) : (
          filteredAreas.map((area) => (
            <Card key={area.id}>
              <CardContent style={{ padding: '1.5rem' }}>
                <div className="flex justify-between items-start">
                  <div style={{ flex: 1 }}>
                    <div className="flex items-center gap-2" style={{ marginBottom: '0.5rem' }}>
                      <h3 style={{ fontSize: '1.125rem', fontWeight: '600' }}>
                        {area.nome}
                      </h3>
                      <Badge variant={area.ativo ? 'success' : 'secondary'}>
                        {area.ativo ? 'Ativa' : 'Inativa'}
                      </Badge>
                    </div>
                    
                    {area.descricao && (
                      <p style={{ color: '#6b7280', marginBottom: '0.75rem' }}>
                        {area.descricao}
                      </p>
                    )}
                    
                    <div style={{ fontSize: '0.875rem', color: '#9ca3af' }}>
                      <p>Criada em: {formatDate(area.createdAt)}</p>
                      {area.updatedAt !== area.createdAt && (
                        <p>Atualizada em: {formatDate(area.updatedAt)}</p>
                      )}
                    </div>
                  </div>
                  
                  <div className="flex gap-2">
                    <ResponsiveButton
                      variant="secondary"
                      size="small"
                      onClick={() => handleEdit(area)}
                    >
                      <Edit style={{ width: '1rem', height: '1rem' }} />
                    </ResponsiveButton>
                    <ResponsiveButton
                      variant="danger"
                      size="small"
                      onClick={() => handleDelete(area.id)}
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
              {areas.length}
            </div>
            <div style={{ color: '#6b7280' }}>Total de Áreas</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardContent style={{ padding: '1.5rem', textAlign: 'center' }}>
            <div style={{ fontSize: '2rem', fontWeight: 'bold', color: '#059669' }}>
              {areas.filter(a => a.ativo).length}
            </div>
            <div style={{ color: '#6b7280' }}>Áreas Ativas</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardContent style={{ padding: '1.5rem', textAlign: 'center' }}>
            <div style={{ fontSize: '2rem', fontWeight: 'bold', color: '#dc2626' }}>
              {areas.filter(a => !a.ativo).length}
            </div>
            <div style={{ color: '#6b7280' }}>Áreas Inativas</div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
};

export default Areas;