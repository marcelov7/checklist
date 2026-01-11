const API_URL = 'http://localhost:3001/api';

async function testLogin() {
  console.log('🔍 Testando login com email e username...\n');

  // Primeiro, vamos registrar um usuário com username
  console.log('1. Registrando usuário de teste com username:');
  try {
    const registerResponse = await fetch(`${API_URL}/auth/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: 'Usuário Teste',
        email: 'teste@checklist.com',
        username: 'usuario_teste',
        password: 'Senha123!'
      })
    });

    const registerData = await registerResponse.text();
    console.log(`Status: ${registerResponse.status}`);
    console.log(`Response: ${registerData}\n`);
  } catch (error) {
    console.log(`Erro no registro: ${error.message}\n`);
  }

  // Teste 2: Login com email
  console.log('2. Testando login com EMAIL:');
  try {
    const emailResponse = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        email: 'teste@checklist.com',
        password: 'Senha123!'
      })
    });

    const emailData = await emailResponse.text();
    console.log(`Status: ${emailResponse.status}`);
    console.log(`Response: ${emailData}\n`);
  } catch (error) {
    console.log(`Erro no login com email: ${error.message}\n`);
  }

  // Teste 3: Login com username
  console.log('3. Testando login com USERNAME:');
  try {
    const usernameResponse = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        username: 'usuario_teste',
        password: 'Senha123!'
      })
    });

    const usernameData = await usernameResponse.text();
    console.log(`Status: ${usernameResponse.status}`);
    console.log(`Response: ${usernameData}\n`);
  } catch (error) {
    console.log(`Erro no login com username: ${error.message}\n`);
  }

  // Teste 4: Login com dados inválidos
  console.log('4. Testando login com dados inválidos:');
  try {
    const invalidResponse = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        username: 'usuario_inexistente',
        password: 'senha_errada'
      })
    });

    const invalidData = await invalidResponse.text();
    console.log(`Status: ${invalidResponse.status}`);
    console.log(`Response: ${invalidData}\n`);
  } catch (error) {
    console.log(`Erro no login inválido: ${error.message}\n`);
  }
}

// Executar os testes
testLogin().catch(console.error);