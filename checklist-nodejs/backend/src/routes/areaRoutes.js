import express from 'express';
import { 
  getAreas, 
  getAllAreas, 
  getAreaById, 
  createArea, 
  updateArea, 
  deleteArea,
  getAreaStats 
} from '../controllers/areaController.js';
import { authenticate, authorize } from '../middleware/auth.js';

const router = express.Router();

/**
 * Rotas de Áreas
 * Endpoints para gerenciamento de áreas
 */

// Middleware de autenticação para todas as rotas
router.use(authenticate);

// Rotas de consulta (todos os usuários autenticados)
router.get('/', getAreas);
router.get('/all', getAllAreas);
router.get('/:id', getAreaById);
router.get('/:id/stats', getAreaStats);

// Rotas de modificação (apenas administradores)
router.post('/', authorize(['ADMIN']), createArea);
router.put('/:id', authorize(['ADMIN']), updateArea);
router.delete('/:id', authorize(['ADMIN']), deleteArea);

export default router;