<?
require_once('redirect.php');
require_once('Classes/StickyForm.php');
$stickyForm = new StickyForm();

function init()
{
    global $elementsArr, $stickyForm;
    //if the submit button is pressed validate the form
    if (isset($_POST['submit'])) {
        $postArr = $stickyForm->validateForm($_POST, $elementsArr);

        //if there are no errors submit to the database
        if ($postArr['masterStatus']['status'] == "noerrors") {
            return addData($_POST, $postArr);
            //if there are errors keep the fields sticky and show the errors
        } else {
            return getForm("", $postArr);
        }
    } else {
        return getForm("", $elementsArr);
    }
}

function addData($post, $sticky)
{
    global $elementsArr;

    require_once('Classes/Pdo_methods.php');
    $pdo = new PdoMethods();

    //search the database to check if email already exists
    $checkDuplicate = "SELECT email FROM admin where email = :email";
    $email = [[':email', $post['email'], 'str']];
    $duplicate = $pdo->selectBinded($checkDuplicate, $email);

    //if email doesn't exist attempt to add admin to the database
    if(!isset($duplicate[0])) {

        $sql = "INSERT INTO admin (name, email, password, status) VALUES (:name, :email, :password, :status)";
        $bindings = [
            [':name', $post['name'], 'str'],
            [':password', $post['password'], 'str'],
            [':email', $post['email'], 'str'],
            [':status', $post['status'], 'str']
        ];
        $result = $pdo->otherBinded($sql, $bindings);

        if ($result == "error") {
            return getForm("<p>there was an error adding user</p>", $sticky);
        } else {
            return getForm("<p>User added</p>", $elementsArr);
        }
    } else {
        return getForm("<p>An admin already exists with that email</p>", $sticky);
    }
}

//a nested array with the data for each part of the form
$elementsArr = [
    "masterStatus" => [
        "status" => "noerrors",
        "type" => "masterStatus"
    ],
    "name" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>Name cannot be blank and must be a standard name</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "",
        "regex" => "name"
    ],
    "password" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>Passwords can only include letters numbers and special characters</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "",
        "regex" => "password"
    ],
    "email" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>Email cannot be blank and must be in format: example@domain.com</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "",
        "regex" => "email"
    ]
];

//inputs data from the array into the form
function getForm($acknowledgement, $elementsArr)
{
    global $stickyForm;

    $form = <<<HTML
            <head>
                <title>Add Admin</title>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            </head>
                <br><br>
            <body class = "container">
                <form method="post">
                    <div class="form-group">
                        <label for="name" class="space">Name (letters only){$elementsArr['name']['errorOutput']}</label>
                        <input type="text" class="form-control" name="name" id="name" value="{$elementsArr['name']['value']}">
                    </div>
                    <div class="form-group">
                        <label for="email" class="space">Email{$elementsArr['email']['errorOutput']}</label>
                        <input value="{$elementsArr['email']['value']}" type="text" class="form-control" name="email" id="email">
                    </div>
                    <div class="form-group">
                        <label for="password" class="space">Password{$elementsArr['password']['errorOutput']}</label>
                        <input  value="{$elementsArr['password']['value']}" type="text" class="form-control" name="password" id="password">
                    </div>
                    <div class="form-group">
                        <label for="status" class="space">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="Staff">Staff</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="submit" value="Submit" class="btn btn-primary">Submit</button>
                    <br><br><br><br>
                </form>
        </body>
HTML;
    return [$acknowledgement, $form];
}
