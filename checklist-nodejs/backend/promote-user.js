import prisma from './src/database/prisma.js';

async function promoteUserToAdmin() {
  try {
    console.log('🔄 Promovendo usuário de teste a administrador...\n');
    
    // Buscar o usuário de teste
    const user = await prisma.user.findUnique({
      where: { email: 'teste@checklist.com' }
    });

    if (!user) {
      console.log('❌ Usuário de teste não encontrado');
      return;
    }

    console.log(`👤 Usuário encontrado: ${user.name} (${user.email})`);
    console.log(`🔑 Role atual: ${user.role}`);

    if (user.role === 'ADMIN') {
      console.log('✅ Usuário já é administrador!');
      return;
    }

    // Promover a administrador
    const updatedUser = await prisma.user.update({
      where: { id: user.id },
      data: { role: 'ADMIN' },
      select: {
        id: true,
        name: true,
        email: true,
        username: true,
        role: true,
        updatedAt: true
      }
    });

    console.log('\n✅ Usuário promovido com sucesso!');
    console.log(`👑 Nova role: ${updatedUser.role}`);
    console.log(`📅 Atualizado em: ${new Date(updatedUser.updatedAt).toLocaleString('pt-BR')}`);
    
    console.log('\n🎉 Agora você pode criar áreas no sistema!');

  } catch (error) {
    console.error('❌ Erro ao promover usuário:', error);
  } finally {
    await prisma.$disconnect();
  }
}

promoteUserToAdmin();