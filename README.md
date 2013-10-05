piGoogleAnalyticsAuthenticator
==============================

Symfony1 plugin for Diem CMF providing a service and a task to authenticate Google Analytics 

Usage
-----

run pi:authenticate-google-analytics --user=yourusername@google.com --pass=YourPassword

To make it automatic set a cron job.

You can store user and pass in app.yml and avoid passing it to task everytime
Set:
```yml
app:
  ga:
    user: yourusername@google.com
    pass: YourPassword
```
