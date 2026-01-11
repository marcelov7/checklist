import express from 'express';
import authRoutes from './authRoutes.js';
import userRoutes from './userRoutes.js';
import areaRoutes from './areaRoutes.js';
import paradaRoutes from './paradaRoutes.js';
import testeRoutes from './testeRoutes.js';
import paradaTesteRoutes from './paradaTesteRoutes.js';
import equipamentoRoutes from './equipamentoRoutes.js';

const router = express.Router();

/**
 * Configuração Central de Rotas
 * Organiza e centraliza todas as rotas da API
 */

// Rota de status da API
router.get('/status', (req, res) => {
  res.json({
    success: true,
    message: 'API do Sistema de Checklist está funcionando',
    timestamp: new Date().toISOString(),
    version: '1.0.0'
  });
});

// Rotas de autenticação
router.use('/auth', authRoutes);

// Rotas de usuários (administrativas)
router.use('/users', userRoutes);

// Rotas de áreas
router.use('/areas', areaRoutes);

// Rotas de equipamentos
router.use('/equipments', equipamentoRoutes);

// Rotas de paradas
router.use('/paradas', paradaRoutes);

// Rotas de testes
router.use('/testes', testeRoutes);

// Rotas específicas para testes de paradas
router.use('/paradas', paradaTesteRoutes);

export default router;