import { create } from 'zustand';

export interface Teste {
  id: string;
  name: string;
  status: 'PENDENTE' | 'EM_ANDAMENTO' | 'CONCLUIDO' | 'CANCELADO';
  progress: number; // 0-100
  createdAt: string;
  updatedAt: string;
  paradaId: string;
  parada?: {
    id: string;
    nome: string;
    status: string;
    area?: {
      id: string;
      nome: string;
    };
    user?: {
      id: string;
      nome: string;
      email: string;
    };
  };
}

interface TesteState {
  testes: Teste[];
  selectedTeste: Teste | null;
  isLoading: boolean;
  error: string | null;
}

interface TesteActions {
  setTestes: (testes: Teste[]) => void;
  addTeste: (teste: Teste) => void;
  updateTeste: (id: string, teste: Partial<Teste>) => void;
  removeTeste: (id: string) => void;
  setSelectedTeste: (teste: Teste | null) => void;
  setLoading: (loading: boolean) => void;
  setError: (error: string | null) => void;
  clearError: () => void;
  getTesteById: (id: string) => Teste | undefined;
  getTestesByParada: (paradaId: string) => Teste[];
  getTestesByStatus: (status: Teste['status']) => Teste[];
}

type TesteStore = TesteState & TesteActions;

export const useTesteStore = create<TesteStore>((set, get) => ({
  // Estado inicial
  testes: [],
  selectedTeste: null,
  isLoading: false,
  error: null,

  // Ações
  setTestes: (testes: Teste[]) => {
    set({ testes, error: null });
  },

  addTeste: (teste: Teste) => {
    set((state) => ({
      testes: [...state.testes, teste],
      error: null
    }));
  },

  updateTeste: (id: string, testeUpdate: Partial<Teste>) => {
    set((state) => ({
      testes: state.testes.map(teste =>
        teste.id === id ? { ...teste, ...testeUpdate } : teste
      ),
      error: null
    }));
  },

  removeTeste: (id: string) => {
    set((state) => ({
      testes: state.testes.filter(teste => teste.id !== id),
      selectedTeste: state.selectedTeste?.id === id ? null : state.selectedTeste,
      error: null
    }));
  },

  setSelectedTeste: (teste: Teste | null) => {
    set({ selectedTeste: teste });
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

  getTesteById: (id: string) => {
    return get().testes.find(teste => teste.id === id);
  },

  getTestesByParada: (paradaId: string) => {
    return get().testes.filter(teste => teste.paradaId === paradaId);
  },

  getTestesByStatus: (status: Teste['status']) => {
    return get().testes.filter(teste => teste.status === status);
  },
}));