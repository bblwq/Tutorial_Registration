# Tutorial_Registration
An example of establishing connection to a MYSQL server on a PHP website.

## List of Tables
* allocation
* session

## Table Schema: allocation
| Field | Type | Null | Key | Default | Extra |
| :---: | :---: | :---: | :---: | :---: | :---: |
| emailAddress | varchar(50) | NO | PRI | NULL |  |
| name | varchar(50) | YES |  | NULL |  |
| sessionID | int(11) | YES | MUL | NULL |  |
| question | varchar(200) | YES |  | NULL |  |

## Table Schema: session
| Field | Type | Null | Key | Default | Extra |
| :---: | :---: | :---: | :---: | :---: | :---: |
| sessionID | int(11) | NO   | PRI | NULL |  |
| day | varchar(20) | YES  |  | NULL |  |
| time | varchar(20) | YES  |  | NULL |  |
| capability | int(11) | YES |  | NULL |  |
| remainPlace | int(11)  | YES |  | NULL |  |
