import { Router } from 'express';
import {
  listarEquipamentos,
  buscarEquipamentoPorId,
  criarEquipamento,
  atualizarEquipamento,
  deletarEquipamento,
  obterProximaNumeracao
} from '../controllers/equipamentoController.js';
import { authenticate } from '../middleware/auth.js';

const router = Router();

// Aplicar middleware de autenticação em todas as rotas
router.use(authenticate);

// Rotas de equipamentos
router.get('/', listarEquipamentos);
router.get('/next-numeracao', obterProximaNumeracao);
router.get('/:id', buscarEquipamentoPorId);
router.post('/', criarEquipamento);
router.put('/:id', atualizarEquipamento);
router.delete('/:id', deletarEquipamento);

export default router;