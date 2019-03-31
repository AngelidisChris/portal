# Vacation management system
Here follows  the implementation of a simple vacation management web application, written in PHP. <br/>The employees can request their vacation online, the manager receives a notification to approve or decline that request, and the information (time used,
balances) is stored within the portal. 

# Disclaimer
This application developed for the purpose of skill training and assessment.
It never was intended to live on a production server, as it could pose risk to stability and security of the system.<br/>
The emails that were used for the demonstration are temporary. No other information shown is valid.


# Application process- workflow

<p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/workflow.PNG?raw=true" align="center"
     title="Application process- workflow" width="320" height="378"></p>


# Application process - with images

1. The employee signs into the portal

<p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/The%20employee%20signs%20into%20the%20portal.PNG?raw=true" align="center"
     title="The employee signs into the portal" width="520" height="178"></p>


2. A list of past applications is displayed, sorted by submission date (descending)
including the following fields:<br>
a. Date submitted<br>
b. Dates requested (vacation start - vacation end)<br>
c. Status (pending/approved/rejected)<br>
<p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/A%20list%20of%20past%20applications%20is%20displayed.PNG?raw=true" align="center"
     title="A list of past applications is displayed" width="520" height="278"></p>

3. A button “submit request” appears above the list. The employee clicks on the
button to visit the submission form

4. The submission form includes the following fields:<br>
a. Date from (vacation start)<br>
b. Date to (vacation end)<br>
c. Reason (textarea)<br>
d. Submit button
<p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/The%20submission%20form.PNG?raw=true" align="center"
     title="submission form" width="520" height="278"></p>

5. After the employee fills-in the fields and clicks on “submit”, he/she is taken
back to the list of applications
<p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/back%20to%20the%20list%20of%20applications.png?raw=true" align="center"
     title="back to the list of applications" width="520" height="278"></p>

6. Upon submitting the application, an email is sent to the portal administrator.
The email includes the following body:
“Dear supervisor, employee {user} requested for some time off, starting on
{vacation_start} and ending on {vacation_end}, stating the reason:
{reason}
Click on one of the below links to approve or reject the application:
{approve_link} - {reject_link}”

7. The administrator (who acts as a supervisor as well) clicks on one of the
“approve” or “reject” links to mark the application accordingly.
<p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/an%20email%20is%20sent%20to%20the%20portal%20administrator.png?raw=true" align="center"
     title="email is sent to the portal administrator" width="520" height="178"></p>

8. As soon as the administrator makes a selection, another email goes out to the
user notifying him/her of the application outcome, with the following body:
“Dear employee, your supervisor has {accepted/rejected} your application
submitted on {submission_date}.”

<p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/another%20email%20goes%20out%20to%20the%20employee.png?raw=true" align="center"
     title="email is sent to the employee" width="520" height="178"></p>

<p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/application%20approved.PNG?raw=true" align="center"
     title="application approved" width="520" height="178"></p>

# User provisioning process - with images

The portal includes an administration page where the designated administrator can
create users. The process can be summarized as follows:
1. The administrator signs in with his/her credentials
   <p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/admin/1.%20The%20administrator%20signs%20in.PNG?raw=true" align="center"
     title="The administrator signs in" width="520" height="178"></p>
2. He/she views a list of existing users, with the following fields:<br/>
a. User first name<br/>
b. User last name<br/>
c. User email<br/>
d. User type (employee/admin)<br/>
<p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/admin/2.%20He%20views%20a%20list%20of%20existing%20users.PNG?raw=true" align="center"
     title="list of existing users" width="520" height="178"></p>

3. On top of the page there is a button to “create user”. Clicking on it takes the
admin to the user creation page, which includes a form with the following
fields:<br/>
a. First name<br/>
b. Last name<br/>
c. Email<br/>
d. Password<br/>
e. User type (drop down, admin/employee)<br/>
f. Create button<br/>
<p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/admin/3.user%20creation%20page.PNG?raw=true" align="center"
     title="user creation page" width="520" height="278"></p>
4. In the list of users, each entry is clickable. Clicking on it takes the admin to the
user properties page, which includes the same form as the “creation” page,
only this time all fields are pre-populated with the user’s entries (except for
the password field).
<p align="center"><img src="https://github.com/AngelidisChris/mvc_registration_system/blob/master/images/admin/4.update%20existing%20user.PNG?raw=true" align="center"
     title="update existing user" width="520" height="278"></p>

# Installation Process
<h3> Lets start with environment installation using XAMPP for Windows.</h3>

