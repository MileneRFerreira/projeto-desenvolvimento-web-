<?php
	class forum{
		protected $con = null;

		public function __construct($con){
			$this->con = $con;
		}

		public function get_category(){
			$query = $this->con->prepare("SELECT * FROM categorias ORDER BY id ASC");
			$query->execute();
			$get = $query->get_result();

			if($get->num_rows > 0){
				while($dados_categorias = $get->fetch_array()){
					global $categoryId;
					$categoryId = $dados_categorias['id'];
					echo "<div class='table-responsive'>
						<table class='table topics'>
							<tr>
								<th class='title'><img src='images/icon-category.png'> {$dados_categorias['categoria']}</th>
								<th class='center'>Tópicos</th>
								<th class='center'>Respostas</th>
								<th class='center'>Última Resposta</th>
							</tr>";

					$this->get_forums($categoryId);
					echo "</tr></table></div>";

				}
			}
		}

		public function strReplace($str){
		return str_replace(' ', '-', preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $str)));
		}

		function get_data(){
			date_default_timezone_set('America/Sao_Paulo');
			return date('d-m-Y H:i:s');
		}	

		function calculaDias($diaX,$diaY){
		$data1 = new DateTime($diaX);
		$data2 = new DateTime($diaY); 

		$intervalo = $data1->diff($data2); 


		if($intervalo->y > 1){
		  return $intervalo->y." Anos atrás";
		}elseif($intervalo->y == 1){
		  return $intervalo->y." Ano atrás";
		}elseif($intervalo->m > 1){
		  return $intervalo->m." Meses atrás";
		}elseif($intervalo->m == 1){
		  return $intervalo->m." Mês atrás";
		}elseif($intervalo->d > 1){
		  return $intervalo->d." Dias atrás";
		}elseif($intervalo->d == 1){
		  return $intervalo->d." Dia atrás";
		}elseif($intervalo->h > 1){
		  return $intervalo->h." Horas atrás";
		}elseif($intervalo->h == 1){
		  return $intervalo->h." Hora atrás";
		}elseif($intervalo->i > 1 && $intervalo->i < 59){
		  return $intervalo->i." Minutos atrás";
		}elseif($intervalo->i == 1){
		  return $intervalo->i." Minuto atrás";
		}elseif($intervalo->s < 60 && $intervalo->i <= 0){
		  return $intervalo->s." Segundo atrás";
		}
	}

		public function get_forums($id){
			$query = $this->con->prepare("SELECT * FROM forums WHERE categoria = ?");
			$query->bind_param("i", $id);
			$query->execute();
			$get = $query->get_result();

			if($get->num_rows > 0){
				while($dados_forums = $get->fetch_array()){
					switch($dados_forums['status']){
						case 0:
							$class = 'locked';
							$img = 'images/icon-locked.png';
						break;

						case 1:
							$class = 'open';
							$img = 'images/icon-message.png';
						break;
					}
			echo "<tr>
  					<td>
		  				<div class='media'>
		  					<img class='mr-3 {$class}' src='{$img}'>
		  					<div class='media-body'>
		  						<label><a href='forum/{$dados_forums['id']}-".strtolower($this->strReplace($dados_forums['titulo']))."'>{$dados_forums['titulo']}</a><br>
		  						<span class='small'>{$dados_forums['descricao']}</span></label>
		  					</div>
		  				</div>
		  			</td>";

		  			$this->get_totaltopics($dados_forums['id'], $id);
		  			$this->get_totalrespostas($dados_forums['id'], $id);
		  			$this->get_lastPost($dados_forums['id'], $id);
		  			
				}
			}	
		}


		public function get_totaltopics($id, $id_categoria){
			$query = $this->con->prepare("SELECT * FROM topicos WHERE forum = ? AND categoria = ?");
			$query->bind_param("ss", $id, $id_categoria);
			$query->execute();
			$total = $query->get_result()->num_rows;

			echo "<td  class='center'>{$total}</td>";
		}

		public function get_totalrespostas($id){
			$query = $this->con->prepare("SELECT * FROM respostas WHERE id_topico = ? AND id_categoria = ?");
			$query->bind_param("ss", $id, $id_categoria);
			$query->execute();
			$total = $query->get_result()->num_rows;

			echo "<td class='center'>{$total}</td>";
		}

		public function get_lastPost($id_forum, $id_categoria){
			$query = $this->con->prepare("SELECT * FROM respostas WHERE id_forum = ? AND id_categoria = ? ORDER BY id DESC LIMIT 1");
			$query->bind_param("ss", $id_forum, $id_categoria);
			$query->execute();
			$get = $query->get_result();
			$total = $get->num_rows;
			$dados = $get->fetch_assoc();


			echo "<td class='center'>";
			if($total > 0){
			$sql = $this->con->prepare("SELECT * FROM topicos WHERE id = ?");
			$sql->bind_param("s", $dados['id_topico']);
			$sql->execute();
			$dadosT = $sql->get_result()->fetch_assoc(); 


			$stmt = $this->con->prepare("SELECT * FROM usuarios WHERE usuario = ?");
			$stmt->bind_param("s", $dados['postador']);
			$stmt->execute();
			$dadosU = $stmt->get_result()->fetch_assoc();

			switch ($dadosU['nivel']) {
				case 0:
					$class = 'text-primary';
				break;

				case 1:
					$class = 'type-moderator';
				break;

				case 2:
					$class = 'type-admin';
				break;
			}
			echo "<span class='title'><a href='topico/{$dadosT['id']}-".strtolower($this->strReplace($dadosT['titulo']))."'>{$dadosT['titulo']}</a></span>
					<br> por <span class='{$class}'><a href='perfil/'>{$dadosU['nome']}</a></span>
					<br><span class='small'>{$this->calculaDias($dados['data'], $this->get_data())}</span>";
			echo "</td>";
		}
	}

	public function get_forum($titulo){
		$stmt = $this->con->prepare("SELECT * FROM topicos WHERE forum = ?");
		$stmt->bind_param("s", $titulo);
		$stmt->execute();
		$get = $stmt->get_result();
		$total = $get->num_rows;


		if($total > 0){
			echo "<div class='table-responsive'> <table class='table topics'>";
			echo "<tr>";
			echo "<th>Título</th>";
			echo "<th class='options-topics'>Informações <span class='float-right'><a href='#' class='btn btn-primary btn-sm'>Criar Tópico</a></span></th>";
			echo "</tr>";
			while($dados = $get->fetch_array()){
			echo "
				<tr>
				<td>
					<a href='topico/{$dados['id']}-".strtolower($this->strReplace($dados['titulo']))."'>{$dados['titulo']}</a>
					<br><small>Criado por <a href='perfil/{$dados['postador']}'>User_1</a>, 5 horas atrás</small>
				</td>
				<td>
					<span class='small'>{$this->get_total_respostas($dados['id'], $dados['forum'], $dados['categoria'])} Respostas</span>
					<br>
					<span class='small'>{$dados['visitas']} Visualizações</span>
				</td>
				</tr>";
				}
			echo "</table></div>";
			}else{
				echo "<div class='table-responsive'><table class='table topics'>";
				echo "<tr>";
				echo "<th>Título</th>";
				echo "<th class='options-topics'>Informações <span class='float-right'><a href='#' class='btn btn-primary btn-sm'>Criar Tópico</a></span></th>";
				echo "</tr>";
				echo "<tr><td class='alert alert-warning' align='center'>Esse fórum não possui tópicos</div></td>";
				echo "<td></td></table></div>";
			}
		}

	public function get_total_respostas($id, $id_forum, $id_categoria){
		$stmt = $this->con->prepare("SELECT * FROM respostas WHERE id_topico = ? AND id_forum = ? AND id_categoria = ?");
		$stmt->bind_param("sss", $id, $id_forum, $id_categoria);
		$stmt->execute();
		$get = $stmt->get_result();
		return $get->num_rows;
	}
	
	public function get_idCategoria($id){
		$stmt = $this->con->prepare("SELECT * FROM topicos WHERE forum = ?");
		$stmt->bind_param("s", $id);
		$stmt->execute();
		$get = $stmt->get_result();
		$dados = $get->fetch_assoc();

		return $dados['categoria'];
	}


	public function get_topic($id){
		$stmt = $this->con->prepare("SELECT * FROM topicos WHERE id = ?");
		$stmt->bind_param("s", $id);
		$stmt->execute();
		$get = $stmt->get_result();

		if($get->num_rows > 0){
			$dados = $get->fetch_assoc();

			$sql = $this->con->prepare("SELECT * FROM usuarios WHERE usuario = ?");
			$sql->bind_param("s", $dados['postador']);
			$sql->execute();
			$dadosU = $sql->get_result()->fetch_assoc();

			switch ($dadosU['nivel']) {
				case 0:
					$class = 'color: blue;';
					$ntipo = 'Membro';
				break;

				case 1:
					$class = 'color: limegreen;';
					$ntipo = 'Moderador';
				break;

				case 2:
					$class = 'color: red;';
					$ntipo = 'Administrador';
				break;
			}

			$valor = 'location.href="curtir/'.$dados['id'].'/'.$dados['curtidas'].'"';

			echo "<div class='table-responsive'>
			<table class='table'>
				<tr>
					<th class='w-15 p-3'>{$dadosU['nome']}</th>
					<th class='w-75 p-3'>Postado {$this->calculaDias($dados['data'], $this->get_data())}</th>
					<th></th>
				</tr>

				<tr>
					<td>
						<img src='{$dadosU['foto']}' class='img-fluid'><br>
						<p class='small' style='{$class}'>{$ntipo}</p>
					</td>
					<td>{$dados['mensagem']}</td>
					<td><i class='fas fa-thumbs-up' onclick='{$valor}'></i> <span class='badge badge-dark'>{$dados['curtidas']}</span></td>
				</tr>
		</table></div>
		";
		}
		$this->update_views($dados['id'], $dados['visitas']);
		$this->get_respostas($dados['id'], $dados['forum'], $dados['categoria'], $dados['status']);
	}

	public function update_views($id, $visitas){
		$newvistas = ($visitas) + 1;

		$sql = $this->con->prepare("UPDATE topicos SET visitas = ? WHERE id = ?");
		$sql->bind_param("ss", $newvistas, $id);
		$sql->execute();

		if($sql->affected_rows > 0){
			return true;
		}else{
			return false;
		}
	}


	public function get_respostas($id_topico, $id_forum, $id_categoria, $status){
		$stmt = $this->con->prepare("SELECT * FROM respostas WHERE id_topico = ?");
		$stmt->bind_param("s", $id_topico);
		$stmt->execute();
		$get = $stmt->get_result();

		if($get->num_rows > 0){
			while($dados = $get->fetch_array()){

			$sql = $this->con->prepare("SELECT * FROM usuarios WHERE usuario = ?");
			$sql->bind_param("s", $dados['postador']);
			$sql->execute();
			$dadosU = $sql->get_result()->fetch_assoc();
			

			switch ($dadosU['nivel']) {
				case 0:
					$class = 'color: blue;';
					$ntipo = 'Membro';
				break;

				case 1:
					$class = 'color: limegreen;';
					$ntipo = 'Moderador';
				break;

				case 2:
					$class = 'color: red;';
					$ntipo = 'Administrador';
				break;
			}

			$valor = 'location.href="curtir_resposta/'.$dados['id'].'/'.$dados['curtidas'].'"';

			echo "<div class='table-responsive'>
			<table class='table'>
				<tr>
					<th class='w-15 p-3'>{$dadosU['nome']}</th>
					<th class='w-75 p-3'>Postado {$this->calculaDias($dados['data'], $this->get_data())}</th>
					<th></th>
				</tr>

				<tr>
					<td>
						<img src='{$dadosU['foto']}' class='img-fluid'><br>
						<p class='small' style='{$class}'>{$ntipo}</p>
					</td>
					<td>{$dados['resposta']}</td>
					<td><i class='fas fa-thumbs-up' onclick='{$valor}'></i> <span class='badge badge-dark'>{$dados['curtidas']}</span></td>
				</tr>
		</table></div>
		";
		}
	}

	if($status == 1){
			$this->reply_topic("thsales061", $id_topico, $id_forum, $id_categoria);
		}else{
			echo "<div class='alert alert-danger'><i class='fas fa-lock'></i> Tópico fechado. Você não pode comentar!</div>";
		}	
}

	public function reply_topic($postador, $id_topico, $id_forum, $id_categoria){
		echo "<div class='table-responsive'><table class='table'>
				<tr>
				<td class='reply-width'>
					<img src='' class='img-fluid reply-img'>
				</td>
				<td>
				<form method='POST'>
					<textarea name='resposta' class='form-control'></textarea>
					<br><input type='submit' value='Responder' class='btn btn-primary btn-sm float-right'>
					<input type='hidden' name='env' value='resp'>
				</form>
				</td>
				</tr>
			</table></div>
		";

	if($_POST['env'] && $_POST['env'] == "resp"){
		$resposta = addslashes($_POST['resposta']);
		$data = $this->get_data();
		
		$sql = $this->con->prepare("INSERT INTO respostas (id_topico, id_forum, id_categoria, postador, resposta, data) VALUES (?, ?, ?, ?, ?, ?)");
		$sql->bind_param("ssssss", $id_topico, $id_forum, $id_categoria, $postador, $resposta, $data);
		$sql->execute();

		if($sql->affected_rows > 0){
			return true;
		}else{
			return false;
		}
		}	
	}

	public function update_likes_topicos($id, $curtidas){
		$likesAtualizados = ($curtidas) + 1;

		$stmt = $this->con->prepare("UPDATE topicos SET curtidas = ? WHERE id = ?");
		$stmt->bind_param("ss", $likesAtualizados, $id);
		$stmt->execute();


		if($stmt->affecte_rows > 0){
			return true;
		}
		else{
			echo $stmt->affecte_rows;
		}
	}


	public function update_likes_respostas($id, $curtidas){
		$likesAtualizados = ($curtidas) + 1;

		$stmt = $this->con->prepare("UPDATE respostas SET curtidas = ? WHERE id = ?");
		$stmt->bind_param("ss", $likesAtualizados, $id);
		$stmt->execute();


		if($stmt->affecte_rows > 0){
			return true;
		}
		else{
			echo $stmt->affecte_rows;
		}
	}

	public function get_chatbox(){

		echo "
		<div class='row'>
				<div class='col-sm-12'>
					<div class='alert alert-success'>
						<h5 align='left'>Regras do ChatBox</h5>
						<p>
							1ª Flood {mensagens seguidas}: Evite floodar.<br>
							2ª Links: Proibido links de outros sites.<br>
							3ª Conversas: Modere nas conversas.<br>
							4ª Somente é permitido links de imagens com o link direto.<br>
							5ª Vídeos: Somente videos relacionados ao assunto do momento.<br>
							6ª Banimento: Postagens de links com conteúdo pornográfico, mensagens ofensivas (palavrões),mensagens preconceituosas, acarretará em avaliação do moderador e pode causar expulsão do fórum..
						</p>
					</div>
				</div>
				<div class='col-sm-9'>
					<div class='chatbox'>
					<ul>
						<li>
							<img src='images/me.jpg'> 
							<span class='at'>@</span> 
							<span class='user'>User_1</span>
							<span class='message'>Yes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yes </span>
						</li>

						<li>
							<img src='images/me.jpg'> 
							<span class='at'>@</span> 
							<span class='admin'>User_2</span>
							<span class='message'>Claro que sim... Claro que não... Claro que sim... Claro que não...Claro que sim... Claro que não...Claro que sim... Claro que não...Claro que sim... Claro que não...Claro que sim... Claro que não...</span>
						</li>

						<li>
							<img src='images/me.jpg'> 
							<span class='at'>@</span> 
							<span class='mod'>User_1</span>
							<span class='message'>Yes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yesYes baby... yes </span>
						</li>
					</ul>
				</div>
		   	</div>
		   		<div class='col-sm-3'>
		   			<form method='POST'>
		   				<textarea class='form-control' rows='4'></textarea>
		   				<div class='options' align='center'>
			   				<button type='submit' class='btn btn-success btn-sm'>Enviar</button>
			   				<button type='reset' class='btn btn-primary btn-sm'>Limpar</button>
		   				</div>
		   			</form>
		   		</div>
			</div>
		";

	}

		
}
?>
