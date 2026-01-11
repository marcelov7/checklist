/**
 * Middleware para rotas não encontradas (404)
 */
export const notFound = (req, res, next) => {
  const error = new Error(`Rota não encontrada: ${req.originalUrl}`);
  error.statusCode = 404;
  next(error);
};