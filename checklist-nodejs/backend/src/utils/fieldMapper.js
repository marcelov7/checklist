/**
 * Utilitários para mapeamento de campos entre inglês e português
 * Garante compatibilidade entre o schema do banco (inglês) e o frontend (português)
 */

/**
 * Mapeia campos de área do inglês (banco) para português (frontend)
 * @param {Object} area - Objeto área com campos em inglês
 * @returns {Object} - Objeto área com campos em português
 */
export const mapAreaToPortuguese = (area) => {
  if (!area) return null;
  
  const mapped = {
    id: area.id,
    nome: area.name,
    descricao: area.description,
    ativo: true, // Por padrão, áreas são ativas (não temos campo ativo no schema atual)
    createdAt: area.createdAt,
    updatedAt: area.updatedAt
  };

  // Preserva campos adicionais como _count
  if (area._count) {
    mapped._count = area._count;
  }

  return mapped;
};

/**
 * Mapeia array de áreas do inglês para português
 * @param {Array} areas - Array de áreas com campos em inglês
 * @returns {Array} - Array de áreas com campos em português
 */
export const mapAreasToPortuguese = (areas) => {
  if (!Array.isArray(areas)) return [];
  return areas.map(mapAreaToPortuguese);
};

/**
 * Mapeia campos de área do português (frontend) para inglês (banco)
 * @param {Object} areaData - Dados da área em português
 * @returns {Object} - Dados da área em inglês para o banco
 */
export const mapAreaToEnglish = (areaData) => {
  if (!areaData) return null;
  
  const mapped = {};
  
  if (areaData.nome !== undefined) {
    mapped.name = areaData.nome;
  }
  
  if (areaData.descricao !== undefined) {
    mapped.description = areaData.descricao;
  }
  
  // Note: não mapeamos 'ativo' pois não existe no schema atual
  // Se precisar adicionar este campo, será necessário uma migration
  
  return mapped;
};

/**
 * Mapeia campos de equipamento do inglês para português
 * @param {Object} equipment - Objeto equipamento com campos em inglês
 * @returns {Object} - Objeto equipamento com campos em português
 */
export const mapEquipmentToPortuguese = (equipment) => {
  if (!equipment) return null;
  
  const mapped = {
    id: equipment.id,
    numeracao: equipment.numeracao,
    nome: equipment.nome,
    tipo: equipment.tipo,
    fabricante: equipment.fabricante,
    modelo: equipment.modelo,
    numeroSerie: equipment.numeroSerie,
    status: equipment.status,
    prioridade: equipment.prioridade,
    observacoes: equipment.observacoes,
    areaId: equipment.areaId,
    createdAt: equipment.createdAt,
    updatedAt: equipment.updatedAt
  };

  // Mapeia área relacionada se existir
  if (equipment.area) {
    mapped.area = {
      id: equipment.area.id,
      nome: equipment.area.name // Mapeia name para nome
    };
  }

  return mapped;
};

/**
 * Mapeia array de equipamentos do inglês para português
 * @param {Array} equipments - Array de equipamentos
 * @returns {Array} - Array de equipamentos com campos mapeados
 */
export const mapEquipmentsToPortuguese = (equipments) => {
  if (!Array.isArray(equipments)) return [];
  return equipments.map(mapEquipmentToPortuguese);
};