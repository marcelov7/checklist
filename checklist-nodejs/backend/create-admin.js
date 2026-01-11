import pkg from '@prisma/client';
const { PrismaClient } = pkg;
import bcrypt from 'bcryptjs';

const prisma = new PrismaClient();

async function createAdmin() {
  try {
    console.log('🔧 Criando usuário administrador...');

    // Dados do admin
    const adminData = {
      name: 'Administrador',
      email: 'admin@checklist.com',
      password: 'admin123',
      role: 'ADMIN'
    };

    // Verifica se o usuário já existe
    const existingUser = await prisma.user.findUnique({
      where: { email: adminData.email }
    });

    if (existingUser) {
      console.log('⚠️  Usuário administrador já existe!');
      console.log(`📧 Email: ${existingUser.email}`);
      console.log(`👤 Nome: ${existingUser.name}`);
      console.log(`🔑 Role: ${existingUser.role}`);
      return;
    }

    // Hash da senha
    const hashedPassword = await bcrypt.hash(adminData.password, 12);

    // Cria o usuário admin
    const admin = await prisma.user.create({
      data: {
        name: adminData.name,
        email: adminData.email,
        password: hashedPassword,
        role: adminData.role
      }
    });

    console.log('✅ Usuário administrador criado com sucesso!');
    console.log('📋 Dados de acesso:');
    console.log(`📧 Email: ${admin.email}`);
    console.log(`🔑 Senha: ${adminData.password}`);
    console.log(`👤 Nome: ${admin.name}`);
    console.log(`🛡️  Role: ${admin.role}`);
    console.log(`🆔 ID: ${admin.id}`);
    console.log('');
    console.log('🌐 Agora você pode fazer login no sistema!');

  } catch (error) {
    console.error('❌ Erro ao criar usuário administrador:', error);
  } finally {
    await prisma.$disconnect();
  }
}

// Executa o script
createAdmin();