<?php

require_once (__DIR__."/../domain/Tarefa.php");

class TarefaDao
{
    private $conexao;

    function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    function listaTarefas()
    {
        $arrays = array();
        $resultado = mysqli_query($this->conexao,
            "select distinct concat(t.idHistoria,'.',t.idFuncionalidade,'.',t.idTarefa) as cod_tar, t.*,p.nome, p.papel, f.funcionalidade
                        from Tarefa as t 
                        join Pessoa as p on t.ra=p.ra 
                        join Funcionalidade as f on t.idFuncionalidade=f.idFuncionalidade 
                        group by cod_tar
                        order by t.idHistoria, t.idFuncionalidade, t.idTarefa");
        while ($array = mysqli_fetch_assoc($resultado)) {
            array_push($arrays, $array);
        }
        return $arrays;
    }
    function countIdHistoria()
    {
        $arrays = array();
        $resultado = mysqli_query($this->conexao,
            "select idHistoria, COUNT(*) as total
                       from Tarefa group by idHistoria");
        while ($array = mysqli_fetch_assoc($resultado)) {
            array_push($arrays, $array);
        }
        return $arrays;
    }

    function countIdHistoriaIdSprint()
    {
        $arrays = array();
        $resultado = mysqli_query($this->conexao,
            "select concat(idHistoria, '-', idSprint),idHistoria,idSprint, COUNT(*) as total
                        from Tarefa group by concat(idHistoria, '-', idSprint)");
        while ($array = mysqli_fetch_assoc($resultado)) {
            array_push($arrays, $array);
        }
        return $arrays;
    }

    function insereTarefa(Tarefa $tarefa)
    {
        $query = "insert into Tarefa (tarefa, idSprint, ra, status, inicio, tempo, termino, duracao, dependencia, prioridade)
            values (
            '{$tarefa->getTarefa()}',
            '{$tarefa->getIdSprint()}',
            '{$tarefa->getRa()}', 
            {$tarefa->getStatus()},
            {$tarefa->getInicio()}, 
            '{$tarefa->getTempo()}', 
            '{$tarefa->getTermino()}', 
            '{$tarefa->getDuracao()}', 
            '{$tarefa->getDependencia()}',
            '{$tarefa->getPrioridade()}')";
        return mysqli_query($this->conexao, $query);
    }

    function alteraTarefa($idHistoria, $idFuncionalidade, $idTarefa, Tarefa $tarefa)
    { 
        $query = "update Tarefa set 
            idHistoria = '{$tarefa->getIdHistoria()}', 
            idFuncionalidade = '{$tarefa->getIdFuncionalidade()}', 
            idTarefa = '{$tarefa->getIdTarefa()}', 
            tarefa = '{$tarefa->getTarefa()}', 
            idSprint = '{$tarefa->getIdSprint()}', 
            ra = '{$tarefa->getRa()}', 
            status = '{$tarefa->getStatus()}', 
            inicio = '{$tarefa->getInicio()}', 
            tempo = '{$tarefa->getTempo()}', 
            termino = '{$tarefa->getTermino()}', 
            duracao = '{$tarefa->getDuracao()}', 
            dependencia = '{$tarefa->getDependencia()}', 
            prioridade = '{$tarefa->getPrioridade()}' 
            where 
            idHistoria = '{$idHistoria}' AND
            idFuncionalidade = '{$idFuncionalidade}' AND
            idTarefa = '{$idTarefa}'"
        ;
        return mysqli_query($this->conexao, $query);
    }

    function buscaTarefa($idHistoria, $idFuncionalidade, $idTarefa)
    {  
        $query = "select * from Tarefa where 
            idHistoria = '{$idHistoria}' AND
            idFuncionalidade = '{$idFuncionalidade}' AND
            idTarefa = '{$idTarefa}'";
        $resultado = mysqli_query($this->conexao, $query);
        $array = mysqli_fetch_assoc($resultado);
        return  $array;
    }

    function removeTarefa($idHistoria, $idFuncionalidade, $idTarefa)
    {
        $query = "delete from Tarefa where 
                    idHistoria = '{$idHistoria}' AND
                    idFuncionalidade = '{$idFuncionalidade}' AND
                    idTarefa = '{$idTarefa}'";
        return mysqli_query($this->conexao, $query);
    }
}

?>