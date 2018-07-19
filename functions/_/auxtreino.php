<script>
$(document).ready(function() {
    $('.delete-object').click(function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var object = $(this);
        var pid = object.attr('data-id');
        $.post('auxtreino.php', { 'exercicio': pid, 'type': 'del' })
            .done(function(response) {
                $('#subData').html(response);

            })
            .fail(function() {
                alert('Something Went Wrog ....');
            });
        return false;
    });
    });
</script>
<table class="highlight bordered">
    <thead>
      <th>Exercício</th>
      <th>Quantidade</th>
      <th>Repetições</th>
      <th></th>
      </thead>
      <tbody>
<?php
    require_once "./util/conexao.php";
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION["treino"])) $_SESSION["treino"] = array();

    if(isset($_POST["type"])){
        if($_POST["type"] ==  "add"){
            $exercicioId = $_POST["exercicio"];
            $qtd =  $_POST["qtd"];
            $repeticoes =$_POST["repeticoes"];
            $found = 0;
            
            for($i = 0;$i< sizeof($_SESSION["treino"]); $i++ ){
                if($_SESSION["treino"][$i]["exercicio"] == $exercicioId){
                    $_SESSION["treino"][$i]["qtd"] = $qtd;
                    $_SESSION["treino"][$i]["repeticoes"] = $repeticoes;
                    $found = 1;
                    break;
                }
            }
            if($found == 0){
                array_push($_SESSION["treino"],[
                    "exercicio" => $exercicioId,
                    "qtd" => $qtd,
                    "repeticoes" => $repeticoes
                ]);
            }
        }
        else if($_POST["type"] == "del"){
            $exercicioId = $_POST["exercicio"];
             for($i = 0;$i< sizeof($_SESSION["treino"]); $i++ ){
                if($_SESSION["treino"][$i]["exercicio"] == $exercicioId){
                    array_splice($_SESSION["treino"], $i,1);
                    break;
                }
             }
        }
    }
    if(!empty($_SESSION["treino"])){
    $sql = "SELECT nmExercicio from tbexercicio where cdExercicio = :id";
    $stmt = $conexao->prepare($sql);    
    foreach($_SESSION["treino"] as $row){
    $stmt->execute([
    "id" => $row["exercicio"]
    ]);
    $recordSet = $stmt->fetch(PDO::FETCH_ASSOC);
    $exercicioNome = $recordSet["nmExercicio"];
    echo "<tr>
    <td>{$exercicioNome}</td>
    <td>{$row["qtd"]}</td>
    <td>{$row["repeticoes"]}</td>
    <td><a href='javascript:void(0)' data-id='{$row["exercicio"]}'  class='delete-object'><i class=\"material-icons md-dark pmd-md\">delete</i></a></td></td>
    </tr>";
    }
        }
?>

      </tbody>
    </table>

<?php
        echo "<input type='text' hidden value='".json_encode($_SESSION["treino"])."' name='dataTreino' id='dataTreino'>";
        
?>