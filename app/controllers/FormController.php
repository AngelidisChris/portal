<?php

use App\Classes\ErrorHandler;
use App\Classes\Mail;
use App\Classes\Redirect;
use App\Classes\Request;
use App\Classes\Session;
use App\Classes\ValidateRequest;

require_once __DIR__ . '/../functions/helper.php';

class FormController extends Controller
{
    protected $app;
    protected $user;

    public function __construct()
    {
        $this->app = $this->model('Form');
        $this->user = $this->model('User');
    }

    public function index()
    {

        $this->show();
    }

    public function show()
    {
        // check if user is logged in or have permissions to request page
        checkPermissions(3);

        // show different form for different types of user
        if (Session::get('user_type') === 1)
            echo $this->twig->render('form\show.html.twig',[]);
        // user creation form for admins
        else
        {
            // check which form was submitted by checking hidden input field
            // update user
            if(isset($_POST['user_id']) && !empty($_POST['user_id']))
            {
                // get user data to populate fields

                $this->user->getUser($_POST['user_id']);

                $user_data =
                    [
                        'firstname' => $this->user->getFirstname(),
                        'lastname' => $this->user->getLastname(),
                        'email' => $this->user->getEmail(),
                        'type' => $this->user->getType()
                    ];

                echo $this->twig->render('auth\admin\update_user.html.twig',['user_data' => $user_data]);
            }
            // create new user
            else
                echo $this->twig->render('auth\admin\create_user.html.twig',[]);

        }

    }

    /**
     * Processes the form data, either sending it to the model to be
     * saved into the database, or displays errors if the required
     * fields are not present.
     */
    public function createAppForm()
    {

        // check if user is logged in or have permissions to request page
        checkPermissions(1);

        if (Request::has('post'))
        {
            $requiredFields = array('date_from', 'date_to', 'reason');
            $rules = [
                'date_from' => ['required' => true, 'pastDate' => true],
                'date_to' => ['required' => true, 'pastDate' => true],
                'reason' =>['maxLength' => 1000]
            ];

            $data = array();

            // load all POST variables in an array
            foreach($requiredFields as $_field)
            {
                $data[$_field] = ($_POST[$_field]);

            }

            // initialise input validator
            $validate = new ValidateRequest();
            $validate->abide($_POST, $rules);

            // check date_from > date_to
            if($validate->compareDates($data['date_from'], $data['date_to']) === false)
            {
                // log error
                $validate->setError('Vacation Start date is after vacation end date', 'date_mismatch');
            }

            // check for user input errors before creating application
            if ($validate->hasError())
            {

                $errors = $validate->getErrorMessages();
                echo $this->twig->render('form\show.html.twig',['errors' => $errors]);
                return;
            }

            // create application
            $this->app->create(Session::get('user_id'),$data);

            // get user to pass email data
            $this->user->getUser(Session::get('user_id'));

            $app_data= $this->app->getApp($this->app->getLastAppId()['LAST_INSERT_ID()']);
            //send mail to supervisor
            $mail = new Mail();

            //email data
            $email_data = [
                'to' => $this->user->getSupervisorEmail(),
                'subject' => 'Request for vacation',
                'date_from' => $data['date_from'],
                'date_to' => $data['date_to'],
                'reason' => $data['reason'],
                'procession_code' => $app_data['procession_code']
            ];


            // currently not working as intended cause of gmail auth
            // rememebr we are using gmail.smtp at  localhost
            if($mail->send($email_data, 'supervisor'))
            {
                echo 'email send successfully';
            }
            else
            {
                echo 'email did not send';
                //echo 'email did not send';Redirect::to('/home');
            }
        }
        Redirect::to('/home');
    }

    /**
     * create new user
     * take info from create user form
     */
    public function createUserForm()
    {
        // check if user is logged in or have permissions to request page

        checkPermissions(2);

        if (Request::has('post'))
        {
            $requiredFields = array('user_type', 'firstname', 'lastname', 'email', 'password');
            $data = array();


            $data['supervisor_id'] = Session::get('user_id');

            foreach ($requiredFields as $_field) {

                $data[$_field] = $_POST[$_field];

            }


            // apply input rules
            $rules = [

                'user_type' => ['required' => true],
                'firstname' => ['required' => true, 'maxLength' => 250, 'mixed' => true],
                'lastname' => ['required' => true, 'maxLength' => 250, 'mixed' => true],
                'email' => ['required' => true, 'unique' => 'users', 'email' => true],
                'password' => ['required' => true, 'minLength' => 4, 'maxLength' => 50]
            ];
            // initialise input validator
            $validate = new ValidateRequest;
            $validate->abide($_POST, $rules);

            // check for user input errors before creating application
            if ($validate->hasError()) {
                $errors = $validate->getErrorMessages();
                echo $this->twig->render('auth\admin\create_user.html.twig', ['errors' => $errors]);
                return;
            }

            // insert supervisor id in data array
            $data['supervisor_id'] = Session::get('user_id');

            //create user
            $this->user->create($data);

        }
        Redirect::to('/home');
    }

    /**
     * update user info
     * take info from update user form
     */
    public function updateUserForm()
    {

        // check if user is logged in or have permissions to request page
        checkPermissions(2);
        if (Request::has('post'))
        {
            $requiredFields = array('firstname', 'lastname', 'password', 'email', 'user_type', 'old_email');
            $user_data = array();

            $user_data['supervisor_id'] = Session::get('user_id');

            foreach ($requiredFields as $_field) {
                $user_data[$_field] = $_POST[$_field];
            }

            // apply input rules
            $rules = [
                'firstname' => ['required' => true, 'maxLength' => 250, 'mixed' => true],
                'lastname' => ['required' => true, 'maxLength' => 250, 'mixed' => true],
                'user_type' => ['required' => true]

            ];
            //check if email changed or is the same to apply rules
            if($user_data['email'] !== $user_data['old_email'])
                $rules['email'] = ['required' => true, 'unique' => 'users', 'email' => true];


            // if password field not empty, then we apply rule
            if (!empty($user_data['password']))
                $rules['password'] = ['required' => true, 'minLength' => 4, 'maxLength' => 50];

            // initialise input validator
            $validate = new ValidateRequest;
            $validate->abide($_POST, $rules);

            // check for user input errors before creating application
            if ($validate->hasError()) {
                $errors = $validate->getErrorMessages();
                echo $this->twig->render('auth\admin\update_user.html.twig', ['errors' => $errors, 'user_data' => $user_data]);
                return;
            }

            // check if admin changed email property
            $result = $this->user->getByEmail($user_data['old_email']);
            $id = $result['id'];

            $user_data['id'] = $id;
            // update user
            $this->user->update($user_data);


        }

        Redirect::to('/home');
    }
}