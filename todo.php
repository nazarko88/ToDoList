<link rel="stylesheet" href="styles.css">
<?php require 'addToFile.php'; ?>

<form class="in" action="todo.php" method="post">
    <span class="error"><?php echo $error;?></span><br>
    <label for="task_name">New task (max 10char)</label>
    <input id="task_name" type="text" name="task_name" maxlength="10" autofocus/>
    <label for="task_rate" >Rating (1-9)</label>
    <input id="task_rate" type="number" name="task_rate" maxlength="2" min="1" max="9"/>

    <input type="submit" name="submit">
    <input type="reset" value="Reset">
</form>

<h1><?php echo $addedItem;?></h1><br>

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
        <?php
            foreach ($tasks as $key => $task) {
                $status = var_export($task['status'], true);
                $checked   = $task['status'] ? 'checked="checked"' : '';
                $newStatus = var_export(! $task['status'], true);
                echo "
                <tr>
                    <td>{$task['name']}</td>
                    <td>{$task['rate']}</td>
                    <td>{$status}</td>
                    <td>{$task['time']}</td>
                    <td>{$task['modified']}</td>
                    <td>
                        <form action='todo.php' method='post'>
                            <input type='hidden' name='delete' value='" . $key . "'/>
                            <input type='submit' value='delete'>
                        </form>
                    </td>
                    <td>
                        <form action='todo.php' method='post'>
                            <input type='hidden' name='done' value='" . $key . "'/>
                            <input type='checkbox' name='status' value='" . $newStatus . "' onChange='this.form.submit()' " . $checked . "/>
                        </form>
                    </td>
                </tr>";
            }
        ?>
    </tbody>
</table>
