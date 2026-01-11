import prisma from '../database/prisma.js';
import { isValidName, sanitizeString, isValidId, isValidStatus } from '../utils/validation.js';
import { 
  successResponse, 
  errorResponse, 
  validationErrorResponse, 
  notFoundResponse,
  paginatedResponse 
} from '../utils/response.js';

/**
 * Controller de Paradas
 * Gerencia operações CRUD de paradas
 */

const VALID_PARADA_STATUSES = ['ATIVA', 'CONCLUIDA', 'CANCELADA'];

/**
 * Lista todas as paradas com paginação e filtros
 * GET /api/paradas
 */
export const getParadas = async (req, res) => {
  try {
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 10;
    const search = req.query.search || '';
    const status = req.query.status || '';
    const areaId = req.query.areaId || '';
    const userId = req.query.userId || '';

    const skip = (page - 1) * limit;

    // Constrói o filtro de busca
    const where = {};
    
    if (search) {
      where.name = { contains: search, mode: 'insensitive' };
    }
    
    if (status && VALID_PARADA_STATUSES.includes(status)) {
      where.status = status;
    }
    
    if (areaId && isValidId(areaId)) {
      where.areaId = areaId;
    }
    
    if (userId && isValidId(userId)) {
      where.userId = userId;
    }

    // Busca as paradas
    const [paradas, total] = await Promise.all([
      prisma.parada.findMany({
        where,
        include: {
          area: {
            select: {
              id: true,
              name: true
            }
          },
          user: {
            select: {
              id: true,
              name: true
            }
          },
          _count: {
            select: {
              testes: true
            }
          }
        },
        skip,
        take: limit,
        orderBy: { createdAt: 'desc' }
      }),
      prisma.parada.count({ where })
    ]);

    paginatedResponse(res, paradas, { page, limit, total });

  } catch (error) {
    console.error('❌ Erro ao buscar paradas:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Busca uma parada por ID
 * GET /api/paradas/:id
 */
export const getParadaById = async (req, res) => {
  try {
    const { id } = req.params;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de parada inválido']);
    }

    const parada = await prisma.parada.findUnique({
      where: { id },
      include: {
        area: {
          select: {
            id: true,
            name: true,
            description: true
          }
        },
        user: {
          select: {
            id: true,
            name: true,
            email: true
          }
        },
        testes: {
          select: {
            id: true,
            name: true,
            status: true,
            progress: true,
            createdAt: true,
            updatedAt: true
          },
          orderBy: { createdAt: 'desc' }
        },
        _count: {
          select: {
            testes: true
          }
        }
      }
    });

    if (!parada) {
      return notFoundResponse(res, 'Parada não encontrada');
    }

    successResponse(res, parada, 'Parada encontrada');

  } catch (error) {
    console.error('❌ Erro ao buscar parada:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Cria uma nova parada
 * POST /api/paradas
 */
export const createParada = async (req, res) => {
  try {
    const { name, areaId, userId } = req.body;

    // Validações
    const errors = [];

    if (!isValidName(name)) {
      errors.push('Nome deve ter entre 2 e 100 caracteres');
    }

    if (!areaId || !isValidId(areaId)) {
      errors.push('ID de área inválido');
    }

    if (userId && !isValidId(userId)) {
      errors.push('ID de usuário inválido');
    }

    if (errors.length > 0) {
      return validationErrorResponse(res, errors);
    }

    // Verifica se a área existe
    const area = await prisma.area.findUnique({
      where: { id: areaId }
    });

    if (!area) {
      return notFoundResponse(res, 'Área não encontrada');
    }

    // Verifica se o usuário existe (se fornecido)
    if (userId) {
      const user = await prisma.user.findUnique({
        where: { id: userId }
      });

      if (!user) {
        return notFoundResponse(res, 'Usuário não encontrado');
      }
    }

    // Sanitiza os dados
    const sanitizedName = sanitizeString(name);

    // Cria a parada
    const parada = await prisma.parada.create({
      data: {
        name: sanitizedName,
        areaId,
        userId: userId || null,
        status: 'ATIVA'
      },
      include: {
        area: {
          select: {
            id: true,
            name: true
          }
        },
        user: {
          select: {
            id: true,
            name: true
          }
        },
        _count: {
          select: {
            testes: true
          }
        }
      }
    });

    successResponse(res, parada, 'Parada criada com sucesso', 201);

  } catch (error) {
    console.error('❌ Erro ao criar parada:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Atualiza uma parada
 * PUT /api/paradas/:id
 */
export const updateParada = async (req, res) => {
  try {
    const { id } = req.params;
    const { name, status, userId } = req.body;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de parada inválido']);
    }

    // Validações
    const errors = [];

    if (name && !isValidName(name)) {
      errors.push('Nome deve ter entre 2 e 100 caracteres');
    }

    if (status && !isValidStatus(status, VALID_PARADA_STATUSES)) {
      errors.push('Status deve ser ATIVA, CONCLUIDA ou CANCELADA');
    }

    if (userId && !isValidId(userId)) {
      errors.push('ID de usuário inválido');
    }

    if (errors.length > 0) {
      return validationErrorResponse(res, errors);
    }

    // Verifica se a parada existe
    const existingParada = await prisma.parada.findUnique({
      where: { id }
    });

    if (!existingParada) {
      return notFoundResponse(res, 'Parada não encontrada');
    }

    // Verifica se o usuário existe (se fornecido)
    if (userId) {
      const user = await prisma.user.findUnique({
        where: { id: userId }
      });

      if (!user) {
        return notFoundResponse(res, 'Usuário não encontrado');
      }
    }

    // Prepara os dados para atualização
    const updateData = {};
    
    if (name) {
      updateData.name = sanitizeString(name);
    }
    
    if (status) {
      updateData.status = status;
    }
    
    if (userId !== undefined) {
      updateData.userId = userId || null;
    }

    // Atualiza a parada
    const updatedParada = await prisma.parada.update({
      where: { id },
      data: updateData,
      include: {
        area: {
          select: {
            id: true,
            name: true
          }
        },
        user: {
          select: {
            id: true,
            name: true
          }
        },
        _count: {
          select: {
            testes: true
          }
        }
      }
    });

    successResponse(res, updatedParada, 'Parada atualizada com sucesso');

  } catch (error) {
    console.error('❌ Erro ao atualizar parada:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Remove uma parada
 * DELETE /api/paradas/:id
 */
export const deleteParada = async (req, res) => {
  try {
    const { id } = req.params;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de parada inválido']);
    }

    // Verifica se a parada existe
    const existingParada = await prisma.parada.findUnique({
      where: { id },
      include: {
        _count: {
          select: {
            testes: true
          }
        }
      }
    });

    if (!existingParada) {
      return notFoundResponse(res, 'Parada não encontrada');
    }

    // Remove a parada (os testes associados serão removidos automaticamente devido ao onDelete: Cascade)
    await prisma.parada.delete({
      where: { id }
    });

    successResponse(res, null, 'Parada removida com sucesso');

  } catch (error) {
    console.error('❌ Erro ao remover parada:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Atribui um usuário a uma parada
 * PUT /api/paradas/:id/assign
 */
export const assignUserToParada = async (req, res) => {
  try {
    const { id } = req.params;
    const { userId } = req.body;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de parada inválido']);
    }

    if (!userId || !isValidId(userId)) {
      return validationErrorResponse(res, ['ID de usuário inválido']);
    }

    // Verifica se a parada existe
    const parada = await prisma.parada.findUnique({
      where: { id }
    });

    if (!parada) {
      return notFoundResponse(res, 'Parada não encontrada');
    }

    // Verifica se o usuário existe
    const user = await prisma.user.findUnique({
      where: { id: userId }
    });

    if (!user) {
      return notFoundResponse(res, 'Usuário não encontrado');
    }

    // Atualiza a parada
    const updatedParada = await prisma.parada.update({
      where: { id },
      data: { userId },
      include: {
        area: {
          select: {
            id: true,
            name: true
          }
        },
        user: {
          select: {
            id: true,
            name: true
          }
        },
        _count: {
          select: {
            testes: true
          }
        }
      }
    });

    successResponse(res, updatedParada, 'Usuário atribuído à parada com sucesso');

  } catch (error) {
    console.error('❌ Erro ao atribuir usuário à parada:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Remove a atribuição de usuário de uma parada
 * PUT /api/paradas/:id/unassign
 */
export const unassignUserFromParada = async (req, res) => {
  try {
    const { id } = req.params;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de parada inválido']);
    }

    // Verifica se a parada existe
    const parada = await prisma.parada.findUnique({
      where: { id }
    });

    if (!parada) {
      return notFoundResponse(res, 'Parada não encontrada');
    }

    // Remove a atribuição
    const updatedParada = await prisma.parada.update({
      where: { id },
      data: { userId: null },
      include: {
        area: {
          select: {
            id: true,
            name: true
          }
        },
        user: {
          select: {
            id: true,
            name: true
          }
        },
        _count: {
          select: {
            testes: true
          }
        }
      }
    });

    successResponse(res, updatedParada, 'Atribuição de usuário removida com sucesso');

  } catch (error) {
    console.error('❌ Erro ao remover atribuição de usuário:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};