import axios, { AxiosInstance, InternalAxiosRequestConfig, AxiosResponse } from 'axios';
import { useAuthStore } from '../stores/authStore';

// Configuração base da API
const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:3001/api';

// Criar instância do axios
export const api: AxiosInstance = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Interceptor para adicionar token de autenticação
api.interceptors.request.use(
  (config: InternalAxiosRequestConfig) => {
    const token = useAuthStore.getState().token;
    
    if (token && config.headers) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Interceptor para tratar respostas e erros
api.interceptors.response.use(
  (response: AxiosResponse) => {
    return response;
  },
  (error) => {
    // Se o token expirou ou é inválido, fazer logout
    if (error.response?.status === 401) {
      const { logout } = useAuthStore.getState();
      logout();
      
      // Redirecionar para login se não estiver na página de login
      if (window.location.pathname !== '/login') {
        window.location.href = '/login';
      }
    }
    
    return Promise.reject(error);
  }
);

// Tipos para respostas da API
export interface ApiResponse<T = any> {
  data: T;
  message?: string;
  success: boolean;
}

export interface ApiError {
  message: string;
  errors?: Record<string, string[]>;
  status?: number;
}

// Função helper para tratar erros da API
export const handleApiError = (error: any): ApiError => {
  if (error.response) {
    // Erro de resposta do servidor
    return {
      message: error.response.data?.message || 'Erro no servidor',
      errors: error.response.data?.errors,
      status: error.response.status,
    };
  } else if (error.request) {
    // Erro de rede
    return {
      message: 'Erro de conexão. Verifique sua internet.',
    };
  } else {
    // Erro desconhecido
    return {
      message: error.message || 'Erro desconhecido',
    };
  }
};

export default api;