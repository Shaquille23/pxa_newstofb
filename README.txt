*************************************
*  
*  pxa_newstofb user guide
* 
*************************************

1. Create a Facebook application

  - go to http://www.facebook.com/developer then create new application and fill in the necessary information such as
    - application name
    - description
    - user support address
    - and in the tab "Web Site" fill you website url (We will need this url to use for this extension)

2. Configuration of extension:
    - Create Facebook App configuration Record
    - Select list View and Create new "Facebook App config" 
      * Facebook Application ID;
      * Facebook App secret;
      * The website URL;
      * Save config record;
    - Click on Facebook icon;
      * Grant publish access to faceboook Page
      * IF ("Success SECURITY WARNING") return to popUp window and press {Connect to Facebook and get Page Tocken}
 
  Note: If you remove this application from your profile application setting, and you add the application again then you must generate this code again.


3. Publishing configuration:
    - Create "Publisher to FB config" record
    a. Select your "Facebook Application ID (app Name);
    b. Select page in which you wont to publish news("Publish to Facebook page");
    c. Put your "The website url": the site url of your application when you create the application see (1);
    d. Set "News single view PID": the detail page id of your news page where you have installed the single view of the news;
    e. Set "News Storage PID";
    f. (If you wont to publish considering the categories)
       Set "The UID of tt_news categories"
    g. Log file path: enter the path of the log file;
    h. Path to default image:if news record doesn't has a image;
    i. uncheck "Disable", save it;

4. Running the application

  This application is run by shceduler.
  Go to TYPO3 backend -> Scheduler (4.3.x and 4.4.x) and click on add task then select the task "Publish news to Facebook (pxa_newstofb)" then configure the schedule and cront job to run.
  When the shcedule run, the program will select all the news which not yet mark as publish then will publish all these news to fan page and group page.


Done!


*******************************
*
*  pxa_newstofb ToDo
*
*******************************

1. Add support of Tx_News Categories
2. Remove Log to external file functionality
3. Code cleanup and re-factoring
4. Check Facebook auth API for newer version
