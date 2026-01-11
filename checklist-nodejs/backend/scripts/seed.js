import prisma from '../src/database/prisma.js';

async function seed() {
  try {
    console.log('🌱 Iniciando seed do banco de dados...');

    // Verificar se já existem áreas
    const existingAreas = await prisma.area.findMany();
    console.log('Áreas existentes:', existingAreas.length);

    let area;
    if (existingAreas.length === 0) {
      // Criar área de exemplo
      area = await prisma.area.create({
        data: {
          name: 'Produção',
          description: 'Área de produção industrial'
        }
      });
      console.log('✅ Área criada:', area.name);
    } else {
      area = existingAreas[0];
      console.log('✅ Usando área existente:', area.name);
    }

    // Verificar se já existem equipamentos
    const existingEquipments = await prisma.equipamento.findMany();
    console.log('Equipamentos existentes:', existingEquipments.length);

    if (existingEquipments.length === 0) {
      // Criar equipamentos de exemplo
      const equipamentos = [
        {
          numeracao: 'EQ001',
          nome: 'Bomba Centrífuga',
          tipo: 'Bomba',
          fabricante: 'KSB',
          modelo: 'Etanorm',
          numeroSerie: 'BC001',
          status: 'ATIVO',
          prioridade: 1,
          observacoes: 'Equipamento crítico para produção',
          areaId: area.id
        },
        {
          numeracao: 'EQ002',
          nome: 'Compressor de Ar',
          tipo: 'Compressor',
          fabricante: 'Atlas Copco',
          modelo: 'GA22',
          numeroSerie: 'CA002',
          status: 'ATIVO',
          prioridade: 2,
          observacoes: 'Sistema de ar comprimido',
          areaId: area.id
        },
        {
          numeracao: 'EQ003',
          nome: 'Motor Elétrico',
          tipo: 'Motor',
          fabricante: 'WEG',
          modelo: 'W22',
          numeroSerie: 'ME003',
          status: 'ATIVO',
          prioridade: 3,
          observacoes: 'Motor principal da linha',
          areaId: area.id
        }
      ];

      for (const equipamento of equipamentos) {
        const created = await prisma.equipamento.create({
          data: equipamento
        });
        console.log('✅ Equipamento criado:', created.nome);
      }
    }

    console.log('🎉 Seed concluído com sucesso!');
  } catch (error) {
    console.error('❌ Erro no seed:', error);
  } finally {
    await prisma.$disconnect();
  }
}

seed();