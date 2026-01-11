import prisma from '../database/prisma.js';
import { hashPassword, comparePassword, generateToken } from '../utils/auth.js';
import { isValidEmail, validatePassword, isValidName, sanitizeString } from '../utils/validation.js';
import { 
  successResponse, 
  errorResponse, 
  validationErrorResponse, 
  unauthorizedResponse,
  conflictResponse 
} from '../utils/response.js';

/**
 * Controller de Autenticação
 * Gerencia registro, login e operações relacionadas ao usuário
 */

/**
 * Registra um novo usuário
 * POST /api/auth/register
 */
export const register = async (req, res) => {
  try {
    const { name, email, username, password } = req.body;

    // Validações básicas
    const errors = [];

    if (!isValidName(name)) {
      errors.push('Nome deve ter entre 2 e 100 caracteres');
    }

    if (!isValidEmail(email)) {
      errors.push('Email inválido');
    }

    // Validação do username (opcional)
    if (username && (username.length < 3 || username.length > 30)) {
      errors.push('Username deve ter entre 3 e 30 caracteres');
    }

    // Verifica se username contém apenas caracteres válidos (letras, números, underscore, hífen)
    if (username && !/^[a-zA-Z0-9_-]+$/.test(username)) {
      errors.push('Username deve conter apenas letras, números, underscore ou hífen');
    }

    const passwordValidation = validatePassword(password);
    if (!passwordValidation.isValid) {
      errors.push(...passwordValidation.errors);
    }

    if (errors.length > 0) {
      return validationErrorResponse(res, errors);
    }

    // Sanitiza os dados
    const sanitizedName = sanitizeString(name);
    const sanitizedEmail = email.toLowerCase().trim();
    const sanitizedUsername = username ? username.toLowerCase().trim() : null;

    // Verifica se o email já existe
    const existingUser = await prisma.user.findUnique({
      where: { email: sanitizedEmail }
    });

    if (existingUser) {
      return conflictResponse(res, 'Email já está em uso');
    }

    // Verifica se o username já existe (se fornecido)
    if (sanitizedUsername) {
      const existingUsername = await prisma.user.findFirst({
        where: { username: sanitizedUsername }
      });

      if (existingUsername) {
        return conflictResponse(res, 'Username já está em uso');
      }
    }

    // Hash da senha
    const hashedPassword = await hashPassword(password);

    // Cria o usuário
    const user = await prisma.user.create({
      data: {
        name: sanitizedName,
        email: sanitizedEmail,
        username: sanitizedUsername,
        password: hashedPassword,
        role: 'USER' // Role padrão
      },
      select: {
        id: true,
        name: true,
        email: true,
        username: true,
        role: true,
        createdAt: true
      }
    });

    // Gera token JWT
    const token = generateToken({ 
      id: user.id, 
      email: user.email, 
      role: user.role 
    });

    // Mapeia name para nome para compatibilidade com frontend
    const { name: userName, ...userWithoutName } = user;

    successResponse(res, {
      user: {
        ...userWithoutName,
        nome: userName
      },
      token
    }, 'Usuário registrado com sucesso', 201);

  } catch (error) {
    console.error('❌ Erro no registro:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Autentica um usuário
 * POST /api/auth/login
 * Aceita login com email ou username
 */
export const login = async (req, res) => {
  try {
    const { email, username, password } = req.body;

    // Validações básicas - aceita email OU username
    const identifier = email || username;
    if (!identifier || !password) {
      return validationErrorResponse(res, ['Email/username e senha são obrigatórios']);
    }

    const sanitizedIdentifier = identifier.toLowerCase().trim();

    // Determina se é email ou username
    const isEmail = isValidEmail(sanitizedIdentifier);
    
    // Busca o usuário por email ou username
    const user = await prisma.user.findFirst({
      where: isEmail 
        ? { email: sanitizedIdentifier }
        : { username: sanitizedIdentifier }
    });

    if (!user) {
      return unauthorizedResponse(res, 'Credenciais inválidas');
    }

    // Verifica a senha
    const isPasswordValid = await comparePassword(password, user.password);

    if (!isPasswordValid) {
      return unauthorizedResponse(res, 'Credenciais inválidas');
    }

    // Gera token JWT
    const token = generateToken({ 
      id: user.id, 
      email: user.email, 
      role: user.role 
    });

    // Remove a senha da resposta e mapeia name para nome
    const { password: _, name: userName, ...userWithoutPassword } = user;

    successResponse(res, {
      user: {
        ...userWithoutPassword,
        nome: userName // Mapeia name para nome para compatibilidade com frontend
      },
      token
    }, 'Login realizado com sucesso');

  } catch (error) {
    console.error('❌ Erro no login:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Retorna o perfil do usuário autenticado
 * GET /api/auth/profile
 */
export const getProfile = async (req, res) => {
  try {
    // O usuário já está disponível através do middleware de autenticação
    // Mapeia name para nome para compatibilidade com frontend
    const { name: userName, ...userWithoutName } = req.user;
    
    successResponse(res, {
      ...userWithoutName,
      nome: userName
    }, 'Perfil recuperado com sucesso');
  } catch (error) {
    console.error('❌ Erro ao buscar perfil:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Atualiza o perfil do usuário autenticado
 * PUT /api/auth/profile
 */
export const updateProfile = async (req, res) => {
  try {
    const { name } = req.body;
    const userId = req.user.id;

    // Validações
    const errors = [];

    if (name && !isValidName(name)) {
      errors.push('Nome deve ter entre 2 e 100 caracteres');
    }

    if (errors.length > 0) {
      return validationErrorResponse(res, errors);
    }

    // Prepara os dados para atualização
    const updateData = {};
    if (name) {
      updateData.name = sanitizeString(name);
    }

    // Atualiza o usuário
    const updatedUser = await prisma.user.update({
      where: { id: userId },
      data: updateData,
      select: {
        id: true,
        name: true,
        email: true,
        role: true,
        createdAt: true,
        updatedAt: true
      }
    });

    successResponse(res, updatedUser, 'Perfil atualizado com sucesso');

  } catch (error) {
    console.error('❌ Erro ao atualizar perfil:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Altera a senha do usuário autenticado
 * PUT /api/auth/change-password
 */
export const changePassword = async (req, res) => {
  try {
    const { currentPassword, newPassword } = req.body;
    const userId = req.user.id;

    // Validações básicas
    if (!currentPassword || !newPassword) {
      return validationErrorResponse(res, ['Senha atual e nova senha são obrigatórias']);
    }

    const passwordValidation = validatePassword(newPassword);
    if (!passwordValidation.isValid) {
      return validationErrorResponse(res, passwordValidation.errors);
    }

    // Busca o usuário com a senha
    const user = await prisma.user.findUnique({
      where: { id: userId }
    });

    // Verifica a senha atual
    const isCurrentPasswordValid = await comparePassword(currentPassword, user.password);

    if (!isCurrentPasswordValid) {
      return unauthorizedResponse(res, 'Senha atual incorreta');
    }

    // Hash da nova senha
    const hashedNewPassword = await hashPassword(newPassword);

    // Atualiza a senha
    await prisma.user.update({
      where: { id: userId },
      data: { password: hashedNewPassword }
    });

    successResponse(res, null, 'Senha alterada com sucesso');

  } catch (error) {
    console.error('❌ Erro ao alterar senha:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};