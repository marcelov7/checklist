import express from 'express';
import { 
  getParadas, 
  getParadaById, 
  createParada, 
  updateParada, 
  deleteParada,
  assignUserToParada,
  unassignUserFromParada 
} from '../controllers/paradaController.js';
import { authenticate, authorize } from '../middleware/auth.js';

const router = express.Router();

/**
 * Rotas de Paradas
 * Endpoints para gerenciamento de paradas
 */

// Middleware de autenticação para todas as rotas
router.use(authenticate);

// Rotas de consulta (todos os usuários autenticados)
router.get('/', getParadas);
router.get('/:id', getParadaById);

// Rotas de modificação (administradores e usuários podem criar/atualizar suas próprias paradas)
router.post('/', createParada);
router.put('/:id', updateParada);

// Rotas administrativas (apenas administradores)
router.delete('/:id', authorize(['ADMIN']), deleteParada);
router.patch('/:id/assign', authorize(['ADMIN']), assignUserToParada);
router.patch('/:id/unassign', authorize(['ADMIN']), unassignUserFromParada);

export default router;