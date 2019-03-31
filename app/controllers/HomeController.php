<?php


use App\Classes\Session;


class HomeController extends Controller
{

    protected $user;
    protected $form;


    public function __construct()
    {
        $this->user = $this->model('User');
        $this->form = $this->model('Form');
    }

    public function index()
    {

        $name = '';
        if(Session::has('name'))
            $name = Session::get('name');

        if (!Session::has('logged_in'))
        {
            session_destroy();
            Session::add('logged_in', 'false');

        }

        // if no user is logged in, go to login page
        if(Session::get('logged_in') === 'false')
        {
            echo $this->twig->display('auth\signin.html.twig',
                ['name' => $name]);
            return;
        }

        // if user is logged in, load all applications and go to home page
        else{

            $user_id = Session::get('user_id');

            // check type of user and render the appropriate view
            // employee
            if(Session::get('user_type') === 1)
            {
                // load all applications of employee
                $result = $this->form->getApplications($user_id);

                echo $this->twig->display('/home/index.html.twig',
                    ['name' => $name,
                     'result' => $result]
                );
                return;
            }
            // supervisor
            else
            {
                // load all employees
                $result = $this->user->getUsersBySupervisorId($user_id);

                echo $this->twig->display('/auth/admin/user_list.html.twig',
                    ['name' => $name,
                        'result' => $result]
                );
                return;
            }
        }
    }
}