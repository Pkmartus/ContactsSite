<?
require_once('redirect.php');

function init()
{
    require_once('Classes/Pdo_methods.php');
    $pdo = new PdoMethods();

    //if the delete button is pressed and any of the check boxes are selected delete each of those reccords
    if (isset($_POST['delete'])) {
        
        if (isset($_POST['checkbox'])) {
            $error = false;
           

            foreach ($_POST['checkbox'] as $id) {
                $sql = "DELETE FROM admin WHERE admin_id=:id";
                $bindings = [[':id', $id, 'int']];

                $result = $pdo->otherBinded($sql, $bindings);
                if ($result === 'error') {
                    $error = true;
                    break;
                }
            }
        }
    }
    //select all the records in the table
    $output = "";
    $sql = "SELECT * FROM admin";
    $records = $pdo->selectNotBinded($sql);

    //the there are no reccords in the table notify the user
    if (count($records) === 0) {
        $output = "<p>There are no records in table</p>";
        return [$output, ""];
    } else {
        $output = <<<HTML
            <head> 
            <title>Delete Admin</title> 
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        </head>
        <br><br>
        <form method='post'>
        HTML;

        $output .= <<<HTML
            <table class='table table-striped table-bordered'>
            <thead>
                <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Status</th>
                <th>Delete</th>
                </tr>
            </thead><tbody>
    HTML;

        foreach ($records as $row) {
            $output .= <<<HTML
            <tr><td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['password']}</td>
            <td>{$row['status']}</td>
            <td><input type='checkbox' name='checkbox[]' value={$row['admin_id']} /></td></tr>
        HTML;
        }

        $output .= <<<HTML
            </tbody></table>
            <input onclick="return confirm('Are you sure?');" type="submit" name="delete" value="Delete" class="btn btn-danger" id="delete">
            </form>
            HTML;

        if (isset($error)) {
            if ($error) {
                $msg = "<p>Could not delete the admin(s)</p>";
            } else {
                $msg = "<p>Contact(s) deleted</p>";
            }
        } else {
            $msg = "";
        }
        return [$msg, $output];
    }
}
