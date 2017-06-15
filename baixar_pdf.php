<?php 
	
	require_once "database.php";
	require_once "documentos.php";
	require_once "documentosDAO.php";
	
	$id = $_GET['search'];
	
	$documentos = new documentosDAO();
	
	$documentos->downloadPDF($id);
?>