<?
require_once('Validation.php');

//based on the architecture by Scott Shaper

class StickyForm extends Validation
{
    public function validateForm($GlobalPost, $elementsArr)
    {
        

        foreach ($elementsArr as $k=>$v) {
            
            //for all text fieldstext if the input is valid or not
            if ($elementsArr[$k]['type'] === "text") {
                switch ($elementsArr[$k]['regex']) {
                    case "name":
                        $valid = $this->name($GlobalPost[$k]);
                        break;
                    case "address":
                        $valid =  $this->address($GlobalPost[$k]);
                        break;
                    case "city":
                        $valid =  $this->city($GlobalPost[$k]);
                        break;
                    case "phone":
                        $valid =  $this->phone($GlobalPost[$k]);
                        break;
                    case "email":
                        $valid =  $this->email($GlobalPost[$k]);
                        break;
                    case "password":
                        $valid =  $this->password($GlobalPost[$k]);
                        break;
                }
                //if any errors are found set them for the specific element and for the whole page
                if (!$valid) {
                    $elementsArr[$k]['errorOutput'] = $elementsArr[$k]['errorMessage'];
                    $elementsArr['masterStatus']['status'] = "error";
                }
                //make it sticky
                $elementsArr[$k]['value'] = htmlentities($GlobalPost[$k]);

                //make select fields sticky
            } elseif ($elementsArr[$k]['type'] === "select") {
                $elementsArr[$k]['selected'] = $GlobalPost[$k];
            }

            //make checkboxes sticky and check if required
            else if ($elementsArr[$k]['type'] === 'checkbox') {
                    if (isset($GlobalPost[$k])) {
                                    $elementsArr[$k]['status'] = "checked";
                    }
                }

            //make dates sticky and check if required
            else if ($elementsArr[$k]['type'] === 'date') {
                $elementsArr[$k]['value'] = $GlobalPost[$k];
            }
        }

        return $elementsArr;
    }
}
