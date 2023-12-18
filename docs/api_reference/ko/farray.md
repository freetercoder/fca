# FArray
## 개요
PHP의 배열을 래핑한 클래스입니다.

## 함수 목록
### val
#### 설명
`FArray` 객체를 PHP의 배열로 반환합니다.
#### 함수 원형
```
val()
```

### find
#### 설명
연관배열의 키로 값을 찾습니다. 중첩되어 있는 배열도 찾을 수 있습니다.  
JSON 요청을 파싱할 때 유용하게 사용됩니다.

#### 함수 원형
```
find($key, $default=null)
```

#### 매개변수
##### `$key`
찾을 연관배열의 키입니다.

##### `$default`
키에 해당하는 값을 찾지 못했을 경우 반환할 값입니다.

#### 예제
```
$sample = [
    "first" => "first value",
    "second" => [
        "third" => [
            "fourth" => "fourth depth value"
        ]
    ]
];

echo farray($sample)->find("first"); // first value
echo farray($sample)->find("second.third.fourth"); // fourth depth value
echo farray($sample)->find("nothing", "NULL"); // NULL
```


