import jwt from 'jsonwebtoken';
import bcrypt from 'bcryptjs';

/**
 * Gera um token JWT para o usuário
 * @param {Object} payload - Dados do usuário para incluir no token
 * @returns {string} Token JWT
 */
export const generateToken = (payload) => {
  return jwt.sign(payload, process.env.JWT_SECRET, {
    expiresIn: process.env.JWT_EXPIRES_IN || '7d',
  });
};

/**
 * Verifica e decodifica um token JWT
 * @param {string} token - Token JWT para verificar
 * @returns {Object} Payload decodificado
 */
export const verifyToken = (token) => {
  return jwt.verify(token, process.env.JWT_SECRET);
};

/**
 * Gera hash da senha usando bcrypt
 * @param {string} password - Senha em texto plano
 * @returns {Promise<string>} Hash da senha
 */
export const hashPassword = async (password) => {
  const saltRounds = parseInt(process.env.BCRYPT_ROUNDS) || 12;
  return await bcrypt.hash(password, saltRounds);
};

/**
 * Compara senha em texto plano com hash
 * @param {string} password - Senha em texto plano
 * @param {string} hash - Hash da senha
 * @returns {Promise<boolean>} True se as senhas coincidirem
 */
export const comparePassword = async (password, hash) => {
  return await bcrypt.compare(password, hash);
};

/**
 * Extrai token do header Authorization
 * @param {string} authHeader - Header Authorization
 * @returns {string|null} Token ou null se não encontrado
 */
export const extractTokenFromHeader = (authHeader) => {
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    return null;
  }
  return authHeader.substring(7);
};