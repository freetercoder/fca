# 1.Get ready
## 1.1. Preparing the software
Prepare PHP and MySQL.
If you are having trouble installing them individually, use [XAMPP](https://www.apachefriends.org/).

## 1.2. PHP version required
FCA operates in PHP 5.6 or higher.

## 1.3. FCA Installation
Just [download](https://github.com/freetercoder/fca/releases). Then unzip it to any directory.

## 1.4. Getting started with the database
Start the MariaDB server.
  
![K-001](https://github.com/freetercoder/fca/blob/main/capture/K-001.png)

## 1.5. Create database
Create a database for test.
```SQL
CREATE DATABASE `fca` /*!40100 COLLATE 'utf8mb4_general_ci' */;
```

# 2. Setting up
## 2.1. Please provide FCA with your database information.
`_fca/user/user.config.php`
```php
/////////////////////////////////////////////////////
// database
/////////////////////////////////////////////////////

const DB_HOST = "localhost";
const DB_PORT = "3306";
const DB_USER = "fca_user";
const DB_PASSWORD = "1234";
const DB_NAME = "fca";
const DB_CHARSET = "utf8";
```

## 2.2. Start the server.
```
php -S localhost:7000
```

# 3. Create
## 3.1. Create a database table.
### 3.1.1. Access Gen page
1. Access [http://localhost:7000/__dev/gen.php](http://localhost:7000/__dev/gen.php).
![K-002](https://github.com/freetercoder/fca/blob/main/capture/K-002.png)

### 3.1.2. Create
1. Enter `article title content` in the command.
2. Select APPLY DB.
3. Check create member table.
4. Click the generate button.
![K-003](https://github.com/freetercoder/fca/blob/main/capture/K-003.png)

### 3.1.3. Check SQL RESULT items
Check whether the SQL RESULT item appears.
![K-004](https://github.com/freetercoder/fca/blob/main/capture/K-004.png)

### 3.1.4. Check database table
Make sure the `article` table and `member` table are created.
![K-005](https://github.com/freetercoder/fca/blob/main/capture/K-005.png)
  
![K-006](https://github.com/freetercoder/fca/blob/main/capture/K-006.png)
  
![K-007](https://github.com/freetercoder/fca/blob/main/capture/K-007.png)

### 3.1.5. Check `member` sample data
Check whether there is sample data in the `member` table.
![K-008](https://github.com/freetercoder/fca/blob/main/capture/K-008.png)

## 3.2. Create an API draft.
### 3.2.1. Create a file
1. Go back to the [http://localhost:7000/__dev/gen.php](http://localhost:7000/__dev/gen.php) page.
2. Check whether `article title content` is entered in the command.
3. Change the SQL item to ONLY SHOW.
3. Uncheck create member table.
4. Select APPLY FILE in the Template API Generate item.
5. Click the generate button.

![K-009](https://github.com/freetercoder/fca/blob/main/capture/K-009.png)

### 3.2.2. Check FILE RESULT items
Check whether the FILE RESULT item appears.
![K-010](https://github.com/freetercoder/fca/blob/main/capture/K-010.png)

### 3.2.3. Check the created files
Verify that the API draft PHP file has been created.
![K-011](https://github.com/freetercoder/fca/blob/main/capture/K-011.png)

# 4. Test the API.
## 4.1. Data generation testing
### 4.1.1. Check member token
Check the automatically generated member token.
![K-012](https://github.com/freetercoder/fca/blob/main/capture/K-012.png)

1. Access the test page [http://localhost:7000/__dev/test.php](http://localhost:7000/__dev/test.php).
2. Enter the automatically generated member token in the bearer section.
3. Check whether the method is POST (default).
4. Enter `/article` as the URL.
5. Enter the following code in the text.

```JSON
{
     "title" : "fca title",
     "content" : "fca content"
}
```

6. Press send request.
7. Check the results.

![K-013](https://github.com/freetercoder/fca/blob/main/capture/K-013.png)

### 4.1.2. Check in database
Check the database again.
![K-014](https://github.com/freetercoder/fca/blob/main/capture/K-014.png)

## 4.2. Data list query test
1. Return to the test page [http://localhost:7000/__dev/test.php](http://localhost:7000/__dev/test.php).
2. Leave bearer blank.
3. Change the method to GET.
4. Check that the URL is `/article`.
5. Leave the text blank.
6. Press send request.
7. Check the results.

![K-015](https://github.com/freetercoder/fca/blob/main/capture/K-015.png)

## 4.3. Data individual inquiry test
1. Return to the test page [http://localhost:7000/__dev/test.php](http://localhost:7000/__dev/test.php).
2. Leave bearer blank.
3. Change the method to GET.
4. Change the URL to `/article/1`.
5. Leave the text blank.
6. Press send request.
7. Check the results.

![K-016](https://github.com/freetercoder/fca/blob/main/capture/K-016.png)

## 4.4. Data Modification Testing
1. Access the test page [http://localhost:7000/__dev/test.php](http://localhost:7000/__dev/test.php).
2. Leave bearer blank.
3. Change the method to PUT.
4. Enter `/article` as the URL.
5. Enter the following code in the text.

```JSON
{
     "title" : "fca title modified",
     "content" : "fca content is changed"
}
```

6. Press send request.
7. Check the results.

![K-017](https://github.com/freetercoder/fca/blob/main/capture/K-017.png)

**Failed!** I get an error saying it is not authenticated because `bearer` is empty.
![K-018](https://github.com/freetercoder/fca/blob/main/capture/K-018.png)

1. Fill in `bearer` and press send request again.

![K-019](https://github.com/freetercoder/fca/blob/main/capture/K-019.png)

## 4.5. Data Deletion Test
1. Access the test page [http://localhost:7000/__dev/test.php](http://localhost:7000/__dev/test.php).
2. Fill in the bearer.
3. Change the method to DELETE.
4. Enter `/article/1` as the URL.
5. Press send request.
6. Check the results.

![K-020](https://github.com/freetercoder/fca/blob/main/capture/K-020.png)