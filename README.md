# TaskHandler
Swiftqueue Developer Technical Assessment

## Installation
*From project root*

```
php migrations/migrations.php
php migrations/seeders.php
```

## Running

```
php -S 127.0.0.1:8000 -t public
```

### Logins from the Seeder

- admin / admin123
- user1 / user1
- user2 / user2
- user3 / user3

## Use

The application uses an SQLite database (`data` folder), but it has the capability to use MySQL database as well. It can only be used after logging in.

The main screen shows a list of pending tasks. The administrator sees the full list of tasks, while users only see their own.

The administrator can access the administration dashboard. This contains 2 more pages. One is for managing users, the other is for managing tasks.

You cannot register on the site, but the administrator can create, modify or delete users. New administrators can also be appointed. The site does not allow the first administrator to be deleted for security reasons.

Of course, the application is very simple, there is no email management and notification, password reset and other functions required for a more serious application.

## Used Sources

- [Reset CSS by Josh Comeau](https://www.joshwcomeau.com/css/custom-css-reset/)
- [Bootstrap 3.4](https://getbootstrap.com/docs/3.4/) with [Flatly theme](https://bootswatch.com/3/flatly/) CSS & Icons
- [Moment Js](https://momentjs.com/)
- [Pikaday Js](https://github.com/Pikaday/Pikaday)
- [ParseDown](http://parsedown.org)

## Some notes

The CRUD class comes from one of my old projects. I made it for PHP 7.6, but with some improvements it works fine on PHP 8 as well.

I almost forgot to upload it to GitHub. Sorry, it was half done when I created the repository.

Of course, it would have been much faster and easier to build the application with Laravel, but I wanted to show that I can work outside of frameworks.

The entire work took about 18 hours, of which 2 hours were testing and 1 hour was documentation.