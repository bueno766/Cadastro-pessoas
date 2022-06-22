<?php 
require_once 'pessoa.php';
$p = new Pessoa("pdo", "localhost","root", "");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cadastro Pessoas</title>
</head>
<body>
    <?php
        if(isset($_POST['nome'])){ // Para identificar se clicou no botao cadastrar ou editar
            if(isset($_GET['id_up']) && !empty($_GET['id_up'])){ //--------Atualizar--------
                $id_up= addslashes($_GET['id_up']);
                $nome = addslashes($_POST['nome']);
                $telefone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);
    
                if(!empty($nome) && !empty($telefone) && !empty($email)){
                    //Atualizar
                    $p->atualizarDados($id_up,$nome, $telefone, $email);
                    header("location: index.php");
                }else{
                    ?>
                        <div class="aviso">
                            <img src="aviso.png">
                            <h4>Preencha todos os campos!</h4>
                        </div>  
                    <?php
                }
            }else{ //-----Cadastrar-----
                $nome = addslashes($_POST['nome']);
                $telefone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);
    
                if(!empty($nome) && !empty($telefone) && !empty($email)){
                    if(!$p->cadastrar($nome, $telefone, $email)){
                        ?>
                            <div class="aviso">
                                <img src="aviso.png">
                                <h4>Email ja esta cadastrado!</h4>
                            </div>  
                        <?php
                    }
                }else{
                    ?>
                        <div class="aviso">
                            <img src="aviso.png">
                            <h4>Preencha todos os campos!</h4>
                        </div>  
                    <?php
                }
            }
        }
    ?>
    <?php
        if(isset($_GET['id_up'])){ // se clicou no botão editar
            $id_update = addslashes($_GET['id_up']);
            $res = $p->buscarCadastro($id_update);
        }
    ?>
    <section id="esquerda">
        <form method="POST">
            <h2>CADASTRAR PESSOAS</h2>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?php if(isset($res)){echo $res['nome'];}?>"> 
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?php if(isset($res)){echo $res['telefone'];}?>">
            <label for="email">Email</label> 
            <input type="email" name="email" id="email" value="<?php if(isset($res)){echo $res['email'];}?>">
            <input type="submit" value="<?php if(isset($res)){echo "ATUALIZAR";}else{echo "CADASTRAR";}?>">
        </form>
    </section>

    <section id="direita">
        <table>
            <tr id="titulo">
                <td>NOME</td>
                <td>TELEFONE</td>
                <td colspan="2">EMAIL</td>
            </tr>
        <?php
            $dados = $p->buscarDados();
            if(count($dados) > 0){
                for ($i=0; $i < count($dados); $i++){
                    echo "<tr>";
                    foreach($dados[$i] as $key => $value){
                        if($key != "id"){
                            echo "<td>".$value."</td>";
                        }
                    }
                    ?><td>
                        <a href="index.php?id_up=<?php echo $dados[$i]['id']; ?>">Editar</a>
                        <a href="index.php?id=<?php echo $dados[$i]['id']; ?>">Excluir</a> 
                    </td><?php
                    echo "</tr>";
                }
            }else{ // Banco de dados vazio
                ?>
                 </table>
                    <div class="aviso">
                        <h4>Ainda não há pessoas cadastradas!</h4>
                    </div>  
                <?php
            }
        ?>
    </section>  
</body>
</html>

<?php 
    if(isset($_GET['id'])){
       $id_pessoa = addslashes($_GET['id']);
       $p->excluir($id_pessoa);
       header("location: index.php");
    }
?>

