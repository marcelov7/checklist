/**
 * Middleware de tratamento de erros
 * Captura todos os erros da aplicação e retorna uma resposta padronizada
 */
export const errorHandler = (err, req, res, next) => {
  let error = { ...err };
  error.message = err.message;

  // Log do erro para debugging
  console.error('❌ Erro capturado:', err);

  // Erro de validação do Prisma
  if (err.code === 'P2002') {
    const message = 'Recurso duplicado. Este item já existe.';
    error = { message, statusCode: 400 };
  }

  // Erro de registro não encontrado do Prisma
  if (err.code === 'P2025') {
    const message = 'Recurso não encontrado.';
    error = { message, statusCode: 404 };
  }

  // Erro de validação do Prisma
  if (err.code === 'P2003') {
    const message = 'Erro de relacionamento. Verifique os dados enviados.';
    error = { message, statusCode: 400 };
  }

  // Erro de JWT
  if (err.name === 'JsonWebTokenError') {
    const message = 'Token inválido. Faça login novamente.';
    error = { message, statusCode: 401 };
  }

  // Erro de JWT expirado
  if (err.name === 'TokenExpiredError') {
    const message = 'Token expirado. Faça login novamente.';
    error = { message, statusCode: 401 };
  }

  // Erro de sintaxe JSON
  if (err.type === 'entity.parse.failed') {
    const message = 'Dados JSON inválidos.';
    error = { message, statusCode: 400 };
  }

  // Erro de payload muito grande
  if (err.type === 'entity.too.large') {
    const message = 'Arquivo muito grande. Tamanho máximo: 10MB.';
    error = { message, statusCode: 413 };
  }

  res.status(error.statusCode || 500).json({
    success: false,
    error: error.message || 'Erro interno do servidor',
    ...(process.env.NODE_ENV === 'development' && { stack: err.stack })
  });
};