<?php

require_once 'Additions/AbstractModel.php';
require_once 'Additions/TodoList.php';
require_once 'Additions/TodoItem.php';
require_once 'Additions/DB.php';

use Additions\DB;
use Additions\{TodoList, TodoItem};

$db = new DB();

$listId = 1;

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

$list  = $db->getOne(TodoList::class, ['id' => $listId]);
$items = $db->getMany(TodoItem::class, ['listId' => $listId]);
$list->setItems($items);

switch ($action) {
    case 'get':
        $response = [];

        foreach ($list->getItems() as $task) {
            $status    = $task->getStatus();
            $checked   = $status ? 'checked="checked"' : '';
            $newStatus = var_export(! $status, true);

            $id = $task->getId();

            $response[] =
                '<tr>' .
                    '<td>' . $task->getName() . '</td>' .
                    '<td>' . $task->getRate() . '</td>' .
                    '<td>' . var_export($status, true) . '</td>' .
                    '<td>' . $task->getTime()->format('Y-m-d H:i:s') . '</td>' .
                    '<td>' . ($task->getModified() !== null ? $task->getModified()->format('Y-m-d H:i:s') : null) . '</td>' .
                    '<td><input type="button" value="delete" onClick="remove(' . $id . ');"></td>' . 
                    '<td><input type="checkbox" name="status" value="' . $newStatus . '" onChange="changeStatus(' . $id . ', ' . $newStatus . ');" ' . $checked . '></td>' . 
                '</tr>';
        }

        echo implode("\n", $response);
        return;
    case 'add':
        $name = isset($_REQUEST['task_name']) ? $_REQUEST['task_name'] : null;
        $rate = isset($_REQUEST['task_rate']) ? $_REQUEST['task_rate'] : null;

        $item = new TodoItem($name, (int) $rate, (int) $listId);

        $db->insert($item);
        return;
    case 'remove':
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;

        $db->deleteOne(TodoItem::class, ['id' => $id]);
        return;
    case 'update':
        $id     = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
        $name   = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;
        $rate   = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;

        if ($id === null) {
            return;
        }

        $item = $list->getItemById((int) $id);

        if ($status !== null) {
            $status = filter_var($status, FILTER_VALIDATE_BOOLEAN);

            $item
                ->setStatus($status)
                ->updateModified();
        }

        if ($name !== null) {
            $item
                ->setName($name)
                ->updateModified();
        }

        if ($rate !== null) {
            $item
                ->setRate($rate)
                ->updateModified();
        }

        $db->updateOne($item);
        return;
    default:
        break;
}

?>

<!doctype html>

<html lang="en">
<head>
  <link rel="stylesheet" href="styles.css">
  <script type="text/javascript" src="scripts.js"></script>
</head>
<body>
  <form class="in" method="post">
    <label for="task_name">New task (max 10char)</label>
    <input id="task_name" type="text" name="task_name" maxlength="10" autofocus/>
    <label for="task_rate" >Rating (1-9)</label>
    <input id="task_rate" type="number" name="task_rate" maxlength="2" min="1" max="9"/>

    <input type="submit" name="submit">
    <input type="reset" value="Reset">
  </form>

  <h1>ToDo List:</h1>
  <table>
      <thead>
          <tr>
              <th>Task</th>
              <th>Rate</th>
              <th>Compleated</th>
              <th>Created</th>
              <th>Modified</th>
              <th>Delete</th>
              <th>Status Change</th>
          </tr>
      </thead>
      <tbody>
      </tbody>
  </table>

  <script>
    get();
    events();
  </script>
</body>
</html>
