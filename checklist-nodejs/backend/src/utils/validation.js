/**
 * Utilitários de validação para dados de entrada
 */

/**
 * Valida se o email tem formato válido
 * @param {string} email - Email para validar
 * @returns {boolean} True se válido
 */
export const isValidEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

/**
 * Valida se a senha atende aos critérios mínimos
 * @param {string} password - Senha para validar
 * @returns {Object} Resultado da validação
 */
export const validatePassword = (password) => {
  const errors = [];
  
  if (!password || password.length < 6) {
    errors.push('Senha deve ter pelo menos 6 caracteres');
  }
  
  if (!/[A-Z]/.test(password)) {
    errors.push('Senha deve conter pelo menos uma letra maiúscula');
  }
  
  if (!/[a-z]/.test(password)) {
    errors.push('Senha deve conter pelo menos uma letra minúscula');
  }
  
  if (!/\d/.test(password)) {
    errors.push('Senha deve conter pelo menos um número');
  }
  
  return {
    isValid: errors.length === 0,
    errors
  };
};

/**
 * Valida se o nome tem formato válido
 * @param {string} name - Nome para validar
 * @returns {boolean} True se válido
 */
export const isValidName = (name) => {
  return name && name.trim().length >= 2 && name.trim().length <= 100;
};

/**
 * Valida se o progresso está no intervalo válido (0-100)
 * @param {number} progress - Progresso para validar
 * @returns {boolean} True se válido
 */
export const isValidProgress = (progress) => {
  return typeof progress === 'number' && progress >= 0 && progress <= 100;
};

/**
 * Valida se o status é válido para uma entidade
 * @param {string} status - Status para validar
 * @param {Array} validStatuses - Array de status válidos
 * @returns {boolean} True se válido
 */
export const isValidStatus = (status, validStatuses) => {
  return validStatuses.includes(status);
};

/**
 * Sanitiza string removendo caracteres especiais
 * @param {string} str - String para sanitizar
 * @returns {string} String sanitizada
 */
export const sanitizeString = (str) => {
  if (!str) return '';
  return str.trim().replace(/[<>]/g, '');
};

/**
 * Valida se o ID tem formato válido (CUID)
 * @param {string} id - ID para validar
 * @returns {boolean} True se válido
 */
export const isValidId = (id) => {
  // CUID format: c + timestamp + counter + fingerprint
  const cuidRegex = /^c[a-z0-9]{24}$/;
  return cuidRegex.test(id);
};