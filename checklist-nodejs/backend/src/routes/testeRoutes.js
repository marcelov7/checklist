import express from 'express';
import { 
  getTestes, 
  getTestesByParada,
  getTesteById, 
  createTeste, 
  updateTeste, 
  deleteTeste,
  updateTesteProgress 
} from '../controllers/testeController.js';
import { authenticate, authorize } from '../middleware/auth.js';

const router = express.Router();

/**
 * Rotas de Testes
 * Endpoints para gerenciamento de testes
 */

// Middleware de autenticação para todas as rotas
router.use(authenticate);

// Rotas de consulta (todos os usuários autenticados)
router.get('/', getTestes);
router.get('/:id', getTesteById);

// Rotas de modificação (usuários podem criar/atualizar testes)
router.post('/', createTeste);
router.put('/:id', updateTeste);
router.patch('/:id/progress', updateTesteProgress);

// Rotas administrativas (apenas administradores)
router.delete('/:id', authorize(['ADMIN']), deleteTeste);

export default router;