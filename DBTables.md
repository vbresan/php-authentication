# Database tables #

## `LastLogin` ##

| **Field** | **Type** |
|:----------|:---------|
| e\_oid | int(12) |
| user\_ID | int(12) |
| time | int(16) |
| ipAddress | varchar(15) |

## `MaskedCookieData` ##

| **Field** | **Type** |
|:----------|:---------|
| e\_oid | int(12) |
| user\_ID | int(12) |
| masked\_ID | varchar(21) |

## `TempInvitation` ##

| **Field** | **Type** |
|:----------|:---------|
| e\_oid | int(12) |
| registrationCode | varchar(10) |
| eMailAddress | varchar(64) |
| creationTime | int(16) |

## `TempVerification` ##

| **Field** | **Type** |
|:----------|:---------|
| e\_oid | int(12) |
| user\_ID | int(12) |
| verificationCode | varchar(21) |
| creationTime | int(16) |

## `User` ##

| **Field** | **Type** |
|:----------|:---------|
| e\_oid | int(12) |
| userName | varchar(10) |
| password | varchar(32) |
| eMailAddress | varchar(64) |
| gender | varchar(1) |
| birthYear | int(16) |
| memberSince | int(16) |
| isVerified | tinyint(1) |
| remainingInvitations | int(12) |