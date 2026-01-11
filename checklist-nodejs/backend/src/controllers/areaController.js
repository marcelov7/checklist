import prisma from '../database/prisma.js';
import { isValidName, sanitizeString, isValidId } from '../utils/validation.js';
import { 
  successResponse, 
  errorResponse, 
  validationErrorResponse, 
  notFoundResponse,
  conflictResponse,
  paginatedResponse 
} from '../utils/response.js';
import { 
  mapAreaToPortuguese, 
  mapAreasToPortuguese, 
  mapAreaToEnglish 
} from '../utils/fieldMapper.js';

/**
 * Controller de Áreas
 * Gerencia operações CRUD de áreas
 */

/**
 * Lista todas as áreas com paginação
 * GET /api/areas
 */
export const getAreas = async (req, res) => {
  try {
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 10;
    const search = req.query.search || '';

    const skip = (page - 1) * limit;

    // Constrói o filtro de busca
    const where = {};
    
    if (search) {
      where.OR = [
        { name: { contains: search, mode: 'insensitive' } },
        { description: { contains: search, mode: 'insensitive' } }
      ];
    }

    // Busca as áreas
    const [areas, total] = await Promise.all([
      prisma.area.findMany({
        where,
        include: {
          _count: {
            select: {
              paradas: true
            }
          }
        },
        skip,
        take: limit,
        orderBy: { name: 'asc' }
      }),
      prisma.area.count({ where })
    ]);

    // Mapeia os campos para português
    const mappedAreas = mapAreasToPortuguese(areas);
    
    paginatedResponse(res, mappedAreas, { page, limit, total });

  } catch (error) {
    console.error('❌ Erro ao buscar áreas:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Lista todas as áreas (sem paginação) para uso em selects
 * GET /api/areas/all
 */
export const getAllAreas = async (req, res) => {
  try {
    const areas = await prisma.area.findMany({
      select: {
        id: true,
        name: true,
        description: true
      },
      orderBy: { name: 'asc' }
    });

    // Mapeia os campos para português
    const mappedAreas = mapAreasToPortuguese(areas);
    
    successResponse(res, mappedAreas, 'Áreas recuperadas com sucesso');

  } catch (error) {
    console.error('❌ Erro ao buscar todas as áreas:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Busca uma área por ID
 * GET /api/areas/:id
 */
export const getAreaById = async (req, res) => {
  try {
    const { id } = req.params;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de área inválido']);
    }

    const area = await prisma.area.findUnique({
      where: { id },
      include: {
        paradas: {
          select: {
            id: true,
            name: true,
            status: true,
            createdAt: true,
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
          orderBy: { createdAt: 'desc' }
        },
        _count: {
          select: {
            paradas: true
          }
        }
      }
    });

    if (!area) {
      return notFoundResponse(res, 'Área não encontrada');
    }

    // Mapeia os campos para português
    const mappedArea = mapAreaToPortuguese(area);
    
    successResponse(res, mappedArea, 'Área encontrada');

  } catch (error) {
    console.error('❌ Erro ao buscar área:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Cria uma nova área
 * POST /api/areas
 */
export const createArea = async (req, res) => {
  try {
    // Aceita dados tanto em português quanto em inglês para compatibilidade
    const { name, description, nome, descricao } = req.body;
    
    // Prioriza campos em português se fornecidos
    const areaName = nome || name;
    const areaDescription = descricao || description;

    // Validações
    const errors = [];

    if (!isValidName(areaName)) {
      errors.push('Nome deve ter entre 2 e 100 caracteres');
    }

    if (areaDescription && areaDescription.trim().length > 500) {
      errors.push('Descrição deve ter no máximo 500 caracteres');
    }

    if (errors.length > 0) {
      return validationErrorResponse(res, errors);
    }

    // Sanitiza os dados
    const sanitizedName = sanitizeString(areaName);
    const sanitizedDescription = areaDescription ? sanitizeString(areaDescription) : null;

    // Verifica se o nome já existe
    const existingArea = await prisma.area.findUnique({
      where: { name: sanitizedName }
    });

    if (existingArea) {
      return conflictResponse(res, 'Já existe uma área com este nome');
    }

    // Cria a área
    const area = await prisma.area.create({
      data: {
        name: sanitizedName,
        description: sanitizedDescription
      },
      include: {
        _count: {
          select: {
            paradas: true
          }
        }
      }
    });

    // Mapeia os campos para português
    const mappedArea = mapAreaToPortuguese(area);
    
    successResponse(res, mappedArea, 'Área criada com sucesso', 201);

  } catch (error) {
    console.error('❌ Erro ao criar área:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Atualiza uma área
 * PUT /api/areas/:id
 */
export const updateArea = async (req, res) => {
  try {
    const { id } = req.params;
    // Aceita dados tanto em português quanto em inglês para compatibilidade
    const { name, description, nome, descricao } = req.body;
    
    // Prioriza campos em português se fornecidos
    const areaName = nome || name;
    const areaDescription = descricao !== undefined ? descricao : description;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de área inválido']);
    }

    // Validações
    const errors = [];

    if (areaName && !isValidName(areaName)) {
      errors.push('Nome deve ter entre 2 e 100 caracteres');
    }

    if (areaDescription && areaDescription.trim().length > 500) {
      errors.push('Descrição deve ter no máximo 500 caracteres');
    }

    if (errors.length > 0) {
      return validationErrorResponse(res, errors);
    }

    // Verifica se a área existe
    const existingArea = await prisma.area.findUnique({
      where: { id }
    });

    if (!existingArea) {
      return notFoundResponse(res, 'Área não encontrada');
    }

    // Prepara os dados para atualização
    const updateData = {};
    
    if (areaName) {
      const sanitizedName = sanitizeString(areaName);
      
      // Verifica se o nome já está em uso por outra área
      const nameInUse = await prisma.area.findFirst({
        where: { 
          name: sanitizedName,
          id: { not: id }
        }
      });

      if (nameInUse) {
        return conflictResponse(res, 'Já existe uma área com este nome');
      }
      
      updateData.name = sanitizedName;
    }
    
    if (areaDescription !== undefined) {
      updateData.description = areaDescription ? sanitizeString(areaDescription) : null;
    }

    // Atualiza a área
    const updatedArea = await prisma.area.update({
      where: { id },
      data: updateData,
      include: {
        _count: {
          select: {
            paradas: true
          }
        }
      }
    });

    // Mapeia os campos para português
    const mappedArea = mapAreaToPortuguese(updatedArea);
    
    successResponse(res, mappedArea, 'Área atualizada com sucesso');

  } catch (error) {
    console.error('❌ Erro ao atualizar área:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Remove uma área
 * DELETE /api/areas/:id
 */
export const deleteArea = async (req, res) => {
  try {
    const { id } = req.params;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de área inválido']);
    }

    // Verifica se a área existe
    const existingArea = await prisma.area.findUnique({
      where: { id },
      include: {
        _count: {
          select: {
            paradas: true
          }
        }
      }
    });

    if (!existingArea) {
      return notFoundResponse(res, 'Área não encontrada');
    }

    // Verifica se há paradas associadas
    if (existingArea._count.paradas > 0) {
      return validationErrorResponse(res, [
        `Não é possível remover a área. Existem ${existingArea._count.paradas} parada(s) associada(s).`
      ]);
    }

    // Remove a área
    await prisma.area.delete({
      where: { id }
    });

    successResponse(res, null, 'Área removida com sucesso');

  } catch (error) {
    console.error('❌ Erro ao remover área:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};

/**
 * Busca estatísticas de uma área
 * GET /api/areas/:id/stats
 */
export const getAreaStats = async (req, res) => {
  try {
    const { id } = req.params;

    if (!isValidId(id)) {
      return validationErrorResponse(res, ['ID de área inválido']);
    }

    // Verifica se a área existe
    const area = await prisma.area.findUnique({
      where: { id },
      select: { id: true, name: true }
    });

    if (!area) {
      return notFoundResponse(res, 'Área não encontrada');
    }

    // Busca estatísticas
    const stats = await prisma.parada.groupBy({
      by: ['status'],
      where: { areaId: id },
      _count: {
        status: true
      }
    });

    // Formata as estatísticas
    const formattedStats = {
      area,
      totalParadas: stats.reduce((acc, stat) => acc + stat._count.status, 0),
      statusCount: {
        ATIVA: 0,
        CONCLUIDA: 0,
        CANCELADA: 0
      }
    };

    stats.forEach(stat => {
      formattedStats.statusCount[stat.status] = stat._count.status;
    });

    successResponse(res, formattedStats, 'Estatísticas da área recuperadas');

  } catch (error) {
    console.error('❌ Erro ao buscar estatísticas da área:', error);
    errorResponse(res, 'Erro interno do servidor');
  }
};