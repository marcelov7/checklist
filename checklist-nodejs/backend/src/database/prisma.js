import pkg from '@prisma/client';
const { PrismaClient } = pkg;

/**
 * Instância global do Prisma Client
 * Configurada com logs para desenvolvimento
 */
const prisma = new PrismaClient({
  log: process.env.NODE_ENV === 'development' ? ['query', 'info', 'warn', 'error'] : ['error'],
  errorFormat: 'pretty',
});

/**
 * Conecta ao banco de dados
 */
export const connectDatabase = async () => {
  try {
    await prisma.$connect();
    console.log('✅ Banco de dados conectado com sucesso');
  } catch (error) {
    console.error('❌ Erro ao conectar com o banco de dados:', error);
    process.exit(1);
  }
};

/**
 * Desconecta do banco de dados
 */
export const disconnectDatabase = async () => {
  try {
    await prisma.$disconnect();
    console.log('✅ Banco de dados desconectado');
  } catch (error) {
    console.error('❌ Erro ao desconectar do banco de dados:', error);
  }
};

export default prisma;