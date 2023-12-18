# Getting started
## 소프트웨어 준비하기
PHP, MySQL을 준비하세요.  
개별적으로 설치하기 번거로우시다면 [XAMPP](https://www.apachefriends.org/)를 사용하세요.

## 필요 버전
FCA는 PHP 5.6 이상 버전에서 동작합니다.

## 설치
그냥 [다운로드]()하세요. 그리고 아무 디렉토리에 압축을 푸세요.

## 데이터베이스 시작하기
MariaDB 서버를 시작하세요.
![K-001](https://github.com/freetercoder/fca/blob/main/capture/K-001.png)

## 데이터베이스 생성
테스트할 데이터베이스를 생성해요.
```SQL
CREATE DATABASE `fca` /*!40100 COLLATE 'utf8mb4_general_ci' */;
```

## FCA에게 데이터베이스 정보를 알려주세요.
`_fca/user/user.config.php`
```php
//////////////////////////////////////////////////
// database
//////////////////////////////////////////////////

const DB_HOST = "localhost";
const DB_PORT = "3306";
const DB_USER = "fca_user";
const DB_PASSWORD = "1234";
const DB_NAME = "fca";
const DB_CHARSET = "utf8";
```

## 서버를 시작해요.
php -S localhost:7000

## 데이터베이스 테이블을 만들어요.
[http://localhost:7000/__dev/gen.php](http://localhost:7000/__dev/gen.php) 에 접속하세요.
![K-002](https://github.com/freetercoder/fca/blob/main/capture/K-002.png)

1. 커맨드에 `article title content` 를 입력하세요.
2. APPLY DB를 선택하세요.
3. create member table을 체크하세요.
4. generate 버튼을 클릭하세요.
![K-003](https://github.com/freetercoder/fca/blob/main/capture/K-003.png)

SQL RESULT 항목이 나왔는지 확인해요.
![K-004](https://github.com/freetercoder/fca/blob/main/capture/K-004.png)

`article` 테이블과 `member` 테이블이 생성되었는지 확인하세요.
![K-005](https://github.com/freetercoder/fca/blob/main/capture/K-005.png)
![K-006](https://github.com/freetercoder/fca/blob/main/capture/K-006.png)
![K-007](https://github.com/freetercoder/fca/blob/main/capture/K-007.png)

`member` 테이블에 샘플 데이터가 있는지 확인해요.
![K-008](https://github.com/freetercoder/fca/blob/main/capture/K-008.png)

## API 초안을 생성해요.
1. [http://localhost:7000/__dev/gen.php](http://localhost:7000/__dev/gen.php) 페이지로 돌아가요.
2. 커맨드에 `article title content`가 입력되어 있는지 확인해요.
3. SQL 항목은 ONLY SHOW로 변경해요.
3. create member table을 체크 해지하세요.
4. Template API Generate 항목을 APPLY FILE을 선택해요.
5. generate 버튼을 클릭해요.

![K-009](https://github.com/freetercoder/fca/blob/main/capture/K-009.png)

FILE RESULT 항목이 나왔는지 확인해요.
![K-010](https://github.com/freetercoder/fca/blob/main/capture/K-010.png)

API 초안이 생성되었는지 확인해요.
![K-011](https://github.com/freetercoder/fca/blob/main/capture/K-011.png)

## API를 테스트해요.
### 데이터 생성 테스트
자동 생성된 회원의 토큰을 확인해요.
![K-012](https://github.com/freetercoder/fca/blob/main/capture/K-012.png)

1. 테스트 페이지 [http://localhost:7000/__dev/test.php](http://localhost:7000/__dev/test.php) 에 접속해요.
2. bearer 부분에 자동 생성된 회원의 토큰을 입력해요.
3. 메소드는 POST(기본값) 인지 확인해요.
4. URL 은 `/article` 을 입력해요.
5. 본문은 다음의 코드를 입력해요.

```JSON
{
    "title" : "fca title",
    "content" : "fca content"
}
```

6. send request를 눌러요.
7. 결과를 확인해요.

![K-013](https://github.com/freetercoder/fca/blob/main/capture/K-013.png)

데이터베이스에서도 다시 한번 확인해요.
![K-014](https://github.com/freetercoder/fca/blob/main/capture/K-014.png)

### 데이터 목록 조회 테스트
1. 테스트 페이지 [http://localhost:7000/__dev/test.php](http://localhost:7000/__dev/test.php) 로 돌아와요.
2. bearer 는 비워둬요.
3. 메소드를 GET으로 변경해요.
4. URL 은 `/article`인지 확인해요.
5. 본문은 비워둬요.
6. send request를 눌러요.
7. 결과를 확인해요.

![K-015](https://github.com/freetercoder/fca/blob/main/capture/K-015.png)

### 데이터 개별 조회 테스트
1. 테스트 페이지 [http://localhost:7000/__dev/test.php](http://localhost:7000/__dev/test.php) 로 돌아와요.
2. bearer 는 비워둬요.
3. 메소드를 GET으로 변경해요.
4. URL 은 `/article/1`로 변경해요.
5. 본문은 비워둬요.
6. send request를 눌러요.
7. 결과를 확인해요.

![K-016](https://github.com/freetercoder/fca/blob/main/capture/K-016.png)

### 데이터 수정 테스트
1. 테스트 페이지 [http://localhost:7000/__dev/test.php](http://localhost:7000/__dev/test.php) 에 접속해요.
2. bearer는 비워둬요.
3. 메소드는 PUT으로 변경해요.
4. URL 은 `/article`을 입력해요.
5. 본문은 다음의 코드를 입력해요.

```JSON
{
    "title" : "fca title modified",
    "content" : "fca content is changed"
}
```

6. send request를 눌러요.
7. 결과를 확인해요.

![K-017](https://github.com/freetercoder/fca/blob/main/capture/K-017.png)

실패했어요! `bearer`가 비어있기 때문에 인증되지 않았다는 오류가 보여요.
![K-018](https://github.com/freetercoder/fca/blob/main/capture/K-018.png)

1. `bearer`를 채우고 다시 send request를 눌러요.

![K-019](https://github.com/freetercoder/fca/blob/main/capture/K-019.png)

### 데이터 삭제 테스트
1. 테스트 페이지 [http://localhost:7000/__dev/test.php](http://localhost:7000/__dev/test.php) 에 접속해요.
2. bearer를 채워요.
3. 메소드는 DELETE 로 변경해요.
4. URL 은 `/article/1`을 입력해요.
5. send request를 눌러요.
6. 결과를 확인해요.

![K-020](https://github.com/freetercoder/fca/blob/main/capture/K-020.png)