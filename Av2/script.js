// Elementos usados em mais de uma função
const listaServicos = document.querySelector('#listaServicos');
const modalAgendamento = document.querySelector('#modalAgendamento');
const modalLogin = document.querySelector('#modalLogin');
const modalHistorico = document.querySelector('#modalHistorico');
const modalFundo = document.querySelector('#modalFundo');
const formAgendamento = document.querySelector('#formAgendamento');
const formLogin = document.querySelector('#formLogin');
const formCadastro = document.querySelector('#formCadastro');
const mensagemAgendamento = document.querySelector('#mensagem');
const menu = document.querySelector('#menu');

// Dados mantidos enquanto a página está aberta
let servicos = [];
let profissionais = [];
let carrinho = [];
let usuario = null;
let categoriaAtual = 'todos';
let destinoDepoisDoLogin = '';

iniciar();

async function iniciar() {
  configurarEventos();
  definirDataMinima();

  await carregarServicos();
  await carregarProfissionais();
  await verificarSessao();

  atualizarUsuario();
  mostrarCarrinho();
}

function configurarEventos() {
  document.querySelector('#menuBtn').addEventListener('click', () => {
    menu.classList.toggle('aberto');
  });

  document.querySelectorAll('[data-open-modal], #abrirAgendamento').forEach(botao => {
    botao.addEventListener('click', abrirAgendamento);
  });

  document.querySelector('#abrirLogin').addEventListener('click', () => abrirModal(modalLogin));
  document.querySelector('#abrirHistorico').addEventListener('click', abrirHistorico);
  document.querySelector('#sairConta').addEventListener('click', sair);

  document.querySelectorAll('#menu a').forEach(link => {
    link.addEventListener('click', () => menu.classList.remove('aberto'));
  });
  document.querySelector('#continuarEscolhendo').addEventListener('click', fecharModais);

  document.querySelectorAll('[data-fechar-modal]').forEach(botao => {
    botao.addEventListener('click', fecharModais);
  });

  modalFundo.addEventListener('click', fecharModais);

  document.querySelector('#filtros').addEventListener('click', evento => {
    const botao = evento.target.closest('[data-categoria]');
    if (!botao) return;

    categoriaAtual = botao.dataset.categoria;

    document.querySelectorAll('#filtros button').forEach(item => {
      item.classList.remove('ativo');
    });

    botao.classList.add('ativo');
    mostrarServicos();
  });

  listaServicos.addEventListener('click', evento => {
    const botao = evento.target.closest('[data-servico-id]');
    if (botao) alternarServico(Number(botao.dataset.servicoId));
  });

  document.querySelector('#itensCarrinho').addEventListener('click', evento => {
    const botao = evento.target.closest('[data-remover-id]');
    if (botao) alternarServico(Number(botao.dataset.removerId));
  });

  document.querySelectorAll('[data-aba]').forEach(botao => {
    botao.addEventListener('click', () => trocarAba(botao.dataset.aba));
  });

  formAgendamento.addEventListener('submit', salvarAgendamento);
  formLogin.addEventListener('submit', entrar);
  formCadastro.addEventListener('submit', cadastrar);
}

// Busca os serviços cadastrados no MySQL
async function carregarServicos() {
  try {
    servicos = await requisicao('servicos.php');
    mostrarServicos();
  } catch (erro) {
    listaServicos.innerHTML = `
      <p class="erro-carregamento">
        ${escaparHtml(erro.message)}<br>
        Inicie o Apache e o MySQL no XAMPP e abra o projeto pelo localhost.
      </p>
    `;
  }
}

// Preenche o select de profissionais
async function carregarProfissionais() {
  const select = document.querySelector('#profissional');

  try {
    profissionais = await requisicao('profissionais.php');

    profissionais.forEach(profissional => {
      const opcao = document.createElement('option');
      opcao.value = profissional.id;
      opcao.textContent = profissional.nome + ' - ' + profissional.especialidade;
      select.appendChild(opcao);
    });
  } catch (erro) {
    console.log('Não foi possível carregar os profissionais.');
  }
}

async function verificarSessao() {
  try {
    const resposta = await requisicao('sessao.php');
    usuario = resposta.usuario;
  } catch (erro) {
    usuario = null;
  }
}

function mostrarServicos() {
  let lista = servicos;

  if (categoriaAtual !== 'todos') {
    lista = servicos.filter(servico => servico.categoria === categoriaAtual);
  }

  listaServicos.innerHTML = lista.map(servico => {
    const estaNoCarrinho = carrinho.includes(Number(servico.id));

    return `
      <article class="card">
        <div>
          <span class="categoria-servico">${escaparHtml(servico.categoria)}</span>
          <h3>${escaparHtml(servico.nome)}</h3>
          <p>Duração: ${escaparHtml(servico.duracao)}</p>
        </div>
        <div class="card-rodape">
          <strong class="preco">${formatarDinheiro(servico.preco)}</strong>
          <button class="${estaNoCarrinho ? 'adicionado' : ''}"
                  type="button"
                  data-servico-id="${servico.id}">
            ${estaNoCarrinho ? 'Remover' : 'Adicionar'}
          </button>
        </div>
      </article>
    `;
  }).join('');
}

