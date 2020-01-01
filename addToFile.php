<?php
class TodoList {
    public $TodoItem = array();

    function addToFile($tasks, $todo_name, $todo_rate, $created_time, $modified_time, $done = false) {
        $tasks[] = [
            'name'    => $todo_name,
            'rate'    => $todo_rate,
            'time'    => $created_time,
            'modified'=> $modified_time,
            'status'  => $done
        ];

        file_put_contents('todo.txt', serialize($tasks));

        return $tasks;
    }

}


$tasks = unserialize(file_get_contents("todo.txt"));

$task_name = isset($_REQUEST['task_name']) ? $_REQUEST['task_name'] : '';
$task_rate = isset($_REQUEST['task_rate']) ? $_REQUEST['task_rate'] : '';
date_default_timezone_set('Europe/Kiev');
$created_time = date("h:i:sa");
$modified_time = date("h:i:sa");
$error = "Enter data please";
$addedItem = 'Nonthing was added';
$isFill = false;

if (!empty($_REQUEST['task_name']) && !empty($_REQUEST['task_rate'])) {
    $isFill = true;
}
if (isset($_POST['submit']) && $isFill) {
    $tasks = TodoList::addToFile($tasks, $task_name, $task_rate, $created_time, $modified_time);
    $error = '';
    $addedItem = "Task '$task_name' With rate '$task_rate' Was created at '$created_time'.";
}

if (isset($_REQUEST['delete'])) {
    unset($tasks[$_REQUEST['delete']]);
//    $modified_time = date("h:i:sa");
    file_put_contents('todo.txt', serialize($tasks));
}

if (isset($_REQUEST['done'])) {
    $tasks[$_REQUEST['done']]['status'] = (bool) $_REQUEST['status'];
    $tasks[$_REQUEST['done']]['modified'] = date("h:i:sa");
    file_put_contents('todo.txt', serialize($tasks));
}
