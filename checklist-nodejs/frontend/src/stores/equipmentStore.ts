import { create } from 'zustand';

export interface Equipment {
  id: string;
  numeracao: string; // TAG única do equipamento
  nome: string;
  tipo?: string;
  fabricante?: string;
  modelo?: string;
  numeroSerie?: string;
  status: 'ATIVO' | 'INATIVO' | 'MANUTENCAO' | 'PARADO' | 'EM_TESTE';
  prioridade: number; // Para ordenação de prioridade no checklist
  observacoes?: string;
  areaId?: string;
  area?: {
    id: string;
    nome: string;
  };
  createdAt: string;
  updatedAt: string;
  _count?: {
    checklists: number;
  };
}

interface EquipmentState {
  equipments: Equipment[];
  selectedEquipment: Equipment | null;
  isLoading: boolean;
  error: string | null;
}

interface EquipmentActions {
  setEquipments: (equipments: Equipment[]) => void;
  addEquipment: (equipment: Equipment) => void;
  updateEquipment: (id: string, equipment: Partial<Equipment>) => void;
  removeEquipment: (id: string) => void;
  setSelectedEquipment: (equipment: Equipment | null) => void;
  setLoading: (loading: boolean) => void;
  setError: (error: string | null) => void;
  clearError: () => void;
  getEquipmentById: (id: string) => Equipment | undefined;
  getEquipmentsByArea: (areaId: string) => Equipment[];
  getEquipmentsSortedByPriority: () => Equipment[];
}

type EquipmentStore = EquipmentState & EquipmentActions;

export const useEquipmentStore = create<EquipmentStore>((set, get) => ({
  // Estado inicial
  equipments: [],
  selectedEquipment: null,
  isLoading: false,
  error: null,

  // Ações
  setEquipments: (equipments: Equipment[]) => {
    set({ equipments });
  },

  addEquipment: (equipment: Equipment) => {
    set((state) => ({
      equipments: [...state.equipments, equipment],
    }));
  },

  updateEquipment: (id: string, updatedEquipment: Partial<Equipment>) => {
    set((state) => ({
      equipments: state.equipments.map((equipment) =>
        equipment.id === id ? { ...equipment, ...updatedEquipment } : equipment
      ),
    }));
  },

  removeEquipment: (id: string) => {
    set((state) => ({
      equipments: state.equipments.filter((equipment) => equipment.id !== id),
    }));
  },

  setSelectedEquipment: (equipment: Equipment | null) => {
    set({ selectedEquipment: equipment });
  },

  setLoading: (loading: boolean) => {
    set({ isLoading: loading });
  },

  setError: (error: string | null) => {
    set({ error });
  },

  clearError: () => {
    set({ error: null });
  },

  getEquipmentById: (id: string) => {
    const { equipments } = get();
    return equipments.find((equipment) => equipment.id === id);
  },

  getEquipmentsByArea: (areaId: string) => {
    const { equipments } = get();
    return equipments.filter((equipment) => equipment.areaId === areaId);
  },

  getEquipmentsSortedByPriority: () => {
    const { equipments } = get();
    return [...equipments].sort((a, b) => a.numeracao - b.numeracao);
  },
}));