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
            return addData($_POST);
            //if there are errors keep the fields sticky and show the errors
        } else {
            return getForm("", $postArr);
        }
    } else {
        return getForm("", $elementsArr);
    }
}

function addData($post)
{
    global $elementsArr;

    require_once('Classes/Pdo_methods.php');
    $pdo = new PdoMethods();

    $sql = "INSERT INTO contact (name, address, city, state, phone, email, dob, contact) VALUES (:name, :address, :city, :state, :phone, :email, :dob, :contact)";

    //for each of the check boxes add that value and a comma to a string
    $contact = "";

    if (isset($post['newsletter'])) {
        $contact .= $post['newsletter'] . ", ";
    }
    if (isset($post['emailUpdates'])) {
        $contact .= $post['emailUpdates'] . ", ";
    }
    if (isset($post['textUpdates'])) {
        $contact .= $post['textUpdates'] . ", ";
    }

    //remove the last comma and space
    $contact = substr($contact, 0, -2);


    $bindings = [
        [':name', $post['name'], 'str'],
        [':address', $post['address'], 'str'],
        [':city', $post['city'], 'str'],
        [':state', $post['state'], 'str'],
        [':phone', $post['phone'], 'str'],
        [':email', $post['email'], 'str'],
        [':dob', $post['dob'], 'str'],
        [':contact', $contact, 'str']
    ];

    $result = $pdo->otherBinded($sql, $bindings);

//if there are no errors then add the contact to the database. otherwise reset the form and notify the user
    if ($result == "error") {
        return getForm("<p>there was an error adding contact</p>", $elementsArr);
    } else {
        return getForm("<p>Contact Updated</p>", $elementsArr);
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
    "phone" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>Phone cannot be blank and must be a valid phone number</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "",
        "regex" => "phone"
    ],
    "address" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>Adress cannot be blank and must be the number and street</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "",
        "regex" => "address"
    ],
    "city" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>City cannot be blank</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "",
        "regex" => "city"
    ],
    "email" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>Email cannot be blank and must be in format: example@domain.com</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "",
        "regex" => "email"
    ],
    "dob" => [
        "type" => 'date',
        "value" => ""
    ],
    "newsletter" => [
        "type" => "checkbox",
        "status" => ""
    ],
    "emailUpdates" => [
        "type" => "checkbox",
        "status" => ""
    ],
    "textUpdates" => [
        "type" => "checkbox",
        "status" => ""
    ],
];

//inputs data from the array into the form
function getForm($acknowledgement, $elementsArr)
{
    global $stickyForm;

    $form = <<<HTML
            <head>
                <title>Add Contact</title>
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
                        <label for="address" class="space">Address (just number and street){$elementsArr['address']['errorOutput']}</label>
                        <input type="text" class="form-control" name="address" id="address" value="{$elementsArr['address']['value']}">
                    </div>
                    <div class="form-group">
                        <label for="city" class="space">City{$elementsArr['city']['errorOutput']}</label>
                        <input value="{$elementsArr['city']['value']}" type="text" class="form-control" name="city" id="city">
                    </div>
                    <div class="form-group">
                        <label for="state" class="space">State</label>
                        <select class="form-control" name="state" id="state">
                            <option value="Michigan">Michigan</option>
                            <option value="Wisconsin">Wisconsin</option>
                            <option value="Illinois">Illinois</option>
                            <option value="Indiana">Indiana</option>
                            <option value="Minnesota">Minnesota</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="space">Phone{$elementsArr['phone']['errorOutput']}</label>
                        <input  value="{$elementsArr['phone']['value']}" type="text" class="form-control" name="phone" id="phone">
                    </div>
                    <div class="form-group">
                        <label for="email" class="space">Email{$elementsArr['email']['errorOutput']}</label>
                        <input value="{$elementsArr['email']['value']}" type="text" class="form-control" name="email" id="email">
                    </div>
                    <div class="form-group">
                        <label for="dob" class="space">Date of birth</label>
                        <input value="01/01/1999" type="date" class="form-control" name="dob" id="dob">
                    </div>
                    <div class="form-group">
                        Please check all contact types you would like (optional):<br>
                        <input type="checkbox" id="newsletter" name="newsletter" value="Newsletter" {$elementsArr['newsletter']['status']}>
                        <label for="newsletter"> Newsletter</label>&nbsp;&nbsp;
                        <input type="checkbox" id="emailUpdates" name="emailUpdates" value="Email Updates" {$elementsArr['emailUpdates']['status']}>
                        <label for="emailUpdates"> Email Updates</label>&nbsp;&nbsp;
                        <input type="checkbox" id="textUpdates" name="textUpdates" value="Text Updates" {$elementsArr['textUpdates']['status']}>
                        <label for="textUpdates"> Text Updates</label>&nbsp;&nbsp;
                    </div>
                    <button type="submit" name="submit" value="Submit" class="btn btn-primary">Submit</button>
                    <br><br><br><br>
                </form>
        </body>
HTML;
    return [$acknowledgement, $form];
}
