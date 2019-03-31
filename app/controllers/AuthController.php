<?php


use App\Classes\ErrorHandler;
use App\Classes\Mail;
use App\Classes\Redirect;
use App\Classes\Session;

class AuthController extends Controller
{
    protected $auth;
    protected $form;
    protected $user;

    public function __construct()
    {
        $this->auth = $this->model('Auth');
        $this->form = $this->model('Form');
        $this->user = $this->model('User');
    }

//    to be changed
    public function index()
    {
        $this->login();
    }



    public function login()
    {

        $password=$email="";


        // form submited with POST get_class_method
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {

            if (isset($_POST['email']))
                $email = $_POST['email'];


            if (isset($_POST['password']))
                $password = $_POST['password'];



            // create new user object
            $user = new User();

            // verify user credentials
            // successfull user login
            if($user->verify($email,$password) === true)
            {
                // set session Vars
                Session::add('logged_in', 'true');

                Session::add('name', $user->getFirstname());

                Session::add('user_id', $user->getId());

                Session::add('user_email', $user->getEmail());

                Session::add('user_type', $user->getType());


            }
            else
            {
                $errors[][] = "The combination of email/password is wrong";
                echo $this->twig->render('auth\signin.html.twig',['errors' => $errors]);
                return;
            }

        }

        Redirect::to(getenv("APP_URL"));
    }

    public function logout()
    {
        if (isset($_SESSION))session_destroy();
            Redirect::to(getenv("APP_URL").'/home');
    }


    /**
     * we process the response of administrator email
     * @param $code
     * @param $approved
     */
    public function evaluate($code,$approved)
    {
        checkPermissions(2);

        $result = $this->form->getApplicationByProcessCode($code);


        if($result['processed'] === 0)
        {
            if($approved == 1)
                $this->form->updateStatus('approved',$code);
            else
                $this->form->updateStatus('rejected',$code);
            $employee = $this->user->getUser($result['user_id']);
            // send email back to employee

            $email_data = [
                'to' => $employee->getEmail(),
                'subject' => 'Application Outcome',
                'submitted_date' => $result['submitted_date'],
                'status' => 'approved'
            ];
            if ($approved == 1)
                $email_data['status'] = 'approved';

            else
                $email_data['status'] = 'rejected';

            $mail = new Mail();
            $mail->send($email_data, 'employee');

            Redirect::to('/home');


        }
        else{
            ErrorHandler::view('verified_application','Application has already been evaluated.');
        }
    }

    /**
     * creates  master admin user to seed database for testing
     * works only on local environment
     */
    public function createMaster()
    {
        if(getenv('APP_ENV') == 'local')
        {
            $user_data = [
              'firstname' => 'master',
              'lastname' => 'master',
              'email' => 'master@test.test',
              'user_type' => 2,
              'password' => '1234'
            ];

            $this->user->create($user_data);
            Redirect::to('/home');
        }
    }


}