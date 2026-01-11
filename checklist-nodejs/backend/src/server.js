import app from './app.js';
import prisma from './database/prisma.js';
import 'dotenv/config';

/**
 * Servidor Principal
 * Inicialização do Sistema de Checklist de Paradas
 */

const PORT = process.env.PORT || 3001;
const NODE_ENV = process.env.NODE_ENV || 'development';

// Função para inicializar o servidor
const startServer = async () => {
  try {
    // Testa a conexão com o banco de dados
    console.log('🔄 Testando conexão com o banco de dados...');
    await prisma.$connect();
    console.log('✅ Conexão com o banco de dados estabelecida');

    // Inicia o servidor
    const server = app.listen(PORT, () => {
      console.log('🚀 Servidor iniciado com sucesso!');
      console.log(`📍 Ambiente: ${NODE_ENV}`);
      console.log(`🌐 Servidor rodando na porta: ${PORT}`);
      console.log(`🔗 URL: http://localhost:${PORT}`);
      console.log(`📊 API Status: http://localhost:${PORT}/api/status`);
      console.log(`❤️  Health Check: http://localhost:${PORT}/health`);
      console.log('─'.repeat(50));
    });

    // Configuração para graceful shutdown
    const gracefulShutdown = async (signal) => {
      console.log(`\n🛑 Recebido sinal ${signal}. Iniciando shutdown graceful...`);
      
      server.close(async () => {
        console.log('🔄 Fechando conexões HTTP...');
        
        try {
          await prisma.$disconnect();
          console.log('✅ Conexão com banco de dados fechada');
        } catch (error) {
          console.error('❌ Erro ao fechar conexão com banco:', error);
        }
        
        console.log('👋 Servidor encerrado com sucesso');
        process.exit(0);
      });

      // Força o encerramento após 10 segundos
      setTimeout(() => {
        console.error('⚠️  Forçando encerramento do servidor...');
        process.exit(1);
      }, 10000);
    };

    // Listeners para sinais de encerramento
    process.on('SIGTERM', () => gracefulShutdown('SIGTERM'));
    process.on('SIGINT', () => gracefulShutdown('SIGINT'));

    // Tratamento de erros não capturados
    process.on('uncaughtException', (error) => {
      console.error('❌ Erro não capturado:', error);
      gracefulShutdown('uncaughtException');
    });

    process.on('unhandledRejection', (reason, promise) => {
      console.error('❌ Promise rejeitada não tratada:', reason);
      console.error('Promise:', promise);
      gracefulShutdown('unhandledRejection');
    });

  } catch (error) {
    console.error('❌ Erro ao iniciar o servidor:', error);
    
    try {
      await prisma.$disconnect();
    } catch (disconnectError) {
      console.error('❌ Erro ao desconectar do banco:', disconnectError);
    }
    
    process.exit(1);
  }
};

// Inicia o servidor
startServer();