// Adiciona ou remove um serviço do array carrinho
function alternarServico(id) {
  if (carrinho.includes(id)) {
    carrinho = carrinho.filter(servicoId => servicoId !== id);
  } else {
    carrinho.push(id);
  }

  mostrarServicos();
  mostrarCarrinho();
}

function servicosEscolhidos() {
  return carrinho
    .map(id => servicos.find(servico => Number(servico.id) === id))
    .filter(servico => servico);
}

function mostrarCarrinho() {
  const itens = servicosEscolhidos();
  const lista = document.querySelector('#itensCarrinho');
  let total = 0;

  itens.forEach(item => {
    total += Number(item.preco);
  });

  document.querySelector('#contadorCarrinho').textContent = itens.length;
  document.querySelector('#totalCarrinho').textContent = formatarDinheiro(total);

  if (itens.length === 0) {
    lista.innerHTML = '<p class="carrinho-vazio">O carrinho está vazio.</p>';
  } else {
    lista.innerHTML = itens.map(item => `
      <div class="item-carrinho">
        <div>
          <h4>${escaparHtml(item.nome)}</h4>
          <p>${escaparHtml(item.categoria)} - ${escaparHtml(item.duracao)}</p>
        </div>
        <strong>${formatarDinheiro(item.preco)}</strong>
        <button class="remover-item" type="button" data-remover-id="${item.id}">Remover</button>
      </div>
    `).join('');
  }

  const botaoConfirmar = document.querySelector('#confirmarAgendamento');
  botaoConfirmar.disabled = itens.length === 0;
  botaoConfirmar.textContent = usuario ? 'Confirmar agendamento' : 'Entrar para confirmar';
  document.querySelector('#avisoLoginAgendamento').hidden = Boolean(usuario);
}

function abrirAgendamento() {
  limparMensagem(mensagemAgendamento);
  mostrarCarrinho();
  abrirModal(modalAgendamento);
}

// Envia os IDs do carrinho e os dados do formulário para o PHP
async function salvarAgendamento(evento) {
  evento.preventDefault();

  if (carrinho.length === 0) {
    mostrarMensagem(mensagemAgendamento, 'Adicione pelo menos um serviço.', false);
    return;
  }

  if (!usuario) {
    destinoDepoisDoLogin = 'agendamento';
    trocarAba('login');
    abrirModal(modalLogin);
    mostrarMensagem(document.querySelector('#mensagemLogin'), 'Entre para confirmar o agendamento.', false);
    return;
  }

  const formulario = new FormData(formAgendamento);
  const dados = {
    servicos: carrinho,
    profissional_id: formulario.get('profissional_id') || null,
    data: formulario.get('data'),
    hora: formulario.get('hora'),
    forma_pagamento: formulario.get('forma_pagamento')
  };

  try {
    const resposta = await requisicao('agendar.php', {
      method: 'POST',
      body: JSON.stringify(dados)
    });

    mostrarMensagem(mensagemAgendamento, resposta.mensagem, true);
    carrinho = [];
    formAgendamento.reset();
    definirDataMinima();
    mostrarServicos();
    mostrarCarrinho();
  } catch (erro) {
    mostrarMensagem(mensagemAgendamento, erro.message, false);
  }
}

async function entrar(evento) {
  evento.preventDefault();
  const mensagem = document.querySelector('#mensagemLogin');

  try {
    const resposta = await requisicao('login.php', {
      method: 'POST',
      body: JSON.stringify(Object.fromEntries(new FormData(formLogin)))
    });

    usuario = resposta.usuario;
    formLogin.reset();
    atualizarUsuario();
    mostrarCarrinho();
    continuarDepoisDoLogin();
  } catch (erro) {
    mostrarMensagem(mensagem, erro.message, false);
  }
}

async function cadastrar(evento) {
  evento.preventDefault();
  const mensagem = document.querySelector('#mensagemCadastro');

  try {
    const resposta = await requisicao('cadastro.php', {
      method: 'POST',
      body: JSON.stringify(Object.fromEntries(new FormData(formCadastro)))
    });

    usuario = resposta.usuario;
    formCadastro.reset();
    atualizarUsuario();
    mostrarCarrinho();
    continuarDepoisDoLogin();
  } catch (erro) {
    mostrarMensagem(mensagem, erro.message, false);
  }
}

function continuarDepoisDoLogin() {
  const destino = destinoDepoisDoLogin;
  destinoDepoisDoLogin = '';
  fecharModais();

  if (destino === 'agendamento') abrirAgendamento();
  if (destino === 'historico') abrirHistorico();
}

async function sair() {
  try {
    await requisicao('logout.php', { method: 'POST', body: '{}' });
    usuario = null;
    atualizarUsuario();
    mostrarCarrinho();
    fecharModais();
  } catch (erro) {
    alert(erro.message);
  }
}

