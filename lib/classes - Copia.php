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
					echo "<table class='table topics'>
							<tr>
								<th class='title'><img src='images/icon-category.png'> {$dados_categorias['categoria']}</th>
								<th class='center'>Tópicos</th>
								<th class='center'>Posts</th>
								<th class='center'>Última Publicação</th>
							</tr>";

					$this->get_forums($categoryId);
					echo "</tr></table>";

				}
			}
		}

		function strReplace($str){
		return str_replace(' ', '-', preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $str)));
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

		  			$this->get_totaltopics($dados_forums['id']);
		  			$this->get_totalposts($dados_forums['id']);
		  			$this->get_lastPost($dados_forums['id']);
		  			
				}
			}	
		}


		public function get_totaltopics($id){
			$query = $this->con->prepare("SELECT * FROM topicos WHERE forum = ?");
			$query->bind_param("s", $id);
			$query->execute();
			$total = $query->get_result()->num_rows;

			echo "<td  class='center'>{$total}</td>";
		}

		public function get_totalposts($id){
			$query = $this->con->prepare("SELECT * FROM posts WHERE topico = ?");
			$query->bind_param("s", $id);
			$query->execute();
			$total = $query->get_result()->num_rows;

			echo "<td class='center'>{$total}</td>";
		}

		public function get_lastPost($id){
			$query = $this->con->prepare("SELECT * FROM posts WHERE topico = ? ORDER BY id DESC LIMIT 1");
			$query->bind_param("s", $id);
			$query->execute();
			$get = $query->get_result();
			$total = $get->num_rows;
			$dados = $get->fetch_assoc();


			echo "<td class='center'>";
			if($total > 0){
			$sql = $this->con->prepare("SELECT * FROM topicos WHERE id = ?");
			$sql->bind_param("s", $dados['topico']);
			$sql->execute();
			$dadosT = $sql->get_result()->fetch_assoc(); 


			$stmt = $this->con->prepare("SELECT * FROM usuarios WHERE usuario = ?");
			$stmt->bind_param("s", $dadosT['postador']);
			$stmt->execute();
			$dadosU = $stmt->get_result()->fetch_assoc();

			switch ($dadosU['nivel']) {
				case 0:
					$class = 'type-user';
				break;

				case 1:
					$class = 'type-moderator';
				break;

				case 2:
					$class = 'type-admin';
				break;
			}
			echo "<span class='title'><a href='topic/{$dadosT['id']}-".strtolower($this->strReplace($dados['titulo']))."'>{$dadosT['titulo']}</a></span>
					<br> por <span class='{$class}'><a href='perfil/'>{$dadosU['nome']}</a></span>
					<br><span class='small'>{$dadosT['data']}</span>";
			echo "</td>";
		}
	}

	function get_forum($titulo){
		$stmt = $this->con->prepare("SELECT * FROM topicos WHERE forum = ?");
		$stmt->bind_param("s", $titulo);
		$stmt->execute();
		$get = $stmt->get_result();
		$total = $get->num_rows;


		if($total > 0){
			echo "<table class='table topics title'>";
			echo "<tr>";
			echo "<th>Título</th>";
			echo "<th>Informações</th>";
			echo "</tr>";
			while($dados = $get->fetch_array()){
			echo "
				<tr>
				<td>
					<a href='topico/{$dados['id']}-".strtolower($this->strReplace($dados['titulo']))."'>{$dados['titulo']}</a>
					<br><small>Criado por <a href='perfil/{$dados['postador']}'>User_1</a>, 5 horas atrás</small>
				</td>
				<td>
					<span class='small'>{$this->get_total_respostas($dados['id'])} Respostas</span>
					<br>
					<span class='small'>{$dados['visitas']} Visualizações</span>
				</td>
				</tr>";
				}
			echo "</table>";
			}
		}

------------------------------------------------------------------------------

















	function get_topic($id){
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

			echo "
			<table class='table'>
				<tr>
					<th class='w-15 p-3'>{$dadosU['nome']}</th>
					<th class='w-75 p-3'>Postado {$dados['data']}</th>
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
		</table>
		";
		}
		$this->update_views($dados['id'], $dados['visitas']);
		$this->get_respostas($dados['id'], $dados['forum'], $dados['status']);
		echo $idCategoria = $this->get_idcategory($dados['id']);
	}

	function get_respostas($id_topico, $id_forum, $status){
		$stmt = $this->con->prepare("SELECT * FROM respostas WHERE id_topico = ? ORDER BY id ASC");
		$stmt->bind_param("s", $id_topico);
		$stmt->execute();
		$get = $stmt->get_result();
		$dadosR = $get->fetch_assoc();

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

			echo "
			<table class='table'>
				<tr>
					<th class='w-15 p-3'>{$dadosU['nome']}</th>
					<th class='w-75 p-3'>Postado {$dados['data']}</th>
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
		</table>
		";
		}
		
	}

		if($status == 1){
			$this->reply_topic("thsales061", $forum);
		}else{
			echo "<div class='alert alert-danger'><i class='fas fa-lock'></i> Tópico fechado. Você não pode comentar!</div>";
		}
}

	function update_views($id, $visitas){
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

	function update_likes_topicos($id, $curtidas){
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

	function get_total_respostas($id){
		$stmt = $this->con->prepare("SELECT * FROM respostas WHERE id_topico = ?");
		$stmt->bind_param("s", $id);
		$stmt->execute();
		$get = $stmt->get_result();
		return $get->num_rows;
	}


	function update_likes_respostas($id, $curtidas){
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

	function reply_topic($postador, $forum){
		echo "<table class='table'>
				<tr>
				<td class='reply-width'>
					<img src='http://tutoriaiseinformatica.com/blog/images/me.jpg' class='img-fluid reply-img'>
				</td>
				<td>
				<form method='POST'>
					<textarea name='resposta' class='form-control'></textarea>
					<br><input type='submit' value='Responder' class='btn btn-primary btn-sm float-right'>
					<input type='hidden' name='env' value='resp'>
				</form>
				</td>
			</tr>
			</table>
		";

		if($_POST['env'] && $_POST['env'] == "resp"){
			$resposta = addslashes($_POST['resposta']);
			$data = $this->get_data();
			$id_topico = "1";
			
			$sql = $this->con->prepare("INSERT INTO respostas (id_topico, postador, resposta, data) VALUES (?, ?, ?, ?)");
			$sql->bind_param("ssss", $id_topico, $postador, $resposta, $data);
			$sql->execute();

			if($sql->affected_rows > 0){
				return true;
			}else{
				return false;
			}
			

		}	
	}

	function get_data(){
		date_default_timezone_set('America/Sao_Paulo');
		return date('d-m-Y H:i:s');
	}
}
?>
