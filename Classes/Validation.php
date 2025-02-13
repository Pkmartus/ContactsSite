<?
//each public function checks a different part of the form using the validate method 
//to check against the regular expression for that type
class Validation{

    private function validate($value, $regex) {
        $valid = preg_match($regex, $value);
        return $valid;
    }

    public function name($value) {
        $regex = '/^([a-z]|-|\'|\s)+$/i';
        return $this->validate($value, $regex);
    }

    public function address($value) {
        $regex = '/^\d+\s([a-z]|\s)+$/i';
        return $this->validate($value, $regex);
    }

    public function city($value) {
        $regex = '/^[a-z\s]+$/i';
        return $this->validate($value, $regex);
    }

    public function phone($value) {
        $regex = '/^\d{3}\.\d{3}\.\d{4}$/';
        return $this->validate($value, $regex);
    }

    //the email method uses the built in email checking in php instead of regular expressions
    public function email($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function DOB($value) {
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        return $this->validate($value, $regex);
    }

    public function password($value) {
        $regex = '/^\S+$/';
        return $this->validate($value, $regex);
    }

}
?>