<?php
// Variaveis do sistema

const RELOAD_WITHOUT_CACHE = 1;
const MANUTENCAO = 0;
const MAIL_HOST = 'smtp.foxthree.com.br';
const MAIL_USER = 'sis@foxthree.com.br';
const MAIL_PASS = 'f0x1990';
const MAIL_NAME = 'FOX3';
const JOB_STATUS_PENDENTE = 1;
const JOB_STATUS_EM_ANDAMENTO = 2;
const JOB_STATUS_AGUARDANDO_CLIENTE = 3;
const JOB_STATUS_APROVACAO = 4;
const JOB_STATUS_FINALIZADO = 5;
CONST ID_GRUPO_DEV = 1;
CONST ID_GRUPO_REDACAO = 2;
CONST ID_GRUPO_ARTE = 3;
CONST ID_GRUPO_GERENCIA = 4;
CONST ID_GRUPO_ATENDIMENTO = 5;
CONST ID_GRUPO_SOCIAL = 6;

/* CRIAÇÃO DE TAREFAS - STEPS */
const EMAILS_MEU_NEGOCIO = array(
  'thiago@foxthree.com.br', 
  'atendimento@foxthree.com.br'
);

const GRUPO_JOB_STEPS = 4;

const VERIF_FB_JOB = array(
  'Verificar sobre, telefone, e-mail',
  'Verificar site',
  'Verificar imagem do avatar',
  'Verificar capa',
  'Verificar mensagens não respondidas'
);

const VERIF_IG_JOB = array(
  'Verificar bio, telefone, e-mail',
  'Verificar site',
  'Verificar imagem do avatar',
  'Verificar mensagens não respondidas',
);

const VERIF_SITE_JOB = array(
  'Verificar site',
  'Verificar banners',
  'Verificar links e textos',
  'Verificar mensagens não respondidas'
);

// NOTIFICAÇÕES GERAIS
const NOTIF_GERAIS = array(
  'end_job' => array(
    'name'=>'Tarefa finalizada',
    'key'=>'end_job',
    'log'=>'finalizar_job',
  ),
  'update_job_aprovacao' => array(
    'name'=>'Tarefa alterada para "Aguardando aprovação"',
    'key'=>'update_job_aprovacao',
    'log'=>'alterar_status_job,alterar_status_roteiro_job_integracao',
  ),
);