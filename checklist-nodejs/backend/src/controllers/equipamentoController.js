import pkg from '@prisma/client';
const { PrismaClient } = pkg;
import { successResponse, errorResponse } from '../utils/response.js';

const prisma = new PrismaClient();

// Listar todos os equipamentos
export const listarEquipamentos = async (req, res) => {
  try {
    const { areaId, status, prioridade, search } = req.query;
    
    const where = {};
    
    if (areaId) {
      where.areaId = areaId;
    }
    
    if (status) {
      where.status = status;
    }
    
    if (prioridade) {
      where.prioridade = parseInt(prioridade);
    }
    
    if (search) {
      where.OR = [
        { nome: { contains: search, mode: 'insensitive' } },
        { numeracao: { contains: search, mode: 'insensitive' } },
        { tipo: { contains: search, mode: 'insensitive' } },
        { fabricante: { contains: search, mode: 'insensitive' } },
        { modelo: { contains: search, mode: 'insensitive' } }
      ];
    }

    const equipamentos = await prisma.equipamento.findMany({
      where,
      include: {
        area: {
          select: {
            id: true,
            name: true
          }
        }
      },
      orderBy: [
        { prioridade: 'asc' },
        { nome: 'asc' }
      ]
    });

    return successResponse(res, equipamentos, 'Equipamentos listados com sucesso');
  } catch (error) {
    console.error('❌ Erro ao listar equipamentos:', error);
    return errorResponse(res, 'Erro interno do servidor', 500);
  }
};

// Buscar equipamento por ID
export const buscarEquipamentoPorId = async (req, res) => {
  try {
    const { id } = req.params;

    const equipamento = await prisma.equipamento.findUnique({
      where: { id },
      include: {
        area: {
          select: {
            id: true,
            name: true
          }
        }
      }
    });

    if (!equipamento) {
      return errorResponse(res, 'Equipamento não encontrado', 404);
    }

    return successResponse(res, equipamento, 'Equipamento encontrado com sucesso');
  } catch (error) {
    console.error('❌ Erro ao buscar equipamento:', error);
    return errorResponse(res, 'Erro interno do servidor', 500);
  }
};

// Criar novo equipamento
export const criarEquipamento = async (req, res) => {
  try {
    const {
      numeracao,
      nome,
      tipo,
      fabricante,
      modelo,
      numeroSerie,
      status,
      prioridade,
      observacoes,
      areaId
    } = req.body;

    // Verificar se a área existe
    const area = await prisma.area.findUnique({
      where: { id: areaId }
    });

    if (!area) {
      return errorResponse(res, 'Área não encontrada', 404);
    }

    // Verificar se a numeração já existe
    const equipamentoExistente = await prisma.equipamento.findUnique({
      where: { numeracao }
    });

    if (equipamentoExistente) {
      return errorResponse(res, 'Numeração já existe', 400);
    }

    const novoEquipamento = await prisma.equipamento.create({
      data: {
        numeracao,
        nome,
        tipo,
        fabricante,
        modelo,
        numeroSerie,
        status: status || 'ATIVO',
        prioridade: prioridade || 3,
        observacoes,
        areaId
      },
      include: {
        area: {
          select: {
            id: true,
            name: true
          }
        }
      }
    });

    return successResponse(res, novoEquipamento, 'Equipamento criado com sucesso', 201);
  } catch (error) {
    console.error('❌ Erro ao criar equipamento:', error);
    return errorResponse(res, 'Erro interno do servidor', 500);
  }
};

// Atualizar equipamento
export const atualizarEquipamento = async (req, res) => {
  try {
    const { id } = req.params;
    const {
      numeracao,
      nome,
      tipo,
      fabricante,
      modelo,
      numeroSerie,
      status,
      prioridade,
      observacoes,
      areaId
    } = req.body;

    // Verificar se o equipamento existe
    const equipamentoExistente = await prisma.equipamento.findUnique({
      where: { id }
    });

    if (!equipamentoExistente) {
      return errorResponse(res, 'Equipamento não encontrado', 404);
    }

    // Se a numeração foi alterada, verificar se não existe outra com a mesma numeração
    if (numeracao && numeracao !== equipamentoExistente.numeracao) {
      const equipamentoComMesmaNumeracao = await prisma.equipamento.findUnique({
        where: { numeracao }
      });

      if (equipamentoComMesmaNumeracao) {
        return errorResponse(res, 'Numeração já existe', 400);
      }
    }

    // Se a área foi alterada, verificar se existe
    if (areaId && areaId !== equipamentoExistente.areaId) {
      const area = await prisma.area.findUnique({
        where: { id: areaId }
      });

      if (!area) {
        return errorResponse(res, 'Área não encontrada', 404);
      }
    }

    const equipamentoAtualizado = await prisma.equipamento.update({
      where: { id },
      data: {
        ...(numeracao && { numeracao }),
        ...(nome && { nome }),
        ...(tipo && { tipo }),
        ...(fabricante !== undefined && { fabricante }),
        ...(modelo !== undefined && { modelo }),
        ...(numeroSerie !== undefined && { numeroSerie }),
        ...(status && { status }),
        ...(prioridade !== undefined && { prioridade }),
        ...(observacoes !== undefined && { observacoes }),
        ...(areaId && { areaId })
      },
      include: {
        area: {
          select: {
            id: true,
            name: true
          }
        }
      }
    });

    return successResponse(res, equipamentoAtualizado, 'Equipamento atualizado com sucesso');
  } catch (error) {
    console.error('❌ Erro ao atualizar equipamento:', error);
    return errorResponse(res, 'Erro interno do servidor', 500);
  }
};

// Deletar equipamento
export const deletarEquipamento = async (req, res) => {
  try {
    const { id } = req.params;

    // Verificar se o equipamento existe
    const equipamento = await prisma.equipamento.findUnique({
      where: { id }
    });

    if (!equipamento) {
      return errorResponse(res, 'Equipamento não encontrado', 404);
    }

    await prisma.equipamento.delete({
      where: { id }
    });

    return successResponse(res, null, 'Equipamento deletado com sucesso');
  } catch (error) {
    console.error('❌ Erro ao deletar equipamento:', error);
    return errorResponse(res, 'Erro interno do servidor', 500);
  }
};

// Obter próxima numeração disponível
export const obterProximaNumeracao = async (req, res) => {
  try {
    // Buscar o último equipamento criado para gerar a próxima numeração
    const ultimoEquipamento = await prisma.equipamento.findFirst({
      orderBy: {
        createdAt: 'desc'
      }
    });

    let proximaNumeracao = 'EQ001';

    if (ultimoEquipamento) {
      // Extrair o número da numeração (assumindo formato EQxxx)
      const numeroAtual = parseInt(ultimoEquipamento.numeracao.replace('EQ', ''));
      const proximoNumero = numeroAtual + 1;
      proximaNumeracao = `EQ${proximoNumero.toString().padStart(3, '0')}`;
    }

    return successResponse(res, { numeracao: proximaNumeracao }, 'Próxima numeração obtida com sucesso');
  } catch (error) {
    console.error('❌ Erro ao obter próxima numeração:', error);
    return errorResponse(res, 'Erro interno do servidor', 500);
  }
};