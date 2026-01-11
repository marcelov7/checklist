import { api, ApiResponse, handleApiError } from './api';
import { User } from '../stores/authStore';

// Tipos para autenticação
export interface LoginCredentials {
  email?: string;
  username?: string;
  password: string;
}

export interface RegisterData {
  nome: string;
  email: string;
  password: string;
  confirmPassword: string;
}

export interface LoginResponse {
  user: User;
  token: string;
}

export interface ChangePasswordData {
  currentPassword: string;
  newPassword: string;
  confirmPassword: string;
}

export interface UpdateProfileData {
  nome: string;
  email: string;
}

class AuthService {
  // Login do usuário
  async login(credentials: LoginCredentials): Promise<LoginResponse> {
    try {
      const response = await api.post<ApiResponse<LoginResponse>>('/auth/login', credentials);
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Registro de novo usuário
  async register(userData: RegisterData): Promise<LoginResponse> {
    try {
      const response = await api.post<ApiResponse<LoginResponse>>('/auth/register', userData);
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Logout do usuário
  async logout(): Promise<void> {
    try {
      await api.post('/auth/logout');
    } catch (error) {
      // Mesmo se der erro no servidor, limpar dados locais
      console.warn('Erro ao fazer logout no servidor:', error);
    }
  }

  // Obter perfil do usuário atual
  async getProfile(): Promise<User> {
    try {
      const response = await api.get<ApiResponse<User>>('/auth/profile');
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Atualizar perfil do usuário
  async updateProfile(profileData: UpdateProfileData): Promise<User> {
    try {
      const response = await api.put<ApiResponse<User>>('/auth/profile', profileData);
      return response.data.data;
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Alterar senha do usuário
  async changePassword(passwordData: ChangePasswordData): Promise<void> {
    try {
      await api.put('/auth/change-password', passwordData);
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Verificar se o token ainda é válido
  async verifyToken(): Promise<boolean> {
    try {
      await api.get('/auth/verify');
      return true;
    } catch (error) {
      return false;
    }
  }

  // Solicitar redefinição de senha
  async requestPasswordReset(email: string): Promise<void> {
    try {
      await api.post('/auth/forgot-password', { email });
    } catch (error) {
      throw handleApiError(error);
    }
  }

  // Redefinir senha com token
  async resetPassword(token: string, newPassword: string): Promise<void> {
    try {
      await api.post('/auth/reset-password', { 
        token, 
        password: newPassword 
      });
    } catch (error) {
      throw handleApiError(error);
    }
  }
}

export const authService = new AuthService();