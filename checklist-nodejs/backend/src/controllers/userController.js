import prisma from '../database/prisma.js';
import { hashPassword } from '../utils/auth.js';
import { isValidEmail, validatePassword, isValidName, sanitizeString, isValidId } from '../utils/validation.js';
import { 
  successResponse, 
  errorResponse, 
  validationErrorResponse, 
  notFoundResponse,
  conflictResponse,
  paginatedResponse 
} from '../utils/response.js';

/**
 * Controller de Usuários
 * Gerencia operações CRUD de usuários (apenas para administradores)
 */

/**
 * Lista todos os usuários com paginação
 * GET /api/users
 */
export const getUsers = async (req, res) => {
  try {
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 10;
    const search = req.query.search || '';
    const role = req.query.role || '';

    const skip = (page - 1) * limit;

    // Constrói o filtro de busca
    const where = {};
    
    if (search) {
      where.OR = [
        { name: { contains: search, mode: 'insensitive' } },
        { email: { contains: search, mode: 'insensitive' } }
      ];
    }
    
    if (role && ['ADMIN', 'USER'].includes(role)) {
      where.role = role;
    }

    // Busca os usuários
    const [users, total] = await Promise.all([
      prisma.user.findMany({
        where,
        select: {
          id: true,
          name: true,
          email: true,
          role: true,
          createdAt: true,
          updatedAt: true,
          _count: {
            select: {
              paradas: true
            }
          }
        },
        skip,
        take: limit,
        orderBy: { createdAt: 'desc' }
      }),
      prisma.user.count({ where })
    ]);

    paginatedResponse(res, users, { page, limit, total });

  } catch (error) {
    console.error('❌ Erro ao buscar usuários:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Busca um usuário por ID
 * GET /api/users/:id
 */
export const getUserById = async (req, res) => {
  try {
    const { id } = req.params;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de usuário inválido']);
    }

    const user = await prisma.user.findUnique({
      where: { id },
      select: {
        id: true,
        name: true,
        email: true,
        role: true,
        createdAt: true,
        updatedAt: true,
        paradas: {
          select: {
            id: true,
            name: true,
            status: true,
            createdAt: true,
            area: {
              select: {
                id: true,
                name: true
              }
            }
          },
          orderBy: { createdAt: 'desc' },
          take: 5 // Últimas 5 paradas
        },
        _count: {
          select: {
            paradas: true
          }
        }
      }
    });

    if (!user) {
      return notFoundResponse(res, 'Usuário não encontrado');
    }

    successResponse(res, user, 'Usuário encontrado');

  } catch (error) {
    console.error('❌ Erro ao buscar usuário:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Cria um novo usuário
 * POST /api/users
 */
export const createUser = async (req, res) => {
  try {
    const { name, email, password, role = 'USER' } = req.body;

    // Validações
    const errors = [];

    if (!isValidName(name)) {
      errors.push('Nome deve ter entre 2 e 100 caracteres');
    }

    if (!isValidEmail(email)) {
      errors.push('Email inválido');
    }

    const passwordValidation = validatePassword(password);
    if (!passwordValidation.isValid) {
      errors.push(...passwordValidation.errors);
    }

    if (!['ADMIN', 'USER'].includes(role)) {
      errors.push('Role deve ser ADMIN ou USER');
    }

    if (errors.length > 0) {
      return validationErrorResponse(res, errors);
    }

    // Sanitiza os dados
    const sanitizedName = sanitizeString(name);
    const sanitizedEmail = email.toLowerCase().trim();

    // Verifica se o email já existe
    const existingUser = await prisma.user.findUnique({
      where: { email: sanitizedEmail }
    });

    if (existingUser) {
      return conflictResponse(res, 'Email já está em uso');
    }

    // Hash da senha
    const hashedPassword = await hashPassword(password);

    // Cria o usuário
    const user = await prisma.user.create({
      data: {
        name: sanitizedName,
        email: sanitizedEmail,
        password: hashedPassword,
        role
      },
      select: {
        id: true,
        name: true,
        email: true,
        role: true,
        createdAt: true
      }
    });

    successResponse(res, user, 'Usuário criado com sucesso', 201);

  } catch (error) {
    console.error('❌ Erro ao criar usuário:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Atualiza um usuário
 * PUT /api/users/:id
 */
export const updateUser = async (req, res) => {
  try {
    const { id } = req.params;
    const { name, email, role } = req.body;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de usuário inválido']);
    }

    // Validações
    const errors = [];

    if (name && !isValidName(name)) {
      errors.push('Nome deve ter entre 2 e 100 caracteres');
    }

    if (email && !isValidEmail(email)) {
      errors.push('Email inválido');
    }

    if (role && !['ADMIN', 'USER'].includes(role)) {
      errors.push('Role deve ser ADMIN ou USER');
    }

    if (errors.length > 0) {
      return validationErrorResponse(res, errors);
    }

    // Verifica se o usuário existe
    const existingUser = await prisma.user.findUnique({
      where: { id }
    });

    if (!existingUser) {
      return notFoundResponse(res, 'Usuário não encontrado');
    }

    // Prepara os dados para atualização
    const updateData = {};
    
    if (name) {
      updateData.name = sanitizeString(name);
    }
    
    if (email) {
      const sanitizedEmail = email.toLowerCase().trim();
      
      // Verifica se o email já está em uso por outro usuário
      const emailInUse = await prisma.user.findFirst({
        where: { 
          email: sanitizedEmail,
          id: { not: id }
        }
      });

      if (emailInUse) {
        return conflictResponse(res, 'Email já está em uso');
      }
      
      updateData.email = sanitizedEmail;
    }
    
    if (role) {
      updateData.role = role;
    }

    // Atualiza o usuário
    const updatedUser = await prisma.user.update({
      where: { id },
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

    successResponse(res, updatedUser, 'Usuário atualizado com sucesso');

  } catch (error) {
    console.error('❌ Erro ao atualizar usuário:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Remove um usuário
 * DELETE /api/users/:id
 */
export const deleteUser = async (req, res) => {
  try {
    const { id } = req.params;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de usuário inválido']);
    }

    // Verifica se o usuário existe
    const existingUser = await prisma.user.findUnique({
      where: { id },
      include: {
        _count: {
          select: {
            paradas: true
          }
        }
      }
    });

    if (!existingUser) {
      return notFoundResponse(res, 'Usuário não encontrado');
    }

    // Impede que o usuário delete a si mesmo
    if (id === req.user.id) {
      return validationErrorResponse(res, ['Não é possível deletar seu próprio usuário']);
    }

    // Remove o usuário (as paradas associadas terão userId definido como null devido ao onDelete: SetNull)
    await prisma.user.delete({
      where: { id }
    });

    successResponse(res, null, 'Usuário removido com sucesso');

  } catch (error) {
    console.error('❌ Erro ao remover usuário:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};