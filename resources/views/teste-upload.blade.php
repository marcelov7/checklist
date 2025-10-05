<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teste Upload Foto</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .result { margin-top: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Teste de Upload de Foto - Sistema Checklist</h1>
        
        <form id="testeForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="teste_id">ID do Teste:</label>
                <select id="teste_id" name="teste_id" required>
                    <option value="">Selecione um teste...</option>
                    @foreach($testes as $teste)
                        <option value="{{ $teste->id }}">Teste #{{ $teste->id }} - {{ $teste->equipamento->nome ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="item">Item do Checklist:</label>
                <select id="item" name="item" required>
                    <option value="">Selecione um item...</option>
                    <option value="ar_comprimido">Ar Comprimido</option>
                    <option value="protecoes_eletricas">Proteções Elétricas</option>
                    <option value="protecoes_mecanicas">Proteções Mecânicas</option>
                    <option value="chave_remoto">Chave Remoto</option>
                    <option value="inspecionado">Inspecionado</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="problema">Descrição do Problema:</label>
                <textarea id="problema" name="problema" rows="3" required>Problema de teste para verificar upload</textarea>
            </div>
            
            <div class="form-group">
                <label for="foto">Foto do Problema:</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>
            
            <button type="submit">Salvar Problema com Foto</button>
        </form>
        
        <div id="resultado" class="result" style="display: none;"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#testeForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const testeId = $('#teste_id').val();
            const item = $('#item').val();
            const problema = $('#problema').val();
            const foto = $('#foto')[0].files[0];
            
            if (!testeId || !item || !problema) {
                mostrarResultado('error', 'Preencha todos os campos obrigatórios');
                return;
            }
            
            formData.append('item', item);
            formData.append('problema', problema);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            
            if (foto) {
                formData.append('foto', foto);
                console.log('Foto anexada:', foto.name, 'Tamanho:', foto.size);
            }
            
            console.log('Enviando dados para teste:', testeId);
            
            $.ajax({
                url: `/testes/${testeId}/salvar-problema`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('button[type="submit"]').prop('disabled', true).text('Enviando...');
                },
                success: function(response) {
                    console.log('Resposta recebida:', response);
                    
                    if (response.success) {
                        let msg = 'Problema salvo com sucesso!';
                        if (response.foto_problema_path) {
                            msg += '<br><strong>Foto salva em:</strong> ' + response.foto_problema_path;
                        }
                        mostrarResultado('success', msg);
                    } else {
                        mostrarResultado('error', response.message || 'Erro desconhecido');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', xhr.responseText);
                    
                    let mensagem = 'Erro ao salvar problema';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            mensagem = response.message;
                        } else if (response.errors) {
                            mensagem = Object.values(response.errors).flat().join(', ');
                        }
                    } catch (e) {
                        mensagem += ' (Status: ' + xhr.status + ')';
                    }
                    
                    mostrarResultado('error', mensagem);
                },
                complete: function() {
                    $('button[type="submit"]').prop('disabled', false).text('Salvar Problema com Foto');
                }
            });
        });
        
        function mostrarResultado(tipo, mensagem) {
            const $resultado = $('#resultado');
            $resultado.removeClass('success error').addClass(tipo);
            $resultado.html(mensagem).show();
        }
    </script>
</body>
</html>