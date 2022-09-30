# sinch_recruit_management
This portal is used to apply job vacancies to public and user can apply for the job
Roles:
 1. Super admin
 2. Admin
 3. User
Super Admin:
    Super admin have all privileges for this application
    Can able to see list of users of the application
    Super admin can Add/Update/Delete/Activate/Deactivate User(s) as well as admin
Admin:
    Super admin can Add /Delete/Activate/Deactivate User(s)
    Can able to see the list of super admin too
    But doesn’t have the privileges to update the user
User
    User should able to register in “Sinch Recruit Management” 
    They can login and apply for the current vacancies (As of now we make it as static)
    User can able to update their profile except email
    If admin deactivate the user profile, they doesn’t able to login until activate
Future Requirements:
  Application:
    Can develop application with responsive
  Admin - Job Vacancy Post:
    Need a option to add “Current Vacancy” details with Openings for, No.of Vacancies, Job Description, Salary, Location, etc.,
      Ex:
        Opening for: PHP Developer
        No.of Vacancies: 04
        Job Description: Lorem Ipsum has been the industry's standard dummy text ever since the 1500s
        Salary: 10 – 13 LPA
        Location: PAN India
    Can integrate “Settings” page for application to upload logo, footer configuration, social media links, etc
User Dashboard:
  So far, we listed list of “Job Vacancies” with static
  We can make it dynamic, If admin able to post
  Option to “Apply” for the job vacancies, as of now they can’t
  Can have the history of the applications
  We can make the dashboard with the status of the application like "Applied -> In-Review -> Selected for Interview -> Interview Process -> Selected/Rejected -> BGC -> On Borded
  
