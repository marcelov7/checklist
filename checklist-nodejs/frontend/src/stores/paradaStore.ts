import { create } from 'zustand';

export interface Parada {
  id: string;
  nome: string;
  descricao?: string;
  areaId: string;
  ordem: number;
  ativo: boolean;
  createdAt: string;
  updatedAt: string;
  area?: {
    id: string;
    nome: string;
  };
  _count?: {
    testes: number;
  };
  usuarios?: Array<{
    id: string;
    nome: string;
    email: string;
  }>;
}

interface ParadaState {
  paradas: Parada[];
  selectedParada: Parada | null;
  isLoading: boolean;
  error: string | null;
  filters: {
    areaId?: string;
    ativo?: boolean;
    search?: string;
  };
}

interface ParadaActions {
  setParadas: (paradas: Parada[]) => void;
  addParada: (parada: Parada) => void;
  updateParada: (id: string, parada: Partial<Parada>) => void;
  removeParada: (id: string) => void;
  setSelectedParada: (parada: Parada | null) => void;
  setLoading: (loading: boolean) => void;
  setError: (error: string | null) => void;
  clearError: () => void;
  setFilters: (filters: Partial<ParadaState['filters']>) => void;
  clearFilters: () => void;
  getParadaById: (id: string) => Parada | undefined;
  getParadasByArea: (areaId: string) => Parada[];
  getFilteredParadas: () => Parada[];
}

type ParadaStore = ParadaState & ParadaActions;

export const useParadaStore = create<ParadaStore>((set, get) => ({
  // Estado inicial
  paradas: [],
  selectedParada: null,
  isLoading: false,
  error: null,
  filters: {},

  // Ações
  setParadas: (paradas: Parada[]) => {
    set({ paradas });
  },

  addParada: (parada: Parada) => {
    set((state) => ({
      paradas: [...state.paradas, parada],
    }));
  },

  updateParada: (id: string, updatedParada: Partial<Parada>) => {
    set((state) => ({
      paradas: state.paradas.map((parada) =>
        parada.id === id ? { ...parada, ...updatedParada } : parada
      ),
      selectedParada: state.selectedParada?.id === id 
        ? { ...state.selectedParada, ...updatedParada } 
        : state.selectedParada,
    }));
  },

  removeParada: (id: string) => {
    set((state) => ({
      paradas: state.paradas.filter((parada) => parada.id !== id),
      selectedParada: state.selectedParada?.id === id ? null : state.selectedParada,
    }));
  },

  setSelectedParada: (parada: Parada | null) => {
    set({ selectedParada: parada });
  },

  setLoading: (isLoading: boolean) => {
    set({ isLoading });
  },

  setError: (error: string | null) => {
    set({ error });
  },

  clearError: () => {
    set({ error: null });
  },

  setFilters: (newFilters: Partial<ParadaState['filters']>) => {
    set((state) => ({
      filters: { ...state.filters, ...newFilters },
    }));
  },

  clearFilters: () => {
    set({ filters: {} });
  },

  getParadaById: (id: string) => {
    return get().paradas.find((parada) => parada.id === id);
  },

  getParadasByArea: (areaId: string) => {
    return get().paradas.filter((parada) => parada.areaId === areaId);
  },

  getFilteredParadas: () => {
    const { paradas, filters } = get();
    
    return paradas.filter((parada) => {
      if (filters.areaId && parada.areaId !== filters.areaId) {
        return false;
      }
      
      if (filters.ativo !== undefined && parada.ativo !== filters.ativo) {
        return false;
      }
      
      if (filters.search) {
        const searchLower = filters.search.toLowerCase();
        return (
          parada.nome.toLowerCase().includes(searchLower) ||
          parada.descricao?.toLowerCase().includes(searchLower) ||
          parada.area?.nome.toLowerCase().includes(searchLower)
        );
      }
      
      return true;
    });
  },
}));