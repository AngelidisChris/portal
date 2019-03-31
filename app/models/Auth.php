<?php


class Auth extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function create($email, $password)
    {
        $query = "INSERT INTO users (email, password) VALUES (?,?)";

        return $this->db->run($query, [$email,$password]);
    }

    /**
     *
     * verify user
     * @param $email
     * @param $password
     * @param $user_type
     * @return bool
     */
    public function verify($email, $password, $user_type):bool
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $query = "SELECT * FROM users WHERE email = ? AND password = ? AND user_type = ?";

        $result = $this->db->run($query, [$email, $hashed_password, $user_type])->fetch(PDO::FETCH_ASSOC);

        return $result;
    }



}