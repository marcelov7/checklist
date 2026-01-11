import React, { useState, useEffect } from 'react';
import { Card, CardHeader, CardTitle, CardContent, Badge } from '../components/ui';
import { Plus, Edit, Trash2, Search, Settings, Tag, MapPin, Hash } from 'lucide-react';
import { useEquipmentStore, Equipment } from '../stores/equipmentStore';
import { useAreaStore } from '../stores/areaStore';
import { equipmentService } from '../services/equipmentService';
import { areaService } from '../services/areaService';
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

const Equipments: React.FC = () => {
  const {
    equipments,
    isLoading,
    error,
    setEquipments,
    setLoading,
    setError,
    clearError,
    getEquipmentsSortedByPriority
  } = useEquipmentStore();
  
  const { isMobile, isTablet, getResponsiveClasses } = useResponsive();

  const { areas = [], setAreas } = useAreaStore();

  const [searchTerm, setSearchTerm] = useState('');
  const [selectedArea, setSelectedArea] = useState<string>('');
  const [showForm, setShowForm] = useState(false);
  const [editingEquipment, setEditingEquipment] = useState<Equipment | null>(null);
  const [formData, setFormData] = useState({
    nome: '',
    numeracao: '',
    tipo: '',
    fabricante: '',
    modelo: '',
    numeroSerie: '',
    observacoes: '',
    areaId: '',
    prioridade: 1,
    status: 'ATIVO' as 'ATIVO' | 'INATIVO' | 'MANUTENCAO' | 'PARADO' | 'EM_TESTE'
  });

  // Carregar dados iniciais
  useEffect(() => {
    loadEquipments();
    loadAreas();
  }, []);

  const loadEquipments = async () => {
    try {
      setLoading(true);
      clearError();
      const response = await equipmentService.getEquipments({
        sortBy: 'numeracao',
        sortOrder: 'asc'
      });
      setEquipments(response.equipments || []);
    } catch (err: any) {
      setError(err.message || 'Erro ao carregar equipamentos');
    } finally {
      setLoading(false);
    }
  };

  const loadAreas = async () => {
    try {
      const areas = await areaService.getAllAreas();
      setAreas(areas);
    } catch (err: any) {
      console.error('Erro ao carregar áreas:', err);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    try {
      setLoading(true);
      clearError();

      if (editingEquipment) {
        await equipmentService.updateEquipment(editingEquipment.id, formData);
      } else {
        await equipmentService.createEquipment(formData);
      }

      await loadEquipments();
      resetForm();
    } catch (err: any) {
      setError(err.message || 'Erro ao salvar equipamento');
    } finally {
      setLoading(false);
    }
  };

  const handleEdit = (equipment: Equipment) => {
    setEditingEquipment(equipment);
    setFormData({
      nome: equipment.nome,
      numeracao: equipment.numeracao,
      tipo: equipment.tipo || '',
      fabricante: equipment.fabricante || '',
      modelo: equipment.modelo || '',
      numeroSerie: equipment.numeroSerie || '',
      observacoes: equipment.observacoes || '',
      areaId: equipment.areaId || '',
      prioridade: equipment.prioridade,
      status: equipment.status
    });
    setShowForm(true);
  };

  const handleDelete = async (id: string) => {
    if (!confirm('Tem certeza que deseja excluir este equipamento?')) return;

    try {
      setLoading(true);
      clearError();
      await equipmentService.deleteEquipment(id);
      await loadEquipments();
    } catch (err: any) {
      setError(err.message || 'Erro ao excluir equipamento');
    } finally {
      setLoading(false);
    }
  };

  const resetForm = () => {
    setFormData({
      nome: '',
      tag: '',
      descricao: '',
      areaId: '',
      numeracao: 1,
      ativo: true
    });
    setEditingEquipment(null);
    setShowForm(false);
  };

  const getNextNumeracao = async () => {
    try {
      const nextNum = await equipmentService.getNextNumeracao();
      setFormData(prev => ({ ...prev, numeracao: nextNum }));
    } catch (err) {
      console.error('Erro ao obter próxima numeração:', err);
    }
  };

  // Filtrar equipamentos
  const filteredEquipments = (equipments || []).filter(equipment => {
    const matchesSearch = equipment.nome.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         equipment.numeracao.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesArea = !selectedArea || equipment.areaId === selectedArea;
    return matchesSearch && matchesArea;
  });

  const getAreaName = (areaId: string) => {
    const area = (areas || []).find(a => a.id === areaId);
    return area?.nome || 'Área não encontrada';
  };

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Equipamentos</h1>
          <p className="text-gray-600 mt-1">Gerencie os equipamentos do sistema</p>
        </div>
        <ResponsiveButton
          onClick={() => {
            resetForm();
            getNextNumeracao();
            setShowForm(true);
          }}
        >
          <Plus className="w-4 h-4 mr-2" />
          Novo Equipamento
        </ResponsiveButton>
      </div>

      {/* Filtros */}
      <Card>
        <CardContent className="p-4">
          <div className="flex gap-4 items-center">
            <div className="relative flex-1">
              <MobileInput
                leftIcon={<Search className="w-4 h-4" />}
                placeholder="Buscar por nome ou TAG..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
              />
            </div>
            <MobileSelect
              value={selectedArea}
              onChange={(e) => setSelectedArea(e.target.value)}
            >
              <option value="">Todas as áreas</option>
              {(areas || []).map(area => (
                <option key={area.id} value={area.id}>{area.nome}</option>
              ))}
            </MobileSelect>
          </div>
        </CardContent>
      </Card>

      {/* Mensagem de erro */}
      {error && (
        <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
          {error}
        </div>
      )}

      {/* Formulário */}
      {showForm && (
        <Card>
          <CardHeader>
            <CardTitle>
              {editingEquipment ? 'Editar Equipamento' : 'Novo Equipamento'}
            </CardTitle>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <MobileInput
                    label="Nome do Equipamento *"
                    value={formData.nome}
                    onChange={(e) => setFormData(prev => ({ ...prev, nome: e.target.value }))}
                    placeholder="Ex: Bomba Centrífuga"
                    required
                  />
                </div>
                <div>
                  <MobileInput
                    label="TAG/Numeração *"
                    value={formData.numeracao}
                    onChange={(e) => setFormData(prev => ({ ...prev, numeracao: e.target.value.toUpperCase() }))}
                    placeholder="Ex: BOMB-001"
                    required
                  />
                  <p className="text-xs text-gray-500 mt-1">TAG deve ser única no sistema</p>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Área *
                  </label>
                  <MobileSelect
                    value={formData.areaId}
                    onChange={(e) => setFormData(prev => ({ ...prev, areaId: e.target.value }))}
                    required
                  >
                    <option value="">Selecione uma área</option>
                    {(areas || []).map(area => (
                      <option key={area.id} value={area.id}>{area.nome}</option>
                    ))}
                  </MobileSelect>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Status *
                  </label>
                  <MobileSelect
                    value={formData.status}
                    onChange={(e) => setFormData(prev => ({ ...prev, status: e.target.value as 'ATIVO' | 'INATIVO' | 'MANUTENCAO' }))}
                    required
                  >
                    <option value="ATIVO">Ativo</option>
                    <option value="INATIVO">Inativo</option>
                    <option value="MANUTENCAO">Manutenção</option>
                  </MobileSelect>
                </div>
              </div>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <MobileInput
                    label="Tipo"
                    value={formData.tipo}
                    onChange={(e) => setFormData(prev => ({ ...prev, tipo: e.target.value }))}
                    placeholder="Ex: Bomba, Motor, Válvula"
                  />
                </div>
                <div>
                  <MobileInput
                    label="Fabricante"
                    value={formData.fabricante}
                    onChange={(e) => setFormData(prev => ({ ...prev, fabricante: e.target.value }))}
                    placeholder="Ex: WEG, Grundfos"
                  />
                </div>
                <div>
                  <MobileInput
                    label="Modelo"
                    value={formData.modelo}
                    onChange={(e) => setFormData(prev => ({ ...prev, modelo: e.target.value }))}
                    placeholder="Ex: W22-132M"
                  />
                </div>
                <div>
                  <MobileInput
                    label="Número de Série"
                    value={formData.numeroSerie}
                    onChange={(e) => setFormData(prev => ({ ...prev, numeroSerie: e.target.value }))}
                    placeholder="Ex: 123456789"
                  />
                </div>
              </div>
              
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Observações
                </label>
                <textarea
                  value={formData.observacoes}
                  onChange={(e) => setFormData(prev => ({ ...prev, observacoes: e.target.value }))}
                  placeholder="Observações sobre o equipamento..."
                  className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  rows={3}
                />
              </div>
              <div className="flex gap-2">
                <ResponsiveButton type="submit" disabled={isLoading}>
                  {isLoading ? 'Salvando...' : 'Salvar'}
                </ResponsiveButton>
                <ResponsiveButton type="button" variant="secondary" onClick={resetForm}>
                  Cancelar
                </ResponsiveButton>
              </div>
            </form>
          </CardContent>
        </Card>
      )}

      {/* Lista de equipamentos */}
      <div className="grid gap-4">
        {isLoading && !showForm ? (
          <div className="text-center py-8">
            <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            <p className="text-gray-600 mt-2">Carregando equipamentos...</p>
          </div>
        ) : filteredEquipments.length === 0 ? (
          <Card>
            <CardContent className="text-center py-8">
              <Settings className="w-12 h-12 text-gray-400 mx-auto mb-4" />
              <p className="text-gray-600">Nenhum equipamento encontrado</p>
            </CardContent>
          </Card>
        ) : (
          filteredEquipments.map((equipment) => (
            <Card key={equipment.id} className="hover:shadow-md transition-shadow">
              <CardContent className="p-4">
                <div className="flex justify-between items-start">
                  <div className="flex-1">
                    <div className="flex items-center gap-3 mb-2">
                      <h3 className="text-lg font-semibold text-gray-900">
                        {equipment.nome}
                      </h3>
                      <Badge variant={equipment.status === 'ATIVO' ? 'default' : 'secondary'}>
                        {equipment.status === 'ATIVO' ? 'Ativo' : equipment.status === 'INATIVO' ? 'Inativo' : 'Manutenção'}
                      </Badge>
                    </div>
                    
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                      <div className="flex items-center gap-2">
                        <Tag className="w-4 h-4" />
                        <span className="font-mono font-medium">{equipment.numeracao}</span>
                      </div>
                      <div className="flex items-center gap-2">
                        <MapPin className="w-4 h-4" />
                        <span>{getAreaName(equipment.areaId)}</span>
                      </div>
                      <div className="flex items-center gap-2">
                        <Hash className="w-4 h-4" />
                        <span>Prioridade: {equipment.numeracao}</span>
                      </div>
                    </div>
                    
                    {equipment.observacoes && (
                      <p className="text-gray-600 mt-2 text-sm">{equipment.observacoes}</p>
                    )}
                  </div>
                  
                  <div className="flex gap-2 ml-4">
                    <ResponsiveButton
                      size="small"
                      variant="secondary"
                      onClick={() => handleEdit(equipment)}
                    >
                      <Edit className="w-4 h-4" />
                    </ResponsiveButton>
                    <ResponsiveButton
                      size="small"
                      variant="danger"
                      onClick={() => handleDelete(equipment.id)}
                    >
                      <Trash2 className="w-4 h-4" />
                    </ResponsiveButton>
                  </div>
                </div>
              </CardContent>
            </Card>
          ))
        )}
      </div>
    </div>
  );
};

export default Equipments;