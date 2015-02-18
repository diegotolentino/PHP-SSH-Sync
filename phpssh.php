<?php
/**
 * PHP-SSH-Sync
 *
 * Script for remote php/ssh backup and copy 
 * 
 * @author Diego Tolentino <diegotolentino@gmail.com>
 * @link http://www.ciawebworks.com
 * @version 1
 *
 * @since 2003-12-18 10:51 Inicio
 */
 
/**
 * ********************************
 * Inicializaчуo e conferencias
 * ********************************
 */

/*mostra todos os erros*/
error_reporting(E_ALL);

/*tempo ilimitado para o script*/
set_time_limit(0);

try {
	
	/*nуo existe a variavel que guarda os argumentos*/
	if (!isset($argv))
		throw new Exception("PHP erro: a variavel \$argv nуo esta definida, altere a diretiva register_argc_argv do php.ini\n");
		
	/*nуo existe o argumento com a configuraчуo a ser interpretada*/
	if (!isset($argv[1]))
		throw new Exception("PHP erro: Arquivo ini nуo informado, use\n copiador.bat empresa.ini\n");
	
	$conf = parse_ini_file($argv[1], true);
	
	/*se o conteudo de $conf['server'] nуo for um ip valido, trata como um arquivo contendo o ip*/
	if (!ereg('[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}', $conf['server'])) {
		echo "Resolvendo o Ip no arquivo: \"{$conf['server']}\"\n";
		$conf['server'] = trim(file_get_contents($conf['server']));
	}
	
	/**
	 * ********************************
	 * Conectando e autenticando
	 * ********************************
	 */
	echo "::::::::::::::::::::::::::::\n";
	echo " Conectando a {$conf['server']} com a conta {$conf['user']} \n";
	echo "::::::::::::::::::::::::::::\n";
	$connection = ssh2_connect($conf['server'], $conf['port']);
	if (!ssh2_auth_password($connection, $conf['user'], $conf['pass']))
		throw new Exception('PHP erro: Nуo foi possivel conectar ao servidor ' . $conf['server']);
	$sftp = ssh2_sftp($connection);
	
	/**
	 * ********************************
	 * Executa os scripts remotos, como por 
	 * exemplo chama uma rotina de backup ou tar de um arquivo
	 * ********************************
	 */
	if (isset($conf['before_script']) && $conf['before_script']) {
		foreach ($conf['before_script'] as $script) {
			echo "Executando script \"$script\"\n";
			$stream = ssh2_exec($connection, $script);
			stream_set_blocking($stream, true);
			while ($line = fgets($stream)) {
				if ($line = trim($line))
					echo " $line\n";
			}
		}
	}
	
	/**
	 * ********************************
	 * Compiando os arquivos
	 * ********************************
	 */
	/*definindo o prefixo para o nome dos arquivos baixados*/
	$prefixo = (isset($conf['prefix']) ? $conf['prefix'] : '');
	$prefixo = str_replace('[DATA]', date('Y-m-d_H-i'), $prefixo);
	
	/*copiando*/
	foreach ($conf['copy_from'] as $remoto) {
		echo "Listando arquivos do comando \"$remoto\"\n";
		$stream = ssh2_exec($connection, $remoto);
		stream_set_blocking($stream, true);
		while ($line = fgets($stream)) {
			print_r($line);
			if ($line = trim($line)) {
				echo "  Copiando $line\n";
				if (!copy("ssh2.sftp://$sftp/$line", $conf['copy_to'] . '/' . $prefixo . basename($line)))
					throw new Exception(" -> Nуo foi possivel copiar");
			}
		}
	}
} catch (Exception $e) {
	echo $e->getMessage();
}
?>