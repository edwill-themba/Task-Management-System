# Edwill Themba Task Management System
1. How to run this application 
-Windows Requirements
 Lets assume you have install xampp and npm
 a.You need copy this application folder C:/xampp/htdocs/
 b.Go to the browser of choice and copy and paste this url http://localhost/phpmyadmin/
 c. Create a database called taskmanagementdb
 d. Open  3 terminals of choice and cd C:/xampp/htdocs/edwill-themba-task-management-system
    -On One terminal type php artisan serve and press enter
    -On the second terminal type npm run dev
    -On the third termina run php artisan migrate and then run php artisan db:seed
e. Copy this url to the browser http://localhost:8000
2. The application rules
  -A Supervisor/User can create,update and delete his/her own task.
  -A Supervisor can create,update and delete user task that was only created by him/her.
  -A Supervisor cannot create a task for another supervisor.
  -A User cannot create a task for another user or supervisor.
  -A Task is created  2 days before for example today is 19-12-2022 tasks must start on     21-12-2022
  -A User/Supervisor cannot have 2 more task on one day
  -Tasks that are expired are automatically deleted
3. How does the application works
 NB:Only registered users that can create task.You need to create your own users both supervisors and users log with them in order to test data,you cannot test data with the one that seeded because I though it will be necessary for the application to have security.
   -One the home screen the application display all the tasks
   -You need to register and log in order to create tasks
   -when you log in with valid credentials the application will direct to you to my tasks where
    your task will be displayed is where you can update and delete tasks and the option to create user task called create user task.
   -If you want to add your own task there is a link called Add Task.
  
  

