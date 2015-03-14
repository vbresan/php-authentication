# API #

## Script name: `register.php` ##

POST Parameters:

> `userName` - alphanumeric string. Minimum and maximum length are defined in [properties.ini](InstallationInstructions#5._Customize_properties.ini_file.md) file.

> `password` - alphanumeric string. Length 32 characters. It should be MD5 hash of user password obtained on client side.

> `eMailAddress` - string, syntactically valid e-mail address.

> `registrationCode` - alphanumeric string up to 10 characters long. It is needed only if it is defined so in [properties.ini](InstallationInstructions#5._Customize_properties.ini_file.md) file.

Modified tables:

> [TempInvitation](DBTables#TempInvitation.md) - if new user has been invited by one of the existing users, or registration code is mandatory, entry containing e-mail address or registration code is deleted from this table.

> [TempVerification](DBTables#TempVerification.md) - new entry containing verification code is created if the parameter `SendVerificationEMail` in section `[General]` of file [properties.ini](InstallationInstructions#5._Customize_properties.ini_file.md) is set to 1.

> [User](DBTables#User.md) - new entry is created.

XML output on success:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="UserRegistration">
    <Error>false</Error>
    <UserName>anonymous</UserName>
    <Message>Account verification request sent to your e-mail address</Message>
</XMLMessage>
```
> or:
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="UserRegistration">
    <Error>false</Error>
    <UserName>anonymous</UserName>
    <Message>User registered</Message>
</XMLMessage>
```
Possible XML outputs on failure:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="UserRegistration">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>Invalid input</Message>
</XMLMessage>
```
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="UserRegistration">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>Unknown registration code</Message>
</XMLMessage>
```
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="UserRegistration">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>User name not available or e-mail address already registered in system</Message>
</XMLMessage>
```

JSON output on success:
> 
```
{
 "type":"UserRegistration",
 "error":false,
 "userName":"anonymous",
 "message":["Account verification request sent to your e-mail address"]
}
```
> or:
```
{
 "type":"UserRegistration",
 "error":false,
 "userName":"anonymous",
 "message":["User registered"]
}
```

Possible JSON outputs on failure:
> 
```
{
 "type":"UserRegistration",
 "error":true,
 "userName":"anonymous",
 "message":["Invalid input"]
}
```
```
{
 "type":"UserRegistration",
 "error":true,
 "userName":"anonymous",
 "message":["Unknown registration code"]
}
```
```
{
 "type":"UserRegistration",
 "error":true,
 "userName":"anonymous",
 "message":["User name not available or e-mail address already registered in system"]
}
```

---


## Script name: `unregister.php` ##

POST Parameters: None

Modified tables:

> [LastLogin](DBTables#LastLogin.md) - user's entry is deleted.

> [User](DBTables#User.md) - user's entry is deleted.

XML output on success:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="UnregisterUser">
    <Error>false</Error>
    <UserName>anonymous</UserName>
    <Message>User unregistered</Message>
</XMLMessage>
```
XML output on failure:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="UnregisterUser">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>In order to close account, you have to be logged in</Message>
</XMLMessage>
```
JSON output on success:
> 
```
{
 "type":"UnregisterUser",
 "error":false,
 "userName":"anonymous",
 "message":["User unregistered"]
}
```
JSON output on failure:
> 
```
{
 "type":"UnregisterUser",
 "error":true,
 "userName":"anonymous",
 "message":["In order to close account, you have to be logged in"]
}
```

---


## Script name: `verify.php` ##

POST Parameters: None

GET Parameters:

> `verificationCode` - string, 21 characters long.

Modified tables:

> [TempVerification](DBTables#TempVerification.md) - entry containing given verification code is deleted.

> [User](DBTables#User.md) - for specific user, a verification flag is set to true.

Output:

> Script doesn't give any output, in fact it is customizable web page. More about customization can be read [here](InstallationInstructions#4._Customize_verify.php_file.md).

---


## Script name: `logIn.php` ##

POST Parameters:

> `userName` - alphanumeric string. Minimum length 2, maximum 10 characters.

> `password` - alphanumeric string. Length 32 characters. It should be MD5 hash of user password obtained on client side.

Modified tables:

> [LastLogin](DBTables#LastLogin.md) - upon login, timestamp and user's IP address is stored.

> [MaskedCookieData](DBTables#MaskedCookieData.md) - user's login identifier that is kept in cookie is stored here as well.

XML output on success:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="LogIn">
    <Error>false</Error>
    <UserName>username</UserName>
    <Message>Logged in</Message>
</XMLMessage>
```
Possible XML outputs on failure:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="LogIn">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>Invalid input</Message>
</XMLMessage>
```
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="LogIn">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>You haven't verified your account. Please visit the verification link that has been sent to your e-mail address.</Message>
</XMLMessage>
```
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="LogIn">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>Wrong username and/or password</Message>
</XMLMessage>
```
JSON output on success:
> 
```
{
 "type":"LogIn",
 "error":false,
 "userName":"username",
 "message":["Logged in"]
}
```
Possible JSON outputs on failure:
> 
```
{
 "type":"LogIn",
 "error":true,
 "userName":"anonymous",
 "message":["Invalid input"]
}
```
```
{
 "type":"LogIn",
 "error":true,
 "userName":"anonymous",
 "message":["You haven't verified your account. Please visit the verification link that has been sent to your e-mail address."]
}
```
```
{
 "type":"LogIn",
 "error":true,
 "userName":"anonymous",
 "message":["Wrong username and/or password"]
}
```

---


## Script name: `logOut.php` ##

POST Parameters: None

Modified tables:

> [MaskedCookieData](DBTables#MaskedCookieData.md) - entry containing user's login identifier is deleted.

XML output:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="LogOut">
    <Error>false</Error>
    <UserName>anonymous</UserName>
    <Message>Logged out</Message>
</XMLMessage>
```
JSON output:
> 
```
{
 "type":"LogOut",
 "error":false,
 "userName":"anonymous",
 "message":["Logged out"]
}
```

---


## Script name: `requestLoginData.php` ##

POST Parameters:

> `eMailAddress` - string, syntactically valid e-mail address.

Modified tables: None

XML output on success:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="RequestLoginData">
    <Error>false</Error>
    <UserName>anonymous</UserName>
    <Message>Account data sent to your e-mail address</Message>
</XMLMessage>
```
Possible XML outputs on failure:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="RequestLoginData">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>Invalid input</Message>
</XMLMessage>
```
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="RequestLoginData">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>Your request can not be currently fulfilled. Please try again a bit later.</Message>
</XMLMessage>
```
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="RequestLoginData">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>Unknown e-mail address</Message>
</XMLMessage>
```
JSON output on success:
> 
```
{
 "type":"RequestLoginData",
 "error":false,
 "userName":"anonymous",
 "message":["Account data sent to your e-mail address"]
}
```
Possible JSON outputs on failure:
> 
```
{
 "type":"RequestLoginData",
 "error":true,
 "userName":"anonymous",
 "message":["Invalid input"]
}
```
```
{
 "type":"RequestLoginData",
 "error":true,
 "userName":"anonymous",
 "message":["Your request can not be currently fulfilled. Please try again a bit later."]
}
```
```
{
 "type":"RequestLoginData",
 "error":true,
 "userName":"anonymous",
 "message":["Unknown e-mail address"]
}
```

---


## Script name: `getCurrentUserName.php` ##

POST Parameters: None

Modified tables: None

XML output:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="GetCurrentUserName">
    <Error>false</Error>
    <UserName>username</UserName>
    <Message></Message>
</XMLMessage>
```
> or:
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="GetCurrentUserName">
    <Error>false</Error>
    <UserName>anonymous</UserName>
    <Message></Message>
</XMLMessage>
```
JSON output:
> 
```
{
 "type":"GetCurrentUserName",
 "error":false,
 "userName":"username",
 "message":[]
}
```
> or:
```
{
 "type":"GetCurrentUserName",
 "error":false,
 "userName":"anonymous",
 "message":[]
}
```

---


## Script name: `getUserName.php` ##

POST Parameters:

> `id` - user's id.

Modified tables: None

XML output on success:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="GetUserName">
    <Error>false</Error>
    <UserName>username with assigned id</UserName>
    <Message></Message>
</XMLMessage>
```
XML output on failure:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="GetUserName">
    <Error>true</Error>
    <UserName>username</UserName>
    <Message>User ID unknown</Message>
</XMLMessage>
```
JSON output on success:
> 
```
{
 "type":"GetUserName",
 "error":false,
 "userName":"username with assigned id",
 "message":[]
}
```
JSON output on failure:
> 
```
{
 "type":"GetUserName",
 "error":true,
 "userName":"username",
 "message":["User ID unknown"]
}
```

---


## Script name: `setPrivateData.php` ##

POST Parameters:

> `currentPassword` - alphanumeric string. Length 32 characters. It should be MD5 hash of user's current password obtained on client side.

> `newPassword` - alphanumeric string. Length 32 characters. It should be MD5 hash of user's new password obtained on client side.

> `eMailAddress` - string, syntactically valid e-mail address.

> `gender` - integer, 0 = not set, 1 = male, 2 = female

> `birthYear` - integer, 0 = not set, otherwise user must be between 5 and 120 years old.

Modified tables:

> [User](DBTables#User.md) - user entry is updated with given data.

XML output on success:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="SetPrivateData">
    <Error>false</Error>
    <UserName>username</UserName>
    <Message>Personal data changed</Message>
</XMLMessage>
```
Possible XML outputs on failure:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="SetPrivateData">
    <Error>true</Error>
    <UserName>username</UserName>
    <Message>Invalid input</Message>
</XMLMessage>
```
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="SetPrivateData">
    <Error>true</Error>
    <UserName>username</UserName>
    <Message>Wrong current password</Message>
</XMLMessage>
```
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="SetPrivateData">
    <Error>true</Error>
    <UserName>username</UserName>
    <Message>Personal data not changed - error occurred while saving the changes. It is possible that new e-mail address has already been registered.</Message>
</XMLMessage>
```
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="SetPrivateData">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>In order to change personal data, you have to be logged in</Message>
</XMLMessage>
```
JSON output on success:
> 
```
{
 "type":"SetPrivateData",
 "error":false,
 "userName":"username",
 "message":["Personal data changed"]
}
```
Possible JSON outputs on failure:
> 
```
{
 "type":"SetPrivateData",
 "error":true,
 "userName":"username",
 "message":["Invalid input"]
}
```
```
{
 "type":"SetPrivateData",
 "error":true,
 "userName":"username",
 "message":["Wrong current password"]
}
```
```
{
 "type":"SetPrivateData",
 "error":true,
 "userName":"username",
 "message":["Personal data not changed - error occurred while saving the changes. It is possible that new e-mail address has already been registered."]
}
```
```
{
 "type":"SetPrivateData",
 "error":true,
 "userName":"anonymous",
 "message":["In order to change personal data, you have to be logged in"]
}
```

---


## Script name: `getPrivateData.php` ##

POST Parameters: None

Modified tables: None

XML output on success:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="GetPrivateData">
    <Error>false</Error>
    <UserName>username</UserName>
    <Message></Message>
    <PrivateData>
       <EMailAddress>e-mail address</EMailAddress>
       <Gender>0 (not set), 1 (male) or 2 (female)</Gender>
       <BirthYear>birth year</BirthYear>
    </PrivateData>
</XMLMessage>
```
XML output on failure:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="GetPrivateData">
    <Error>true</Error>
    <UserName>anonymous</UserName>
    <Message>In order to get personal data, you have to be logged in</Message>
</XMLMessage>
```
JSON output on success:
> 
```
{
 "type":"GetPrivateData",
 "error":false,
 "userName":"username",
 "message":[],
 "privateData":
 {
  "eMailAddress":"e-mail address"
  "gender":"0 (not set), 1 (male) or 2 (female)"
  "birthYear":birth year
 }
}
```
JSON output on failure:
> 
```
{
 "type":"GetPrivateData",
 "error":true,
 "userName":"anonymous",
 "message":["In order to get personal data, you have to be logged in"]
}
```

---


## Script name: `sendInvitation.php` ##

POST Parameters:

> `name` - string, at least 1 character long. As registered users send invitations to their friends, this could be inviter's first name or nick name.

> `eMailAddress` - string, syntactically valid e-mail address. It should be invitees address.

> `message` - string, at least 1 character long. Custom invitation message. It will be appended with the registration instructions. Read more about the invitation mail settings [here](InstallationInstructions#5._Customize_properties.ini_file.md).

Modified tables:

> [TempInvitation](DBTables#TempInvitation.md) - new entry is created with e-mail address of the invited user and (optionally) registration code.

> [User](DBTables#User.md) - number of remaining invitations for particular user is decremented.

XML output on success:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="SendInvitation">
    <Error>false</Error>
    <UserName>username</UserName>
    <Message>Invitation with registration instructions is sent to given e-mail address</Message>
</XMLMessage>
```
Possible XML outputs on failure:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="SendInvitation">
    <Error>true</Error>
    <UserName>username</UserName>
    <Message>Invalid input</Message>
</XMLMessage>
```
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="SendInvitation">
    <Error>true</Error>
    <UserName>username</UserName>
    <Message>Person with given e-mail address is already registered</Message>
</XMLMessage>
```
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="SendInvitation">
    <Error>true</Error>
    <UserName>username</UserName>
    <Message>Person with given e-mail address is already invited</Message>
</XMLMessage>
```
JSON output on success:
> 
```
{
 "type":"SendInvitation",
 "error":false,
 "userName":"username",
 "message":["Invitation with registration instructions is sent to given e-mail address"]
}
```
Possible JSON outputs on failure:
> 
```
{
 "type":"SendInvitation",
 "error":true,
 "userName":"username",
 "message":["Invalid input"]
}
```
```
{
 "type":"SendInvitation",
 "error":true,
 "userName":"username",
 "message":["Person with given e-mail address is already registered"]
}
```
```
{
 "type":"SendInvitation",
 "error":true,
 "userName":"username",
 "message":["Person with given e-mail address is already invited"]
}
```

---


## Script name: `getRemainingInvitations.php` ##

POST Parameters: None

Modified tables: None

XML output:
> 
```
<?xml version="1.0" encoding="UTF-8"?>
<XMLMessage type="RemainingInvitations">
    <Error>false</Error>
    <UserName>username</UserName>
    <Message></Message>
    <RemainingInvitations>number of remaining invitations</RemainingInvitations>
</XMLMessage>
```
JSON output:
> 
```
{
 "type":"RemainingInvitations",
 "error":false,
 "userName":"username",
 "message":[]
 "remainingInvitations":number of remaining invitations
}
```

---


## Script name: `deleteExpiredData.php` ##

POST Parameters: None

Modified tables:

> [TempInvitation](DBTables#TempInvitation.md) - all unused invitations that have expired are deleted from the table. Expiration periods are set in [properties.ini](InstallationInstructions#5._Customize_properties.ini_file.md) file.

> [TempVerification](DBTables#TempVerification.md) - all verification codes that have expired are deleted from the table. Expiration periods are set in [properties.ini](InstallationInstructions#5._Customize_properties.ini_file.md) file.

> [User](DBTables#User.md) - all user accounts that haven't been verified are deleted from the table. Expiration periods are set in [properties.ini](InstallationInstructions#5._Customize_properties.ini_file.md) file.

Output: None (the script is supposed to by run by cron)