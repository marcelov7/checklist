import { api, ApiResponse, handleApiError } from './api';
import { Equipment } from '../stores/equipmentStore';

// Tipos para operações de equipamento
export interface CreateEquipmentData {
  nome: string;
  numeracao: string;
  tipo?: string;
  fabricante?: string;
  modelo?: string;
  numeroSerie?: string;
  observacoes?: string;
  areaId?: string;
  prioridade?: number;
  status?: 'ATIVO' | 'INATIVO' | 'MANUTENCAO';
}

export interface UpdateEquipmentData {
  nome?: string;
  numeracao?: string;
  tipo?: string;
  fabricante?: string;
  modelo?: string;
  numeroSerie?: string;
  observacoes?: string;
  areaId?: string;
  prioridade?: number;
  status?: 'ATIVO' | 'INATIVO' | 'MANUTENCAO';
}

export interface EquipmentListResponse {
  equipments: Equipment[];
  total: number;
  page: number;
  limit: number;
}

export interface EquipmentStatsResponse {
  totalEquipments: number;
  equipmentsAtivos: number;
  equipmentsInativos: number;
  totalChecklists: number;
}

class EquipmentService {
  // Listar todos os equipamentos
  async getEquipments(params?: {
    page?: number;
    limit?: number;
    search?: string;
    status?: 'ATIVO' | 'INATIVO' | 'MANUTENCAO';
    areaId?: string;
    sortBy?: 'nome' | 'numeracao' | 'prioridade' | 'createdAt';
    sortOrder?: 'asc' | 'desc';
  }): Promise<EquipmentListResponse> {
    try {
      const response = await api.get<ApiResponse<EquipmentListResponse>>('/equipments', { params });
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Obter equipamento por ID
  async getEquipmentById(id: string): Promise<Equipment> {
    try {
      const response = await api.get<ApiResponse<Equipment>>(`/equipments/${id}`);
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Criar novo equipamento
  async createEquipment(data: CreateEquipmentData): Promise<Equipment> {
    try {
      const response = await api.post<ApiResponse<Equipment>>('/equipments', data);
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Atualizar equipamento
  async updateEquipment(id: string, data: UpdateEquipmentData): Promise<Equipment> {
    try {
      const response = await api.put<ApiResponse<Equipment>>(`/equipments/${id}`, data);
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Deletar equipamento
  async deleteEquipment(id: string): Promise<void> {
    try {
      await api.delete(`/equipments/${id}`);
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Obter equipamentos por área
  async getEquipmentsByArea(areaId: string): Promise<Equipment[]> {
    try {
      const response = await api.get<ApiResponse<Equipment[]>>(`/equipments/area/${areaId}`);
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Obter estatísticas de equipamentos
  async getEquipmentStats(): Promise<EquipmentStatsResponse> {
    try {
      const response = await api.get<ApiResponse<EquipmentStatsResponse>>('/equipments/stats');
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Verificar se TAG já existe
  async checkTagExists(tag: string, excludeId?: string): Promise<boolean> {
    try {
      const params = excludeId ? { excludeId } : {};
      const response = await api.get<ApiResponse<{ exists: boolean }>>(`/equipments/check-tag/${tag}`, { params });
      return response.data.data.exists;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Obter próximo número de numeração disponível
  async getNextNumeracao(): Promise<number> {
    try {
      const response = await api.get<ApiResponse<{ nextNumeracao: number }>>('/equipments/next-numeracao');
      return response.data.data.nextNumeracao;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Reordenar numeração de equipamentos
  async reorderEquipments(equipmentIds: string[]): Promise<void> {
    try {
      await api.post('/equipments/reorder', { equipmentIds });
    } catch (error) {
      throw handleApiError(error);
    }
  }
}

export const equipmentService = new EquipmentService();