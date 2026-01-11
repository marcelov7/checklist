const API_URL = 'http://localhost:3001/api';

async function testLogin() {
  console.log('🔍 Testando diferentes formatos de login...\n');

  // Teste 1: Formato que o frontend está enviando (provavelmente)
  console.log('1. Testando com formato frontend (email + password):');
  try {
    const response1 = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        email: 'admin@checklist.com',
        password: 'admin123'
      })
    });

    const data1 = await response1.text();
    console.log(`Status: ${response1.status}`);
    console.log(`Response: ${data1}\n`);
  } catch (error) {
    console.log(`Erro: ${error.message}\n`);
  }

  // Teste 2: Formato com username
  console.log('2. Testando com formato username:');
  try {
    const response2 = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        username: 'admin@checklist.com',
        password: 'admin123'
      })
    });

    const data2 = await response2.text();
    console.log(`Status: ${response2.status}`);
    console.log(`Response: ${data2}\n`);
  } catch (error) {
    console.log(`Erro: ${error.message}\n`);
  }

  // Teste 3: Formato com dados vazios (para ver erro de validação)
  console.log('3. Testando com dados vazios:');
  try {
    const response3 = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({})
    });

    const data3 = await response3.text();
    console.log(`Status: ${response3.status}`);
    console.log(`Response: ${data3}\n`);
  } catch (error) {
    console.log(`Erro: ${error.message}\n`);
  }

  // Teste 4: Formato com Content-Type incorreto
  console.log('4. Testando sem Content-Type:');
  try {
    const response4 = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      body: JSON.stringify({
        email: 'admin@checklist.com',
        password: 'admin123'
      })
    });

    const data4 = await response4.text();
    console.log(`Status: ${response4.status}`);
    console.log(`Response: ${data4}\n`);
  } catch (error) {
    console.log(`Erro: ${error.message}\n`);
  }
}

testLogin().catch(console.error);