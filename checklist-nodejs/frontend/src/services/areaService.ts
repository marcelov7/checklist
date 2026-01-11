import { api, ApiResponse, handleApiError } from './api';
import { Area } from '../stores/areaStore';

// Tipos para operações de área
export interface CreateAreaData {
  nome: string;
  descricao?: string;
  ativo?: boolean;
}

export interface UpdateAreaData {
  nome?: string;
  descricao?: string;
  ativo?: boolean;
}

export interface AreaListResponse {
  areas: Area[];
  total: number;
  page: number;
  limit: number;
}

export interface AreaStatsResponse {
  totalAreas: number;
  areasAtivas: number;
  areasInativas: number;
  totalParadas: number;
}

class AreaService {
  // Listar todas as áreas
  async getAreas(params?: {
    page?: number;
    limit?: number;
    search?: string;
    ativo?: boolean;
  }): Promise<AreaListResponse> {
    try {
      const response = await api.get<ApiResponse<AreaListResponse>>('/areas', { params });
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Obter área por ID
  async getAreaById(id: string): Promise<Area> {
    try {
      const response = await api.get<ApiResponse<Area>>(`/areas/${id}`);
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Criar nova área
  async createArea(areaData: CreateAreaData): Promise<Area> {
    try {
      const response = await api.post<ApiResponse<Area>>('/areas', areaData);
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Atualizar área existente
  async updateArea(id: string, areaData: UpdateAreaData): Promise<Area> {
    try {
      const response = await api.put<ApiResponse<Area>>(`/areas/${id}`, areaData);
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Deletar área
  async deleteArea(id: string): Promise<void> {
    try {
      await api.delete(`/areas/${id}`);
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Obter estatísticas das áreas
  async getAreaStats(): Promise<AreaStatsResponse> {
    try {
      const response = await api.get<ApiResponse<AreaStatsResponse>>('/areas/stats');
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Ativar/Desativar área
  async toggleAreaStatus(id: string): Promise<Area> {
    try {
      const response = await api.patch<ApiResponse<Area>>(`/areas/${id}/toggle-status`);
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Obter todas as áreas (sem paginação)
  async getAllAreas(): Promise<Area[]> {
    try {
      const response = await api.get<ApiResponse<Area[]>>('/areas/all');
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Obter áreas ativas (para seletores)
  async getActiveAreas(): Promise<Area[]> {
    try {
      const response = await api.get<ApiResponse<Area[]>>('/areas/active');
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }
}

export const areaService = new AreaService();