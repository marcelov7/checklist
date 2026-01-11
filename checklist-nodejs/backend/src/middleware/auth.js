import { verifyToken, extractTokenFromHeader } from '../utils/auth.js';
import { unauthorizedResponse } from '../utils/response.js';
import prisma from '../database/prisma.js';

/**
 * Middleware de autenticação
 * Verifica se o usuário está autenticado através do token JWT
 */
export const authenticate = async (req, res, next) => {
  try {
    // Extrai o token do header Authorization
    const token = extractTokenFromHeader(req.headers.authorization);
    
    if (!token) {
      return unauthorizedResponse(res, 'Token de acesso não fornecido');
    }

    // Verifica e decodifica o token
    const decoded = verifyToken(token);
    
    // Busca o usuário no banco de dados
    const user = await prisma.user.findUnique({
      where: { id: decoded.id },
      select: {
        id: true,
        name: true,
        email: true,
        role: true,
        createdAt: true,
        updatedAt: true
      }
    });

    if (!user) {
      return unauthorizedResponse(res, 'Usuário não encontrado');
    }

    // Adiciona o usuário ao objeto request
    req.user = user;
    next();
  } catch (error) {
    console.error('❌ Erro na autenticação:', error);
    
    if (error.name === 'JsonWebTokenError') {
      return unauthorizedResponse(res, 'Token inválido');
    }
    
    if (error.name === 'TokenExpiredError') {
      return unauthorizedResponse(res, 'Token expirado. Faça login novamente');
    }
    
    return unauthorizedResponse(res, 'Erro na autenticação');
  }
};

/**
 * Middleware de autorização por role
 * Verifica se o usuário tem a role necessária
 */
export const authorize = (roles) => {
  return (req, res, next) => {
    if (!req.user) {
      return res.status(401).json({ 
        message: 'Acesso negado. Token não fornecido ou inválido.' 
      });
    }

    if (!roles.includes(req.user.role)) {
      return res.status(401).json({ 
        message: 'Acesso negado. Permissões insuficientes.' 
      });
    }

    next();
  };
};

/**
 * Middleware de autenticação opcional
 * Adiciona o usuário ao request se autenticado, mas não bloqueia se não estiver
 */
export const optionalAuth = async (req, res, next) => {
  try {
    const token = extractTokenFromHeader(req.headers.authorization);
    
    if (token) {
      const decoded = verifyToken(token);
      const user = await prisma.user.findUnique({
        where: { id: decoded.id },
        select: {
          id: true,
          name: true,
          email: true,
          role: true,
          createdAt: true,
          updatedAt: true
        }
      });
      
      if (user) {
        req.user = user;
      }
    }
    
    next();
  } catch (error) {
    // Em caso de erro, continua sem autenticação
    next();
  }
};