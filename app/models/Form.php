<?php

require_once __DIR__ . '/../functions/helper.php';

class Form extends Model
{
    private $user_id;
    private $submitted_date;
    private $dateFrom;
    private $dateTo;
    private $reason;
    private $procession_code;
    private $processed;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * create new application entry in db
     * @param $user_id
     * @return bool
     * @throws Exception
     */
    public function create($user_id, $data)
    {
        date_default_timezone_set('Europe/Athens');

        $submitted_date = date('Y-m-d H:i:s');
        $procession_code = generateToken(25);

        $query = "INSERT INTO applications (user_id, submitted_date, dateFrom, dateTo, reason, procession_code) VALUES ('$user_id', '$submitted_date', ?, ?, ?, '$procession_code')";
        try{
            $this->db->run($query, [$data['date_from'], $data['date_to'], $data['reason']]);
        }
        catch (PDOException $exception){
            return false;
        }
        return true;
    }

    public function getLastAppId()
    {

        $query = "SELECT LAST_INSERT_ID()";
        return $this->db->run($query, [])->Fetch(PDO::FETCH_ASSOC);;


    }

    /**
     * save data to model and create new application entry in db
     * @param $user_id
     * @param $data
     * @return bool
     * @throws Exception
     */
    public function saveFormData($user_id, $data)
    {
        date_default_timezone_set('Europe/Athens');

        $this->submitted_date = date('Y-m-d H:i:s');
        $this->dateFrom = $data['date_from'];
        $this->dateTo = $data['date_to'];
        $this->reason = $data['reason'];
        $this->procession_code = generateToken(25);

        return $this->create($user_id, $this->procession_code);
    }

    /**
     * return all applications of user ordered by submitted_date (DESC)
     * @param $user_id
     * @return bool
     */
    public function getApplications($user_id)
    {
        $query = "SELECT * 
                  FROM applications 
                  WHERE user_id = '$user_id'
                  ORDER BY submitted_date DESC ";

        try{
            $result = $this->db->run($query, [])->fetchAll(PDO::FETCH_ASSOC);;
        }
        catch (PDOException $exception){
            return false;
        }
        return $result;
//        foreach ($result as $key => $row)
//        {
//            echo $row['user_id'];
//        }

    }

    public function getApp($app_id)
    {
        $query = "SELECT * 
                  FROM applications 
                  WHERE id = '$app_id'";

        return $this->db->run($query, [])->fetch(PDO::FETCH_ASSOC);;
    }

    public function updateStatus($status, $procession_code)
    {
        $query = "UPDATE applications
                  SET status = '$status', processed = 1
                  WHERE procession_code = '$procession_code'";

        try{
            $this->db->run($query, [])->fetchAll(PDO::FETCH_ASSOC);;
        }
        catch (PDOException $exception){
            return false;
        }
    }

    public function getApplicationByProcessCode($process_code)
    {
        $query = "SELECT * 
                  FROM applications 
                  WHERE procession_code = '$process_code'";

        try{
            $result = $this->db->run($query, [])->fetch(PDO::FETCH_ASSOC);;
        }
        catch (PDOException $exception){
            return false;
        }
        return $result;
    }

    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    public function getDateTo()
    {
        return $this->dateTo;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function isProcessed()
    {
        return $this->processed;
    }

    public function getProcessionCode()
    {
        return $this->procession_code;
    }


}