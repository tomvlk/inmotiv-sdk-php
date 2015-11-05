# InMotiv RDC SOAP PHP client

InMotiv (https://www.rdc.nl/) is one of the official RDW partners. It provides SOAP endpoints to access RDW database.
For example you can check whether driver licence is valid or not by number and owner birthday. 

## Requirements

* PHP 5.6
* SOAP extension

## How to use

Fill your InMotiv credentials and driver license details in the code below: 

```
$xmlBuilder = new XmlBuilder();
$client = new InMotivClient(111111, 'rdc111111999', 'xxxXXXxxx');
var_dump($client->isDriverLicenceValid('1111111111', 1990, 1, 1));
```

And result should be

```
bool(true)
```

See full example in [example.php](example.php).

## What is currently implemented

* driver licence check

## Implementation in progress

* vehicle details by numberplates

## How to test

Since InMotiv does not have open sandbox account, you have to create `.env` file in the project root with
your credentials. Also you need to provide a valid driver licence details.

```
INMOTIV_CLIENT_NUMBER=111111
INMOTIV_USERNAME=rdc111111999
INMOTIV_PASSWORD=xxxXXXxxx

DRIVER_LICENCE_NUMBER=xxxxxxxxxx
BIRTHDAY_YEAR=2000
BIRTHDAY_MONTH=10
BIRTHDAY_DAY=10

```

Then just run `./vendor/bin/phpunit`. Everything should be green.
