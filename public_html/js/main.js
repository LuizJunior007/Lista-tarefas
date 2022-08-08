$(document).ready(()=>{

    $('.toast').toast('show');    

    $('.modal').on('hidden.bs.modal',  function(){
        $(this).find('input').val('');
        $(this).find('input').removeClass('borda_vermelha');
        $(this).find('small').text('');
    });

    $('#btnAltSenha').on('click', e =>{

        const novaSenha = $('#nova_senha').val();
        const conSenha = $('#confirm_senha').val();

        if(novaSenha !== conSenha){
            e.preventDefault();
            $('#msgErro').text('As senhas não coincidem uma com a outra');
            $('#msgErro').css('display', 'block');
        } else if(novaSenha.length < 6 || conSenha.length < 6){
            e.preventDefault();
            $('#msgErro').text('Sua senha deve conter pelo menos 6 caracteres');
            $('#msgErro').css('display', 'block');
        } 
    });
    
    $('#cadastrar').on('click', e =>{

        const nome = $('#nome').val();
        const email = $('#email_cadastro').val();
        const senha = $('#senha_cadastro').val();

        if(nome.length < 4){
            
            $('#msgNome').text('Seu nome deve conter pelo menos 4 caracteres');
            $('#nome').addClass('borda_vermelha');
            e.preventDefault();
        } else {

            $('#msgNome').empty();
            $('#nome').removeClass('borda_vermelha');
        }

        if(!validarEmail(email)){
            $('#msgEmail').text('Insira um email válido!');
            $('#email_cadastro').addClass('borda_vermelha');
            e.preventDefault();
        } else{

            $('#msgEmail').empty();
            $('#email_cadastro').removeClass('borda_vermelha');
        }

        if(senha.length < 6){
            $('#msgSenha').text('Sua senha deve conter pelo menos 6 caracteres');
            $('#senha_cadastro').addClass('borda_vermelha');
            e.preventDefault();
        } else{
            $('#msgSenha').empty();
            $('#senha_cadastro').removeClass('borda_vermelha');
        }
    });

    $('#filtrar').on('change', e =>{

        const p = $(e.target).val();

        $.ajax({
            url: '/classificar',
            type: 'GET',
            data: `c=${p}`,
            dataType: 'json',
            success: dados => {

                $('#tabela').empty();

                $('#tabela').append(`
                    <tr>
                        <td colspan="7">
                            <div class="spinner-border text-info" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </td>
                    </tr>
                `);

                if(dados != ''){

                    $('#tabela').empty();

                    dados.forEach(t =>{

                        $('#tabela').append(`

                            <tr>
                                <td>${t.titulo}</td>
                                <td>${t.descricao}</td>
                                <td>${t.prioridade}</td>
                                <td>${formatar_data(t.data)}</td>
                                <td>${t.hora}</td>
                                <td>
                                    <i class="fas fa-check text-success me-2" title="Concluir" onclick="concluir_t(${t.id})"></i>
                                    <i class="fas fa-edit text-info me-2" title="Editar" onclick="editar_t(${t.id})"></i>
                                    <i class="fas fa-trash text-danger" title="Remover" onclick="remover_t(${t.id})"></i>
                                </td>
                            </tr>
                        `);
                    });
                } else{

                    $('#tabela').empty();
                    $('#tabela').append(`
                        <tr>
                            <td colspan="6">Nenhum registro foi encontrado.</td>
                        </tr>
                    `);
                }
            },
            error: erro => {console.log(erro)}
        });
    });

    $('#pesquisar').on('keyup', e =>{

        const p = $(e.target).val();

        $.ajax({

            url: '/pesquisar',
            type: 'GET',
            data: `p=${p}`,
            dataType: 'json',
            success: dados => {

                $('#tabela').empty();

                $('#tabela').append(`
                    <tr>
                        <td colspan="7">
                            <div class="spinner-border text-info" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </td>
                    </tr>
                `);

                if(dados != ''){

                    $('#tabela').empty();

                    dados.forEach(t =>{

                        $('#tabela').append(`

                            <tr>
                                <td>${t.titulo}</td>
                                <td>${t.descricao}</td>
                                <td>${t.prioridade}</td>
                                <td>${formatar_data(t.data)}</td>
                                <td>${t.hora}</td>
                                <td>
                                    <i class="fas fa-check text-success me-2" title="Concluir" onclick="concluir_t(${t.id})"></i>
                                    <i class="fas fa-edit text-info me-2" title="Editar" onclick="editar_t(${t.id})"></i>
                                    <i class="fas fa-trash text-danger" title="Remover" onclick="remover_t(${t.id})"></i>
                                </td>
                            </tr>
                        `);
                    });
                } else{

                    $('#tabela').empty();
                    $('#tabela').append(`
                        <tr>
                            <td colspan="6">Nenhum registro foi encontrado.</td>
                        </tr>
                    `);
                }
            },
            error: erro => {console.log(erro)}
        });
    });
});

editar_t = id =>{

    $.ajax({
        url: '/select_tarefa',
        type: 'GET',
        data: `id=${id}`,
        dataType: 'json', 
        success: t => {

            $('#eTitulo').val(t.titulo);
            $('#eDescricao').val(t.descricao);
            $('#ePrioridade').val(t.prioridade);
            $('#eData').val(t.data);
            $('#eHora').val(t.hora);
            $('#editTarefa').modal('show');
        },
        error: erro => {console.log(erro)}
    });
}

remover_t = id =>{

    $.ajax({
        url: '/select_tarefa',
        type: 'GET',
        data: `id=${id}`,
        dataType: 'json',
        success: t => {

            $('#idT').val(t.id);
            $('#removerTarefa').modal('show');
        },
        error: erro => {console.log(erro)}
    });
}

concluir_t = id =>{

    $.ajax({
        url: '/select_tarefa',
        type: 'GET',
        data: `id=${id}`,
        dataType: 'json',
        success: t => {

            $('#idC').val(t.id);
            $('#tTitulo').val(t.titulo);
            $('#tDescricao').val(t.descricao);
            $('#tPrioridade').val(t.prioridade);
            $('#concluirTarefa').modal('show');
        },
        error: erro => {console.log(erro)}
    });
}

validarEmail = (email) => {
    let re = /\S+@\S+\.\S+/;
    return re.test(email);
}

formatar_data = (d) =>{

    const date = new Date(d); 
    const formatter = Intl.DateTimeFormat('pt-BR', {
        timeZone: "UTC",
        dateStyle: "short"
    });

    return formatter.format(date);
}

addZero = n => {

    if(n <= 9){

        return '0' + n;
    } else{

        return n;
    }
}