<?php 

require_once "database.php";
require_once "documentos.php";


class documentosDAO{
	
	public static $instance;

    public function __construct() {

    }
    
        
	public function Buscar($id){
		try  {
            
            $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
            
            $sql = Conexao::getInstance()->prepare ("SELECT COUNT(*) AS total FROM `02material_texto` WHERE mt02codigo_tema = :id ORDER BY mt02titulo_material");
			$sql->bindValue(":id", $id);
			$sql->execute();
           	$total_arquivos = $sql->fetch();
            $total_arquivos = $total_arquivos['total'];
            
            $registros = 10;
            
            $numPaginas = ceil($total_arquivos/$registros);
	
            $inicio = ($registros*$pagina) - $registros;
            
            } 	catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar o codigo, foi gerado
					um LOG do mesmo, tente novamente mais tarde.";
        }
        
        try {
            $sql = Conexao::getInstance()->prepare ("SELECT `mt02titulo_material`, `mt02codigo_material` , `mt02material` FROM `02material_texto` WHERE mt02codigo_tema = :id ORDER BY mt02titulo_material LIMIT $inicio, $registros");
			$sql->bindValue(":id", $id);
			$sql->execute();
			return $this->atribuirValores($sql);
		} 	catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar o codigo, foi gerado
					um LOG do mesmo, tente novamente mais tarde.";
        }
    } 
	
    	public function paginar($id){
		try  {
            
            $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
            
            $sql = Conexao::getInstance()->prepare ("SELECT COUNT(*) AS total FROM `02material_texto` WHERE mt02codigo_tema = :id ORDER BY mt02titulo_material");
			$sql->bindValue(":id", $id);
			$sql->execute();
           	$total_arquivos = $sql->fetch();
            $total_arquivos = $total_arquivos['total'];
            
            $registros = 10;
            
            $numPaginas = ceil($total_arquivos/$registros);
	
            $inicio = ($registros*$pagina) - $registros;
            
            } 	catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar o codigo, foi gerado
					um LOG do mesmo, tente novamente mais tarde.";
        }
            
            if($pagina > 1) {
            echo "<a href='exibir_arquivos.php?search=".$id."&pagina=".($pagina - 1)."' class='controle'>&laquo; anterior</a>";
            }

            for($i = 1; $i < $numPaginas + 1; $i++) {
            $ativo = ($i == $pagina) ? 'numativo' : '';
            if ($numPaginas <= 1){
                echo "";
            }else {
            echo "<a href='exibir_arquivos.php?search=".$id."&pagina=".$i."' class='numero ".$ativo."'> ".$i." </a>";
            } }

            if($pagina < $numPaginas) {
            echo "<a href='exibir_arquivos.php?search=".$id."&pagina=".($pagina + 1)."' class='controle'>próximo &raquo;</a>";
            }
        }
        
	private function atribuirValores($sql) {
		$results = array();
		
		if($sql){
			while($row = $sql->fetch(PDO::FETCH_OBJ)){
			$documentos = new Documentos();
		
			$documentos->setTitulo($row->mt02titulo_material);
			$documentos->setCodigoMaterial($row->mt02codigo_material);
			$documentos->setMaterial($row->mt02material);
			
			$results[] = $documentos;
			}
    	}
		return $results;
    }
    
                         
                                    
	public function exibirPDF($id){
		
		$sql = Conexao::getInstance()->prepare("SELECT `mt02material` FROM `02material_texto` WHERE mt02codigo_material = :id ");
		$sql->bindValue(":id", $id);
		$sql->execute();
		$res = $sql->fetchAll();
		
		header("Content-Type: application/pdf");
		
		echo $res[0][mt02material];
	}
	
    public function downloadPDF($id){
        
        $sql = Conexao::getInstance()->prepare("SELECT `mt02material` FROM `02material_texto` WHERE mt02codigo_material = :id ");
		$sql->bindValue(":id", $id);
		$sql->execute();
		$res = $sql->fetchAll();
		
        $file = "Arquivo_Plataforma_REMA";
        
		header("Content-Type: application/pdf");
		header('Content-Disposition: attachment; filename="'.$file.'.pdf"');
		
		echo $res[0][mt02material];
    }
    
    public function buscarPalavraChave($buscar){
		        
        try {
            $sql = Conexao::getInstance()->prepare ("SELECT `02material_texto`.`mt02titulo_material`,`02material_texto`.`mt02material`, `02material_texto`.`mt02codigo_material`,`04palavras_chave`.`04pl_palavras` 					FROM `02material_texto`, `04palavras_chave`WHERE `02material_texto`.`mt02pl_codigo` = `04palavras_chave`.`04pl_codigo` AND `04palavras_chave`.`04pl_palavras` LIKE :buscar ORDER BY 								mt02titulo_material");
			$sql->bindValue(":buscar", $buscar);
			$sql->execute();
			return $this->atribuirValores($sql);
		} 	catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar o codigo, foi gerado
					um LOG do mesmo, tente novamente mais tarde.";
        }
	}
    
	public function buscarAutor($buscar){
		
        try {
            $sql = Conexao::getInstance()->prepare ("SELECT  `02material_texto`.`mt02titulo_material` ,  `02material_texto`.`mt02material` ,  `02material_texto`.`mt02codigo_material`, 						 `07obra_autor`.`ob01codigo_autor` FROM  `02material_texto` ,  `07obra_autor` WHERE `02material_texto`.`mt02codigo_autores` = `07obra_autor`.`ob01codigo_autor` AND  `07obra_autor`.`ob02nomes` LIKE :buscar ORDER BY mt02titulo_material ");
			$sql->bindValue(":buscar", $buscar);
			$sql->execute();
			return $this->atribuirValores($sql);
		} 	catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar o codigo, foi gerado
					um LOG do mesmo, tente novamente mais tarde.";
        }
	}

	public function buscarAno($buscar){
		
        
        try {
            $sql = Conexao::getInstance()->prepare ("SELECT `02material_texto`.`mt02titulo_material`,`02material_texto`.`mt02material`,`02material_texto`.`mt02codigo_material` FROM `02material_texto`
				   WHERE `02material_texto`.`mt02ano_material` = :buscar ORDER BY mt02titulo_material");
			$sql->bindValue(":buscar", $buscar);
			$sql->execute();
			return $this->atribuirValores($sql);
		} 	catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar o codigo, foi gerado
					um LOG do mesmo, tente novamente mais tarde.";
        }
	}
}

?>

