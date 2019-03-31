<?php

class User extends Model
{
    private $id;
    private $email;
    private $password;
    private $firstname;
    private $lastname;
    private $user_type;
    private $supervisor_id;



    public function __construct()
    {

        parent::__construct();

    }

    public function create($user_data)
    {

        $password = $hashed_password = password_hash($user_data['password'], PASSWORD_BCRYPT);

        $query = "INSERT INTO users (firstname, lastname, email, password, user_type, supervisor_id) VALUES (?,?,?,?,?,?)";

        return $this->db->run($query, [$user_data['firstname'],$user_data['lastname'],$user_data['email'],$password,$user_data['user_type'],$user_data['supervisor_id']]);
    }


    public function update($user_data)
    {
        $id = $user_data['id'];

        if ($user_data['password'] === '')
        {
            $query = "UPDATE users
                      SET firstname = ?,lastname = ?, email = ?, user_type = ?
                      WHERE id = '$id'";

            try
            {$this->db->run($query, [$user_data['firstname'], $user_data['lastname'], $user_data['email'], $user_data['user_type']]);

            }catch (PDOException $exception){
                return false;
            }
        }
        else
        {
            $password = $hashed_password = password_hash($user_data['password'], PASSWORD_BCRYPT);
            $query = "UPDATE users
                      SET firstname = ?,lastname = ?, email = ?, user_type = ?, password = ? 
                      WHERE id = '$id'";
            return $this->db->run($query, [$user_data['firstname'], $user_data['lastname'], $user_data['email'], $user_data['user_type'], $password]);
        }

    }

    public function getByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = ?";

        $result = $this->db->run($query, [$email])->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * verify user credentials and populate user
     * @param $email
     * @param $password
     * @return bool
     */
    public function verify($email, $password)
    {
        $query = "SELECT * FROM users WHERE email = ?";

        $result = $this->db->run($query, [$email])->fetch(PDO::FETCH_ASSOC);


        if ($result && password_verify($password, $result['password']))
        {
            // we populate user attributes after validation
            $this->id = $result['id'];
            $this->email = $result['email'];
            $this->lastname = $result['lastname'];
            $this->firstname = $result['firstname'];
            $this->user_type = $result['user_type'];
            $this->supervisor = $result['supervisor_id'];

            return true;
        } else {
            return false;
        }
    }

    /**
     * get user by Id
     * @param $user_id
     * @return $this
     *
     */
    public function getUser($user_id)
    {
        $query = "SELECT * FROM users WHERE id = '$user_id'";

        $result = $this->db->run($query, [])->fetch(PDO::FETCH_ASSOC);
        if($result)
        {
            $this->id = $result['id'];
            $this->email = $result['email'];
            $this->lastname = $result['lastname'];
            $this->firstname = $result['firstname'];
            $this->user_type = $result['user_type'];
            $this->supervisor_id = $result['supervisor_id'];

        }
        return $this;
    }

    /**
     * return array of all employees of supervisor with id = $supervisor_id
     * @param $supervisor_id
     * @return array
     */
    public function getUsersBySupervisorId($supervisor_id)
    {
        $query = "SELECT * FROM users WHERE supervisor_id = '$supervisor_id'";

        $result = $this->db->run($query, [])->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * return supervisor email
     */
    public function getSupervisorEmail()
    {
        $query = "SELECT email FROM users WHERE id = '$this->supervisor_id'";
        $result = $this->db->run($query, [])->fetch(PDO::FETCH_ASSOC);

        return $result['email'];
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getType()
    {
        return $this->user_type;
    }





}