const listaServicos = document.querySelector('#listaServicos');
const selectServico = document.querySelector('#servico');
const modal = document.querySelector('#modalAgendamento');
const fundo = document.querySelector('#modalFundo');
const form = document.querySelector('#formAgendamento');
const mensagem = document.querySelector('#mensagem');
const menu = document.querySelector('#menu');

let servicos = [];
let filtroAtual = 'todos';

const servicosPadrao = [
  { id: 1, nome: 'Corte feminino', categoria: 'Cabelo', duracao: '40min', preco: 60 },
  { id: 2, nome: 'Escova', categoria: 'Cabelo', duracao: '45min', preco: 70 },
  { id: 3, nome: 'Hidratação', categoria: 'Cabelo', duracao: '50min', preco: 80 },
  { id: 4, nome: 'Maquiagem social', categoria: 'Maquiagem', duracao: '1h', preco: 100 },
  { id: 5, nome: 'Maquiagem artística', categoria: 'Maquiagem', duracao: '1h30min', preco: 180 },
  { id: 6, nome: 'Maquiagem para noivas', categoria: 'Maquiagem', duracao: '1h30min', preco: 250 },
  { id: 7, nome: 'Design de sobrancelhas', categoria: 'Sobrancelhas', duracao: '30min', preco: 40 },
  { id: 8, nome: 'Alongamento de cílios', categoria: 'Sobrancelhas', duracao: '1h30min', preco: 120 },
  { id: 9, nome: 'Massagem relaxante', categoria: 'Massagem', duracao: '1h', preco: 120 },
  { id: 10, nome: 'Drenagem linfática', categoria: 'Massagem', duracao: '1h', preco: 130 },
  { id: 11, nome: 'Manicure tradicional', categoria: 'Manicure', duracao: '1h', preco: 30 },
  { id: 12, nome: 'Esmaltação em gel', categoria: 'Manicure', duracao: '1h', preco: 60 }
];

iniciar();

async function iniciar() {
  definirDataMinima();
  configurarEventos();
  await carregarServicos();
}

function configurarEventos() {
  document.querySelector('#menuBtn').addEventListener('click', () => {
    menu.classList.toggle('aberto');
  });

  document.querySelectorAll('[data-open-modal], #abrirAgendamento').forEach(botao => {
    botao.addEventListener('click', () => abrirModal());
  });

  document.querySelector('#fecharModal').addEventListener('click', fecharModal);
  fundo.addEventListener('click', fecharModal);

  document.querySelectorAll('#menu a').forEach(link => {
    link.addEventListener('click', () => menu.classList.remove('aberto'));
  });

  document.querySelector('#filtros').addEventListener('click', evento => {
    if (evento.target.tagName !== 'BUTTON') return;

    filtroAtual = evento.target.dataset.categoria;
    document.querySelectorAll('#filtros button').forEach(botao => botao.classList.remove('ativo'));
    evento.target.classList.add('ativo');
    mostrarServicos();
  });

  form.addEventListener('submit', salvarAgendamento);
}

async function carregarServicos() {
  try {
    const resposta = await fetch('api/servicos.php');
    if (!resposta.ok) throw new Error('Erro ao buscar serviços');
    servicos = await resposta.json();
  } catch (erro) {
    servicos = servicosPadrao;
  }

  preencherSelect();
  mostrarServicos();
}

function mostrarServicos() {
  const filtrados = filtroAtual === 'todos'
    ? servicos
    : servicos.filter(servico => servico.categoria === filtroAtual);

  listaServicos.innerHTML = filtrados.map(servico => `
    <article class="card">
      <div>
        <h3>${servico.nome}</h3>
        <p>${servico.categoria} · ${servico.duracao}</p>
        <strong class="preco">R$ ${Number(servico.preco).toFixed(2).replace('.', ',')}</strong>
      </div>
      <button type="button" onclick="abrirModal(${servico.id})">agendar</button>
    </article>
  `).join('');
}

function preencherSelect() {
  selectServico.innerHTML = '<option value="">Selecione um serviço</option>';

  servicos.forEach(servico => {
    const option = document.createElement('option');
    option.value = servico.id;
    option.textContent = `${servico.nome} - R$ ${Number(servico.preco).toFixed(2).replace('.', ',')}`;
    selectServico.appendChild(option);
  });
}

function abrirModal(servicoId = '') {
  mensagem.textContent = '';
  mensagem.className = 'mensagem';
  modal.hidden = false;
  fundo.hidden = false;

  if (servicoId) {
    selectServico.value = servicoId;
  }
}

function fecharModal() {
  modal.hidden = true;
  fundo.hidden = true;
}

async function salvarAgendamento(evento) {
  evento.preventDefault();

  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }

  const dados = Object.fromEntries(new FormData(form));

  try {
    const resposta = await fetch('api/agendar.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(dados)
    });

    const resultado = await resposta.json();
    if (!resultado.sucesso) throw new Error(resultado.mensagem);

    mensagem.textContent = 'Agendamento realizado com sucesso!';
    mensagem.classList.add('sucesso');
    form.reset();
    definirDataMinima();
    setTimeout(fecharModal, 1200);
  } catch (erro) {
    salvarLocalmente(dados);
    mensagem.textContent = 'Agendamento salvo em modo demonstração.';
    mensagem.classList.add('sucesso');
    form.reset();
    definirDataMinima();
  }
}

function definirDataMinima() {
  const campoData = form.elements.data;
  const hoje = new Date().toISOString().split('T')[0];
  campoData.min = hoje;
}

function salvarLocalmente(dados) {
  const agendamentos = JSON.parse(localStorage.getItem('agendamentos_vivant') || '[]');
  agendamentos.push({ ...dados, criado_em: new Date().toISOString() });
  localStorage.setItem('agendamentos_vivant', JSON.stringify(agendamentos));
}