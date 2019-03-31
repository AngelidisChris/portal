<?php
namespace App\Classes;

use \PDO;

class ValidateRequest
{
    private static $db;

    private static $error = [];
    private static $error_messages = [
        'required' => 'The :attribute field is required',
        'minLength' => 'The :attribute field must be a minimum of :policy characters',
        'maxLength' => 'The :attribute field must be a maximum of :policy characters',
        'mixed' => 'The :attribute field can contain letters, numbers only',
        'email' => 'Email address is not valid',
        'unique' => 'That :attribute is already taken, please try another one',
        'pastDate' => 'The :attribute must not be a past date',
        'validDate' => 'The :attribute is not a valid date'
    ];



    public function __construct()
    {
        $host = getenv("DB_HOST");
        $driver = getenv("DB_DRIVER");
        $dbname = getenv("DB_NAME");
        $user = getenv("DB_USERNAME");
        $pass = getenv("DB_PASSWORD");

        $dsn = $driver . ':host='.$host .';dbname=' . $dbname;

        self::$db = new Database($dsn, $user, $pass, []);

    }







    /**
     * @param array $dataAndValues, column and value to validate
     * @param array $policies, the rules that validation must satisfy
     */
    public function abide(array $dataAndValues, array $policies)
    {
        foreach ($dataAndValues as $column => $value){
            if(in_array($column, array_keys($policies))){
                self::doValidation(
                    ['column' => $column, 'value' => $value, 'policies' => $policies[$column]]
                );
            }
        }
    }

    /**
     * Perform validation for the data provider and set error messages
     * @param array $data
     */
    private static function doValidation(array $data)
    {
        $column = $data['column'];
        foreach ($data['policies'] as $rule => $policy){
            $valid = call_user_func_array([self::class, $rule], [$column, $data['value'], $policy]);
            if(!$valid){
                self::setError(
                    str_replace(
                        [':attribute', ':policy', '_'],
                        [$column, $policy, ' '],
                        self::$error_messages[$rule]), $column
                );
            }
        }
    }

    /**
     * @param $column, field name or column
     * @param $value, value passed into the form
     * @param $policy, the rule that e set e.g min = 5
     * @return bool, true | false
     */
    protected static function unique($column, $value, $policy)
    {
        if($value != null && !empty(trim($value))){
            $query = "SELECT * FROM $policy where $column = ?";

            $result = self::$db->run($query, [$value]);


            $row = $result->fetch(PDO::FETCH_ASSOC);


            if ($row)
            {
                return false;
            }

        }
        return true;
    }

    protected static function required($column, $value, $policy)
    {
        return $value !== null && !empty(trim($value));
    }

    protected static function minLength($column, $value, $policy)
    {
        if($value != null && !empty(trim($value))){
            return strlen($value) >= $policy;
        }
        return true;
    }

    protected static function maxLength($column, $value, $policy)
    {
        if($value != null && !empty(trim($value))){
            return strlen($value) <= $policy;
        }
        return true;
    }

    protected static function email($column, $value, $policy)
    {
        if($value != null && !empty(trim($value))){
            return filter_var($value, FILTER_VALIDATE_EMAIL);
        }
        return true;
    }

    protected static function mixed($column, $value, $policy)
    {
        if($value != null && !empty(trim($value))){
            if (!preg_match('/^[A-Za-zΑ-Ωα-ω0-9]+$/', $value))
            {
                return false;
            }
        }
        return true;
    }

    /**
     * check if date is valid
     * @param $column
     * @param $value
     * @param $policy
     * @return bool
     */
    private static function validDate($column, $value, $policy)
    {
        if($value != null && !empty(trim($value)))
        {
            list($y, $m, $d) = explode('-', $value);

            return checkdate($m, $d, $y);

        }
    }


    /**
     * returns true if value is a past date
     * @param $column
     * @param $value
     * @param $policy
     * @return bool
     * @throws \Exception
     */
    private static function pastDate($column, $value, $policy)
    {
        if($value != null && !empty(trim($value)))
        {
            date_default_timezone_set('Europe/Athens');

            $date = new \DateTime($value);

            $now = new \DateTime();
            return $date >= $now;
        }
    }

    /**
     * return true if dateFrom is earlier or equal to dateTo
     * @param $dateFrom
     * @param $dateTo
     * @return bool
     */
    public function compareDates($dateFrom, $dateTo)
    {
        date_default_timezone_set('Europe/Athens');
        $dateF = new \DateTime($dateFrom);
        $dateT = new \DateTime($dateTo);

        return $dateF<=$dateT;
    }

    /**
     * Set specific error
     * @param $error
     * @param null $key
     */
    public function setError($error, $key = null)
    {
        if($key){
            self::$error[$key][] = $error;
        }else{
            self::$error[] = $error;
        }
    }

    /**
     * return true if there is validation error
     * @return bool
     */
    public function hasError()
    {
        return count(self::$error) > 0 ? true : false;
    }

    /**
     * Return all validation errors
     * @return array
     */
    public function getErrorMessages()
    {
        return self::$error;
    }

}