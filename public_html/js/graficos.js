$(document).ready(() =>{

    $.ajax({
        url: '/grafico',
        type: 'GET',
        data: 'dados',
        dataType: 'json',
        success: dados => {
            grafico(dados.ontem, dados.hoje);
        },
        error: erro => {console.log(erro)}
    });
});

grafico = (ontem, hoje)=>{

    // Gráfico de linha
    const ctx = document.getElementById('grafico');
    const grafico = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Ontem', 'Hoje'],
        datasets: [{
            label: 'Tarefas concluídas',
            data: [ontem, hoje, 10, 0],
            backgroundColor: [
                'rgb(0, 195, 255)',
                'rgb(255, 196 ,0)'
            ],
            borderWidth: 1
        },
        
    ]
    },
    options: {
        legend: {
            display: false
        },
        /*
        title:{
            display: true,
            text: 'Filmes e Séries adicionados nos últimos meses',
            fontSize: 20
        }, */
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
    }); // Fim gráfico
}