To add virtual hosts in XAMPP open the Virtual Hosts Apache configuration file httpd-vhosts.conf  from **C:\xampp\apache\conf\extra**

At the end of the file add the following 4 lines. These 4 lines are used to allow access to the XAMPP configuration pages (to access phpMyAdmin etc) by using the URL http://localhost
```diff
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/portal/public"
    ServerName portal.test
</VirtualHost>
```
**Make sure DocumentRoot is pointing to public folder.**

Now lets add entries to Windows Host file

The Windows Host file is called hosts (without any file extension) and can be found in the following directory: **C:\Windows\System32\drivers\etc**
Add the following line at the end of the hosts file:


```diff
127.0.0.1 portal.test
```

Start Apache and MySQL  from XAMPP control panel and that's it, environment is ready.

<h3>DATABASE SETUP</h3>

Import the **portal.sql** file to phpMyAdmin client that it's included to the repository to create the database of the app. It will also seed data to the database. A **master** user with **`password = 1234`** and **user_type categories**.

```diff
# password is 1234
INSERT INTO `users` (`id`, `email`, `password`, `firstname`, `lastname`, `user_type`, `supervisor_id`) VALUES
(30, 'master@test.test', '$2y$10$KJx1jrf.NqJ/GCse8SG8qussLnYUiPff0BAMdy8BdUUsqrL7LJhIe', 'master', 'master', 2, NULL);

INSERT INTO `user_types` (`id`, `type_name`) VALUES
(1, 'employee'),
(2, 'admin');
```

**ATTENTION**: Due to programming error from my part user_types are static in html forms so you have to stick with this convention.

<h3>PROJECT DEPENDENCIES</h3>

Project is using Composer to manage dependencies.

First install Composer to windows and then navigate to the root of your git repo, where your `composer.json` file is and run the  command:

```DIFF
$ composer update
```

<h3>ENVIRONMENT VARIABLES</h3>

Application is using **PHP Dotenv** package to load environment variables from `.env` to `getenv()` automagically. At the root of the git repo is included `.git.example`, rename it to `.env` and fill the variables.

```diff
APP_NAME = Portal
APP_URL= http://portal.test
APP_ENV=local
#DATABASE
DB_DRIVER = 'mysql'
DB_HOST = 'localhost'
DB_NAME = 'portal'
DB_USERNAME = 'root'
DB_PASSWORD = ''
DB_CHAR = 'utf8'

# Mail Credentials
# Use only gmail account or create a local smtp server
# This is the mail address from which the application will use to send emails to the users
EMAIL_USERNAME = enterEmail@gmail.com
EMAIL_PASSWORD = yourGmailPass
SMTP_PORT=587
SMTP_HOST=smtp.gmail.com
ADMIN_EMAIL=enterEmail@gmail.com
```

<h3>MAILING SERVICE SETUP</h3>

We send emails to users using the Gmail smtp server. In order to avoid any restriction to our mailing services we have to TURN ON `Allow less secure app access` from our google account settings.

I **strongly advice** you to use a **temporary  Gmail account** for the application email as well as temporary emails for users to implement this service, otherwise you could create a local smtp server. Temporary emails for user accounts can be created at https://temp-mail.org. Use one email from Firefox browser for the supervisor account and another one from Chrome for the employee.

Environment is now ready.

# Usage

For a better user experience I advice you to execute the following steps:


1. Open up chrome browser.
2. Login to master user (follow **Database Setup** guide if you don't have already created **master** user and **user_type** categories).
3. Create admin user with a temp email created at https://temp-mail.org (opened in Chrome too).
4. Logout from master.
5. Login to new admin user.
6. Create an employee user  with a temp email (created at https://temp-mail.org opened from Mozilla), now you serve as supervisor of this employee.
7. From Mozilla browser login to employee user and create an application form.
8. Email message will be send to Admin, who will approve or reject the application.
9. Another email will be send to the employee to inform him.

# Tips

1. Create temporary emails for easier and safer testing.

2. The admin user, who will create the employee user, will also serve as his supervisor and receive email requests from him.  

3. Master user should only be used to create the first admin user, who, later will create the first employee account.

4. You can also create a master user on the spot by typing the address http://portal/auth/createMaster `userPass:1234`



# Known bugs - Unpleasant behaviors

1. `user_type` categories are fixed on HTML pages such as `create User` form and not dynamically taken from Db. That's why we must seed the `user_type` table exactly as shown in **Database Setup** Section.
2. No support for Greek characters yet.
3. Gmail smtp server is not working as intended. Should consider changing to local smtp. 

