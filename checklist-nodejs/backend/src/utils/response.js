/**
 * Utilitários para padronizar respostas da API
 */

/**
 * Resposta de sucesso padrão
 * @param {Object} res - Objeto response do Express
 * @param {*} data - Dados para retornar
 * @param {string} message - Mensagem de sucesso
 * @param {number} statusCode - Código de status HTTP
 */
export const successResponse = (res, data = null, message = 'Operação realizada com sucesso', statusCode = 200) => {
  return res.status(statusCode).json({
    success: true,
    message,
    data,
    timestamp: new Date().toISOString()
  });
};

/**
 * Resposta de erro padrão
 * @param {Object} res - Objeto response do Express
 * @param {string} message - Mensagem de erro
 * @param {number} statusCode - Código de status HTTP
 * @param {*} errors - Detalhes dos erros
 */
export const errorResponse = (res, message = 'Erro interno do servidor', statusCode = 500, errors = null) => {
  return res.status(statusCode).json({
    success: false,
    message,
    errors,
    timestamp: new Date().toISOString()
  });
};

/**
 * Resposta de validação com erros
 * @param {Object} res - Objeto response do Express
 * @param {Array|Object} errors - Erros de validação
 */
export const validationErrorResponse = (res, errors) => {
  return errorResponse(res, 'Dados inválidos', 400, errors);
};

/**
 * Resposta de não autorizado
 * @param {Object} res - Objeto response do Express
 * @param {string} message - Mensagem personalizada
 */
export const unauthorizedResponse = (res, message = 'Acesso não autorizado') => {
  return errorResponse(res, message, 401);
};

/**
 * Resposta de não encontrado
 * @param {Object} res - Objeto response do Express
 * @param {string} message - Mensagem personalizada
 */
export const notFoundResponse = (res, message = 'Recurso não encontrado') => {
  return errorResponse(res, message, 404);
};

/**
 * Resposta de conflito
 * @param {Object} res - Objeto response do Express
 * @param {string} message - Mensagem personalizada
 */
export const conflictResponse = (res, message = 'Conflito de dados') => {
  return errorResponse(res, message, 409);
};

/**
 * Resposta paginada
 * @param {Object} res - Objeto response do Express
 * @param {Array} data - Dados paginados
 * @param {Object} pagination - Informações de paginação
 */
export const paginatedResponse = (res, data, pagination) => {
  return res.status(200).json({
    success: true,
    data,
    pagination: {
      page: pagination.page,
      limit: pagination.limit,
      total: pagination.total,
      totalPages: Math.ceil(pagination.total / pagination.limit),
      hasNext: pagination.page < Math.ceil(pagination.total / pagination.limit),
      hasPrev: pagination.page > 1
    },
    timestamp: new Date().toISOString()
  });
};