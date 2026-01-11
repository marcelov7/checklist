import express from 'express';
import { 
  getUsers, 
  getUserById, 
  createUser, 
  updateUser, 
  deleteUser 
} from '../controllers/userController.js';
import { authenticate, authorize } from '../middleware/auth.js';

const router = express.Router();

/**
 * Rotas de Usuários
 * Endpoints para gerenciamento administrativo de usuários
 * Todas as rotas requerem autenticação e autorização de administrador
 */

// Middleware de autenticação e autorização para todas as rotas
router.use(authenticate);
router.use(authorize(['ADMIN']));

// CRUD de usuários
router.get('/', getUsers);
router.get('/:id', getUserById);
router.post('/', createUser);
router.put('/:id', updateUser);
router.delete('/:id', deleteUser);

export default router;