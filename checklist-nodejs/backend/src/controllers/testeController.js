import prisma from '../database/prisma.js';
import { isValidName, sanitizeString, isValidId, isValidStatus, isValidProgress } from '../utils/validation.js';
import { 
  successResponse, 
  errorResponse, 
  validationErrorResponse, 
  notFoundResponse,
  paginatedResponse 
} from '../utils/response.js';

/**
 * Controller de Testes
 * Gerencia operações CRUD de testes
 */

const VALID_TESTE_STATUSES = ['PENDENTE', 'EM_ANDAMENTO', 'CONCLUIDO', 'CANCELADO'];

/**
 * Lista todos os testes com paginação e filtros
 * GET /api/testes
 */
export const getTestes = async (req, res) => {
  try {
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 10;
    const search = req.query.search || '';
    const status = req.query.status || '';
    const paradaId = req.query.paradaId || '';

    const skip = (page - 1) * limit;

    // Constrói o filtro de busca
    const where = {};
    
    if (search) {
      where.name = { contains: search, mode: 'insensitive' };
    }
    
    if (status && VALID_TESTE_STATUSES.includes(status)) {
      where.status = status;
    }
    
    if (paradaId && isValidId(paradaId)) {
      where.paradaId = paradaId;
    }

    // Busca os testes
    const [testes, total] = await Promise.all([
      prisma.teste.findMany({
        where,
        include: {
          parada: {
            select: {
              id: true,
              name: true,
              status: true,
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
              }
            }
          }
        },
        skip,
        take: limit,
        orderBy: { createdAt: 'desc' }
      }),
      prisma.teste.count({ where })
    ]);

    paginatedResponse(res, testes, { page, limit, total });

  } catch (error) {
    console.error('❌ Erro ao buscar testes:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Lista testes de uma parada específica
 * GET /api/paradas/:paradaId/testes
 */
export const getTestesByParada = async (req, res) => {
  try {
    const { paradaId } = req.params;
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 10;
    const status = req.query.status || '';

    if (!isValidId(paradaId)) {
      return validationErrorResponse(res, ['ID de parada inválido']);
    }

    const skip = (page - 1) * limit;

    // Verifica se a parada existe
    const parada = await prisma.parada.findUnique({
      where: { id: paradaId },
      select: { id: true, name: true }
    });

    if (!parada) {
      return notFoundResponse(res, 'Parada não encontrada');
    }

    // Constrói o filtro
    const where = { paradaId };
    
    if (status && VALID_TESTE_STATUSES.includes(status)) {
      where.status = status;
    }

    // Busca os testes
    const [testes, total] = await Promise.all([
      prisma.teste.findMany({
        where,
        skip,
        take: limit,
        orderBy: { createdAt: 'desc' }
      }),
      prisma.teste.count({ where })
    ]);

    paginatedResponse(res, { parada, testes }, { page, limit, total });

  } catch (error) {
    console.error('❌ Erro ao buscar testes da parada:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Busca um teste por ID
 * GET /api/testes/:id
 */
export const getTesteById = async (req, res) => {
  try {
    const { id } = req.params;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de teste inválido']);
    }

    const teste = await prisma.teste.findUnique({
      where: { id },
      include: {
        parada: {
          select: {
            id: true,
            name: true,
            status: true,
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
            }
          }
        }
      }
    });

    if (!teste) {
      return notFoundResponse(res, 'Teste não encontrado');
    }

    successResponse(res, teste, 'Teste encontrado');

  } catch (error) {
    console.error('❌ Erro ao buscar teste:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Cria um novo teste
 * POST /api/testes
 */
export const createTeste = async (req, res) => {
  try {
    const { name, paradaId, progress = 0 } = req.body;

    // Validações
    const errors = [];

    if (!isValidName(name)) {
      errors.push('Nome deve ter entre 2 e 100 caracteres');
    }

    if (!paradaId || !isValidId(paradaId)) {
      errors.push('ID de parada inválido');
    }

    if (!isValidProgress(progress)) {
      errors.push('Progresso deve ser um número entre 0 e 100');
    }

    if (errors.length > 0) {
      return validationErrorResponse(res, errors);
    }

    // Verifica se a parada existe
    const parada = await prisma.parada.findUnique({
      where: { id: paradaId }
    });

    if (!parada) {
      return notFoundResponse(res, 'Parada não encontrada');
    }

    // Sanitiza os dados
    const sanitizedName = sanitizeString(name);

    // Determina o status baseado no progresso
    let status = 'PENDENTE';
    if (progress > 0 && progress < 100) {
      status = 'EM_ANDAMENTO';
    } else if (progress === 100) {
      status = 'CONCLUIDO';
    }

    // Cria o teste
    const teste = await prisma.teste.create({
      data: {
        name: sanitizedName,
        paradaId,
        progress,
        status
      },
      include: {
        parada: {
          select: {
            id: true,
            name: true,
            status: true,
            area: {
              select: {
                id: true,
                name: true
              }
            }
          }
        }
      }
    });

    successResponse(res, teste, 'Teste criado com sucesso', 201);

  } catch (error) {
    console.error('❌ Erro ao criar teste:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Atualiza um teste
 * PUT /api/testes/:id
 */
export const updateTeste = async (req, res) => {
  try {
    const { id } = req.params;
    const { name, status, progress } = req.body;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de teste inválido']);
    }

    // Validações
    const errors = [];

    if (name && !isValidName(name)) {
      errors.push('Nome deve ter entre 2 e 100 caracteres');
    }

    if (status && !isValidStatus(status, VALID_TESTE_STATUSES)) {
      errors.push('Status deve ser PENDENTE, EM_ANDAMENTO, CONCLUIDO ou CANCELADO');
    }

    if (progress !== undefined && !isValidProgress(progress)) {
      errors.push('Progresso deve ser um número entre 0 e 100');
    }

    if (errors.length > 0) {
      return validationErrorResponse(res, errors);
    }

    // Verifica se o teste existe
    const existingTeste = await prisma.teste.findUnique({
      where: { id }
    });

    if (!existingTeste) {
      return notFoundResponse(res, 'Teste não encontrado');
    }

    // Prepara os dados para atualização
    const updateData = {};
    
    if (name) {
      updateData.name = sanitizeString(name);
    }
    
    if (status) {
      updateData.status = status;
    }
    
    if (progress !== undefined) {
      updateData.progress = progress;
      
      // Atualiza o status automaticamente baseado no progresso se não foi fornecido explicitamente
      if (!status) {
        if (progress === 0) {
          updateData.status = 'PENDENTE';
        } else if (progress > 0 && progress < 100) {
          updateData.status = 'EM_ANDAMENTO';
        } else if (progress === 100) {
          updateData.status = 'CONCLUIDO';
        }
      }
    }

    // Atualiza o teste
    const updatedTeste = await prisma.teste.update({
      where: { id },
      data: updateData,
      include: {
        parada: {
          select: {
            id: true,
            name: true,
            status: true,
            area: {
              select: {
                id: true,
                name: true
              }
            }
          }
        }
      }
    });

    successResponse(res, updatedTeste, 'Teste atualizado com sucesso');

  } catch (error) {
    console.error('❌ Erro ao atualizar teste:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Remove um teste
 * DELETE /api/testes/:id
 */
export const deleteTeste = async (req, res) => {
  try {
    const { id } = req.params;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de teste inválido']);
    }

    // Verifica se o teste existe
    const existingTeste = await prisma.teste.findUnique({
      where: { id }
    });

    if (!existingTeste) {
      return notFoundResponse(res, 'Teste não encontrado');
    }

    // Remove o teste
    await prisma.teste.delete({
      where: { id }
    });

    successResponse(res, null, 'Teste removido com sucesso');

  } catch (error) {
    console.error('❌ Erro ao remover teste:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Atualiza apenas o progresso de um teste
 * PATCH /api/testes/:id/progress
 */
export const updateTesteProgress = async (req, res) => {
  try {
    const { id } = req.params;
    const { progress } = req.body;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de teste inválido']);
    }

    if (!isValidProgress(progress)) {
      return validationErrorResponse(res, ['Progresso deve ser um número entre 0 e 100']);
    }

    // Verifica se o teste existe
    const existingTeste = await prisma.teste.findUnique({
      where: { id }
    });

    if (!existingTeste) {
      return notFoundResponse(res, 'Teste não encontrado');
    }

    // Determina o status baseado no progresso
    let status = 'PENDENTE';
    if (progress > 0 && progress < 100) {
      status = 'EM_ANDAMENTO';
    } else if (progress === 100) {
      status = 'CONCLUIDO';
    }

    // Atualiza o progresso e status
    const updatedTeste = await prisma.teste.update({
      where: { id },
      data: { 
        progress,
        status
      },
      include: {
        parada: {
          select: {
            id: true,
            name: true,
            area: {
              select: {
                id: true,
                name: true
              }
            }
          }
        }
      }
    });

    successResponse(res, updatedTeste, 'Progresso do teste atualizado com sucesso');

  } catch (error) {
    console.error('❌ Erro ao atualizar progresso do teste:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};