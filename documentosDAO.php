<?php 

require_once "database.php";
require_once "documentos.php";


class documentosDAO{
	
	public static $instance;

    public function __construct() {

    }
    
	
    public function downloadPDF($id){
        
        $sql = Conexao::getInstance()->prepare(" here your script to select the pdf file ");
		$sql->bindValue(":id", $id);
		$sql->execute();
		$res = $sql->fetchAll();
		
        $file = "name_of_file";
        
		header("Content-Type: application/pdf");
		header('Content-Disposition: attachment; filename="'.$file.'.pdf"');
		
		echo $res[0][name in db];
    }
    
   
}

?>