async function abrirHistorico() {
  if (!usuario) {
    destinoDepoisDoLogin = 'historico';
    trocarAba('login');
    abrirModal(modalLogin);
    mostrarMensagem(document.querySelector('#mensagemLogin'), 'Entre para consultar o histórico.', false);
    return;
  }

  const lista = document.querySelector('#listaHistorico');
  lista.innerHTML = '<p class="historico-vazio">Carregando...</p>';
  abrirModal(modalHistorico);

  try {
    const resposta = await requisicao('historico.php');
    const agendamentos = resposta.agendamentos;

    if (agendamentos.length === 0) {
      lista.innerHTML = '<p class="historico-vazio">Nenhum agendamento encontrado.</p>';
      return;
    }

    lista.innerHTML = agendamentos.map(item => `
      <article class="historico-item">
        <span class="status-agendamento">${escaparHtml(item.status)}</span>
        <h3>${formatarData(item.data_agendamento)} às ${formatarHora(item.hora_agendamento)}</h3>
        <p><strong>Serviços:</strong> ${escaparHtml(item.servicos)}</p>
        <p><strong>Profissional:</strong> ${escaparHtml(item.profissional || 'Sem preferência')}</p>
        <p><strong>Pagamento:</strong> ${nomePagamento(item.forma_pagamento)}</p>
        <p><strong>Total:</strong> ${formatarDinheiro(item.valor_total)}</p>
      </article>
    `).join('');
  } catch (erro) {
    lista.innerHTML = '';
    mostrarMensagem(document.querySelector('#mensagemHistorico'), erro.message, false);
  }
}

function atualizarUsuario() {
  const areaDeslogada = document.querySelector('#areaDeslogada');
  const areaLogada = document.querySelector('#areaLogada');
  const botaoLogin = document.querySelector('#abrirLogin');

  if (usuario) {
    areaDeslogada.hidden = true;
    areaLogada.hidden = false;
    botaoLogin.textContent = 'Minha conta';
    document.querySelector('#nomeUsuario').textContent = usuario.nome;
    document.querySelector('#emailUsuario').textContent = usuario.email;
    document.querySelector('#statusAgendamento').textContent = 'Olá, ' + usuario.nome.split(' ')[0] + '. Complete seu agendamento.';
  } else {
    areaDeslogada.hidden = false;
    areaLogada.hidden = true;
    botaoLogin.textContent = 'Entrar';
    document.querySelector('#statusAgendamento').textContent = 'Revise o carrinho e complete o agendamento.';
  }
}

function trocarAba(aba) {
  document.querySelectorAll('[data-aba]').forEach(botao => {
    botao.classList.toggle('ativa', botao.dataset.aba === aba);
  });

  document.querySelector('#painelLogin').hidden = aba !== 'login';
  document.querySelector('#painelCadastro').hidden = aba !== 'cadastro';
}

function abrirModal(modal) {
  fecharModais();
  modal.hidden = false;
  modalFundo.hidden = false;
  document.body.classList.add('modal-aberto');
  menu.classList.remove('aberto');
}

function fecharModais() {
  document.querySelectorAll('.modal').forEach(modal => modal.hidden = true);
  modalFundo.hidden = true;
  document.body.classList.remove('modal-aberto');
}

// Função comum para acessar os arquivos PHP da pasta api
async function requisicao(arquivo, opcoes = {}) {
  let resposta;

  try {
    resposta = await fetch('api/' + arquivo, {
      headers: { 'Content-Type': 'application/json' },
      ...opcoes
    });
  } catch (erro) {
    throw new Error('Não foi possível acessar o servidor.');
  }

  const texto = await resposta.text();
  let dados;

  try {
    dados = JSON.parse(texto);
  } catch (erro) {
    throw new Error('O PHP retornou uma resposta inválida.');
  }

  if (!resposta.ok || dados.sucesso === false) {
    if (resposta.status === 401) {
      usuario = null;
      atualizarUsuario();
      mostrarCarrinho();
    }

    throw new Error(dados.mensagem || 'Não foi possível concluir a operação.');
  }

  return dados;
}

function definirDataMinima() {
  const agora = new Date();
  const ano = agora.getFullYear();
  const mes = String(agora.getMonth() + 1).padStart(2, '0');
  const dia = String(agora.getDate()).padStart(2, '0');
  formAgendamento.elements.data.min = `${ano}-${mes}-${dia}`;
}

function formatarDinheiro(valor) {
  return Number(valor).toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  });
}

function formatarData(data) {
  const partes = data.split('-');
  return partes[2] + '/' + partes[1] + '/' + partes[0];
}

function formatarHora(hora) {
  return hora.slice(0, 5);
}

function nomePagamento(forma) {
  const nomes = {
    credito: 'Cartão de crédito',
    debito: 'Cartão de débito',
    pix: 'Pix',
    dinheiro: 'Dinheiro'
  };

  return nomes[forma] || forma;
}

function mostrarMensagem(elemento, texto, sucesso) {
  elemento.textContent = texto;
  elemento.className = sucesso ? 'mensagem sucesso' : 'mensagem erro';
}

function limparMensagem(elemento) {
  elemento.textContent = '';
  elemento.className = 'mensagem';
}

// Evita que textos vindos do banco sejam interpretados como HTML
function escaparHtml(texto) {
  return String(texto)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}
