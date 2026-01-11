import express from 'express';
import { 
  register, 
  login, 
  getProfile, 
  updateProfile, 
  changePassword 
} from '../controllers/authController.js';
import { authenticate } from '../middleware/auth.js';

const router = express.Router();

/**
 * Rotas de Autenticação
 * Endpoints para registro, login e gerenciamento de perfil
 */

// Rotas públicas (não requerem autenticação)
router.post('/register', register);
router.post('/login', login);

// Rotas protegidas (requerem autenticação)
router.get('/profile', authenticate, getProfile);
router.put('/profile', authenticate, updateProfile);
router.patch('/change-password', authenticate, changePassword);

export default router;