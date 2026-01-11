import { create } from 'zustand';

export interface Area {
  id: string;
  nome: string;
  descricao?: string;
  ativo: boolean;
  createdAt: string;
  updatedAt: string;
  _count?: {
    paradas: number;
  };
}

interface AreaState {
  areas: Area[];
  selectedArea: Area | null;
  isLoading: boolean;
  error: string | null;
}

interface AreaActions {
  setAreas: (areas: Area[]) => void;
  addArea: (area: Area) => void;
  updateArea: (id: string, area: Partial<Area>) => void;
  removeArea: (id: string) => void;
  setSelectedArea: (area: Area | null) => void;
  setLoading: (loading: boolean) => void;
  setError: (error: string | null) => void;
  clearError: () => void;
  getAreaById: (id: string) => Area | undefined;
}

type AreaStore = AreaState & AreaActions;

export const useAreaStore = create<AreaStore>((set, get) => ({
  // Estado inicial
  areas: [],
  selectedArea: null,
  isLoading: false,
  error: null,

  // Ações
  setAreas: (areas: Area[]) => {
    set({ areas });
  },

  addArea: (area: Area) => {
    set((state) => ({
      areas: [...state.areas, area],
    }));
  },

  updateArea: (id: string, updatedArea: Partial<Area>) => {
    set((state) => ({
      areas: state.areas.map((area) =>
        area.id === id ? { ...area, ...updatedArea } : area
      ),
      selectedArea: state.selectedArea?.id === id 
        ? { ...state.selectedArea, ...updatedArea } 
        : state.selectedArea,
    }));
  },

  removeArea: (id: string) => {
    set((state) => ({
      areas: state.areas.filter((area) => area.id !== id),
      selectedArea: state.selectedArea?.id === id ? null : state.selectedArea,
    }));
  },

  setSelectedArea: (area: Area | null) => {
    set({ selectedArea: area });
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

  getAreaById: (id: string) => {
    return get().areas.find((area) => area.id === id);
  },
}));