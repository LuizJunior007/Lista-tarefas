<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);

		$routes['auth'] = array(
			'route' => '/auth',
			'controller' => 'indexController',
			'action' => 'auth'
		);

		$routes['dashboard'] = array(
			'route' => '/dashboard',
			'controller' => 'admController',
			'action' => 'dashboard'
		);

		$routes['tarefas'] = array(
			'route' => '/tarefas',
			'controller' => 'admController',
			'action' => 'tarefas'
		);

		$routes['nova_tarefa'] = array(
			'route' => '/nova_tarefa',
			'controller' => 'admController',
			'action' => 'nova_tarefa'
		);

		$routes['editar_tarefa'] = array(
			'route' => '/editar_tarefa',
			'controller' => 'admController',
			'action' => 'editar_tarefa'
		);

		$routes['concluir_tarefa'] = array(
			'route' => '/concluir_tarefa',
			'controller' => 'admController',
			'action' => 'concluir_tarefa'
		);

		$routes['pesquisar'] = array(
			'route' => '/pesquisar',
			'controller' => 'admController',
			'action' => 'pesquisar'
		);

		$routes['classificar'] = array(
			'route' => '/classificar',
			'controller' => 'admController',
			'action' => 'classificar'
		);

		$routes['grafico'] = array(
			'route' => '/grafico',
			'controller' => 'admController',
			'action' => 'grafico'
		);

		$routes['alterar_foto'] = array(
			'route' => '/alterar_foto',
			'controller' => 'admController',
			'action' => 'alterar_foto'
		);

		$routes['alterar_nome'] = array(
			'route' => '/alterar_nome',
			'controller' => 'admController',
			'action' => 'alterar_nome'
		);

		$routes['redefinir_senha'] = array(
			'route' => '/redefinir_senha',
			'controller' => 'indexController',
			'action' => 'redefinir_senha'
		);

		$routes['alterar_senha'] = array(
			'route' => '/alterar_senha',
			'controller' => 'indexController',
			'action' => 'alterar_senha'
		);

		$routes['nova_senha'] = array(
			'route' => '/nova_senha',
			'controller' => 'indexController',
			'action' => 'nova_senha'
		);

		$routes['cadastrar_usuario'] = array(
			'route' => '/cadastrar_usuario',
			'controller' => 'indexController',
			'action' => 'cadastrar_usuario'
		);

		$routes['remover_tarefa'] = array(
			'route' => '/remover_tarefa',
			'controller' => 'admController',
			'action' => 'remover_tarefa'
		);

		$routes['select_tarefa'] = array(
			'route' => '/select_tarefa',
			'controller' => 'admController',
			'action' => 'select_tarefa'
		);

		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'admController',
			'action' => 'sair'
		);

		$this->setRoutes($routes);
	}
	
}

?>