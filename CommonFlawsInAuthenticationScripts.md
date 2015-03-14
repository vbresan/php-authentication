# Common Flaws in Authentication Scripts #

There is a bunch of PHP authentication scripts around and blog posts addressing this topic still frequently pop out. Some solutions are more sophisticated, some are less but certain security breaches are frequently recurring. In particular those are vulnerability to SQL injection attacks and user passwords that are transmitted and stored in plain text.

According to Wikipedia article [here](http://en.wikipedia.org/wiki/SQL_injection) - SQL injection is a code injection technique that exploits a security vulnerability occurring in the database layer of an application. The vulnerability is present when user input is either incorrectly filtered for string literal escape characters embedded in SQL statements or user input is not strongly typed and thereby unexpectedly executed.

Anyone considering himself a serious developer should read this article - it has some nice examples of SQL injection attacks (viewing data that shouldn't be visible to anyone, destroying database), you can also find prevention mechanisms for several most popular languages (including PHP), and finally - a list of documented real-world attacks. Think of stolen passwords, social security numbers, credit cards, etc.


Now let's move on to passwords that are transmitted and stored in plain text. Most of users don't have a different password for each site that they have an account. It is not unlikely that someone has the same password for e-mail, Facebook, Amazon and e-Bay. And data exchanged between client and server machines could be stored on any of the nodes between. If password travels the net in plain text, questionable proxies could scan it, or it might even show up on other server's logs.

Also, if the password is stored in plain text on one of the web sites, anyone having access to database can use it together with e-mail address and try to steal user's network identity. Add in previously described SQL injection attack and you have a web site that begs to be hacked.

A solution to this problem would be to transmit and store only a hash value of plain text password. When user types in password on client side, hash value is generated, sent over the network, stored in database or checked against already stored hash value. Usually, another value known as salt is added to plain text password that prevents attackers from building a list of hash values for common passwords. MD5 and SHA1 are frequently used cryptographic hash functions. PHP supports both, check out `md5()` and `sha1()` functions.