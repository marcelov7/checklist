import express from 'express';
import { getTestesByParada } from '../controllers/testeController.js';
import { authenticate } from '../middleware/auth.js';

const router = express.Router();

/**
 * Rotas de Testes por Parada
 * Endpoints para gerenciamento de testes específicos de uma parada
 */

// Middleware de autenticação para todas as rotas
router.use(authenticate);

// Rota para buscar testes de uma parada específica
router.get('/:paradaId/testes', getTestesByParada);

export default router;