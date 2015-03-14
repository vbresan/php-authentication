# Installation instructions #

Step by step instructions:

## 1. Extract the zip file ##
## 2. Set database connection parameters ##

> PHP Authentication service uses database to store data. All most frequently used databases are supported (MySQL, PostgreSQL, SQLite). Database is not accessed directly but through EZPDO, lightweight data persistence library that comes with the product. It is not necessary for you to know anything about the mentioned library, you just need to edit the following configuration file:
> > `./config.xml`

> Look for `<default_dsn>` tags and uncomment the one depending on the database type that you use. Put the right username, password and database name in connection string. Please note that the database already has to be created and user has to be given the right credentials, including the one necessary for table creation. The following MySQL statements might be helpful in that task:
```
    CREATE DATABASE databasename DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
    GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX ON databasename . * TO 'username'@'localhost';
    SET PASSWORD FOR 'username'@'localhost'=PASSWORD('password');
```
> Syntax might differ slightly if you are using database other than MySQL.

## 3. Call `_buildTables.php` script ##

> If you have set everything from the previous step correctly, this script will create the following tables: [LastLogin](DBTables#LastLogin.md), [MaskedCookieData](DBTables#MaskedCookieData.md), [TempInvitation](DBTables#TempInvitation.md), [TempVerification](DBTables#TempVerification.md) and [User](DBTables#User.md). Also in local file system, folder `compiled` and file `ezpdo.log` will be created. After you are done with this step, you can delete `_buildTables.php` script.

## 4. Customize `verify.php` file ##

> Link to this file is sent to user via e-mail upon registration as a request for account verification. You can customize this file and give it a desired look, just be sure to keep the `include_once` tag at the top and `$userMessage` variable wherever you want.

## 5. Customize `properties.ini` file ##

> Properties file contains several sections, we'll describe each one right here.

> ### Section `[General]`: ###

> PHP Authentication comes with multi-language support. You select your preferred language by setting the `DefaultLanguage` parameter. You can add your own translation (or customize existing) by modifying the `messages.php` file.

> Each registered user can send registration invitations to friends. Number of invitations that user can send is defined by parameter `NumberOfInvitations`.

> If `CheckForRegistrationCode` parameter is set to 1, registration code is sent along with each invitation and new users can register only by providing that code. Think of it as of 'closed' web site support - new users can register only if they are invited by existing users. If `CheckForRegistrationCode` is set to 0, invitations can still be sent around but they will not contain registration code, since they are not needed now.

> If `SendVerificationEMail` parameter is set to 1, e-mail with verification link is sent to user after his registration. User will not be able to log in until he verifies his account by opening this link. If parameter is set to 0, user's account will be verified on registration and verification mail will not be sent.

> With `MessageFormat` parameter you define the format of PHP Authentication's output messages. The following two formats are available: `XML` (default) and `JSON`.

> ### Section `[UserName]`: ###

> `MinLength` and `MaxLength` define username minimum and maximum lengths, respectively. Maximum length can not be greater than 64 and username must be alphanumeric string. If `AllowEMailAddress` is set to 1, username can have e-mail address syntax and length constraints do not apply.

> ### Section `[VerificationMail]`: ###

> Upon registration, e-mail is sent to user containing the link for account verification. In order to verify the account, user is required to visit the link before the first login.

> Link URL is defined with the parameter `Link`. Enter your hostname and path to `verify.php` script, leave the remaining part untouched.

> Parameter `MailSubject` defines the subject of verification request e-mail, while parameters `MailBodyFile` and `MailHeadersFile` define paths to mail body and mail headers templates respectively. Customize templates as you wish, just don't forget to leave `{$link}` string inside the mail body.

> ### Section `[LoginDataMail]`: ###

> In case user forgets his username or password, he can make a request for forgotten credentials. Username and new password will be sent via e-mail to address that he used when he registered his account.

> Parameter `MailSubject` defines the subject of e-mail, while parameters `MailBodyFile` and `MailHeadersFile` define paths to mail body and mail headers templates respectively. Customize templates as you wish, just don't forget to leave `{$userName}` and `{$password}` strings inside the mail body.

> ### Section `[InvitationMail]`: ###

> This section defines the look of invitation mail.

> Parameter `MailSubject` defines the subject of e-mail which will contain inviter's name if the `{$name}` string is included. Parameter `MailBodyTailFile` defines the tail that will be appended to custom message that is inviter sending. If registration codes are used, this tail should contain `{$registrationCode}` string. Finally, parameter `MailHeadersFile` defines path to mail headers template file.

> ### Section `[DaysToExpire]`: ###

> In this section you can set the number of days after which the invitations and unverified accounts will expire. For that purpose you use `TempInvitation` and `TempVerification` parameters respectively. When the value is set to 0, data is not expired. Deletion of old data is actually done by the `deleteExpiredData.php` script.

> ### Section `[EZPDO]`: ###

> You don't need to modify this section unless you want to move EZPDO directory somewhere else, or you already have it somewhere on your system. In that case you should customize `RelativePath` parameter and make it point wherever you want. Just keep in mind that it is relative path, so keep the starting `./` and omit the trailing slash.

## 6. Set up cron to run `deleteExpiredData.php` once a day ##

> Instructions for this step are different on each hosting environment. If you don't know how to set up scheduler to run the script in regular intervals, the best would be to ask your hosting provider for help.

**That should be it!** You are ready to start using PHP Authentication web service. Continue by checking the API.