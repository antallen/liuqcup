FORMAT: 1A
HOST: https://liuqcup.tdhome.tw

# 經營者管理總後台功能 API
#### 站台經營者帳號密碼管理
用於管理站台經營者帳號密碼
+ 管理者帳號密碼驗證
+ 管理者帳號資料列表
+ 新增管理者帳號
+ 凍結管理者帳號
+ 管理者帳號資料修改

#### 店家資料管理功能要項
用於管理店家資料！
+ 店家資料列表
+ 新增店家資料
+ 凍結店家使用
+ 店家資料查詢
+ 店家資料修改

# Group 站台經營者帳號密碼管理

## 管理者帳號密碼驗證 [/manager/accounts/v1/auths{?account,authword}]

### 管理者帳號密碼驗證 [POST]

+ Parameters

    + account: 'admin' (required, string)
    + authword: 'Aa123456789' (required, string)

+ Response 200 (application/json)

  + Headers

  + Body

            [
                {
                    "token":"abcdefghi"
                }
            ]

+ Response 400 (application/json)

## 管理者帳號資料列表 [/manager/accounts/v1/lists{?token}]

### 管理者帳號資料列表 [GET]

+ Parameters

    + token: 'Ab123456' (required, string) 
      + 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 

+ Response 200 (application/json)

  + Headers

  + Body

            [
                {
                    "adminid": Hello001,
                    "adminname": Peter,
                    "password": ****
                },
                {
                    "adminid": Hello002,
                    "adminname": James,
                    "password": ****
                }
            ]

+ Response 404 (application/json)

  + Headers

  + Body

            [
                {
                    "error": "File Not Found or Token is wrong"
                }
            ]
## 新增管理者帳號  [/manager/accounts/v1/creates{?token,adminid,adminname,password,level,phoneno,email}]

### 新增管理者帳號 [POST]

+ Parameters

    + token: 'Ab123456' (required, string) 
      + 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + adminid: 'Hello001' (required, string)
      + 管理人員的帳號 
    + adminname: 'Peter' (required, string)
      + 管理人員的真實姓名
    + password: 'Ab123456789' (required, string)
      + 管理人員的密碼
    + level: 2 (required, integer)
      + 管理人員等級碼
      + 等級分類：0 最高級，1 一般職員級
    + phoneno: '0987654321' (required, string)
      + 管理人員連絡電話
    + email: 'test@example.com' (required, string)
      + 管理人員連絡 Email，可用於二階段驗證

+ Response 200 (application/json)

  + Headers

  + Body

            [
                {
                    "result": success
                }
            ]

+ Response 404 (application/json)

  + Headers

  + Body

            [
                {
                    "error": "Token is wrong"
                }
            ]

## 凍結管理者帳號  [/manager/accounts/v1/frozens{?token,adminid,lock}]

### 凍結管理者帳號 [PATCH]

+ Parameters

    + token: 'Ab123456' (required, string) 
      + 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + adminid: 'Hello001' (required, string)
      + 管理人員的帳號 
    + lock: 'Y' (required, string)
      + 是否凍結管理人員的帳號

+ Response 200 (application/json)

  + Headers

  + Body

            [
                {
                    "result": success
                }
            ]

+ Response 404 (application/json)

  + Headers

  + Body

            [
                {
                    "error": "Token is wrong"
                }
            ]





## 管理者帳號資料修改  [/manager/accounts/v1/renews{?token,adminid,adminname,password,lock}]

### 管理者帳號資料修改 [PATCH]

+ Parameters

    + token: 'Ab123456' (required, string) 
      + 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + adminid: 'Hello001' (required, string)
      + 管理人員的帳號 
    + adminname: 'Peter' (required, string)
      + 管理人員的真實姓名
    + password: 'Ab123456789' (required, string)
      + 管理人員的密碼
    + lock: 'N' (required, string)
      + 是否凍結管理人員的帳號

+ Response 200 (application/json)

  + Headers

  + Body

            [
                {
                    "result": success
                }
            ]

+ Response 404 (application/json)

  + Headers

  + Body

            [
                {
                    "error": "Token is wrong"
                }
            ]
# Group 店家資料管理
+ 店家資料參數列表：
  + storeid (integer): 店家編號
  + storename (string): 店家名稱
  + func (string): 店家俱備功能項
## 店家資料列表 [/manager/stores/v1/lists{?token,page}]

### 店家資料列表 [GET]

+ Parameters

    + token: 'Ab123456' (required, string) 
      + 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + page: '1' (required, integer)
      + 指定頁數，例如：第一頁、第二頁，以此類推

+ Response 200 (application/json)

  + Headers


  + Body

            [
                {
                    "storeid": 100341234,
                    "storename": 好望角落,
                    "func": A01B02C03
                },
                {
                    "storeid": 100221566,
                    "storename": 太平洋海底,
                    "func": A01C03
                }
            ]

## 新增店家資料 [/manager/stores/v1/creates{?token,storename,func}]
### 新增店家資料 [POST]

+ Parameters

    + token: 'Ab123456' (required, string)
     - 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + storename: '好棒棒' (required, string)
     - 店家名稱
    + func: 'A01B02C03' (required, string)
     - 店家俱備功能項

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : success
                }
            ]

## 凍結店家使用 [/manager/stores/v1/frozens{?token,storeid,lock}]

### 凍結店家使用 [PATCH]

+ Parameters

    + token: 'Ab123456' (required, string)
     - 管理人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: '100341234' (required, integer)
     - 店家編號
    + lock: 'Y' (required, string)
     - 代表需要凍結

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : success
                }
            ]

## 店家資料查詢 [/manager/stores/v1/querys{?token,storeid}]

### 店家資料查詢 [GET]

+ Parameters

    + token: 'Ab123456' (required, string)
     - 管理人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: '100341234' (required, integer)
     - 店家編號

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "storeid": 100221566,
                    "storename": 太平洋海底,
                    "func": A01C03,
                    "address": 中正路1號,
                    "agent": 雞排妹,
                    "phone": {
                        0987654321,
                        081231234
                    }
                    
                }
            ]

## 店家資料修改 [/manager/stores/v1/renews{?token,storeid,storename,func,address,agent,phone,lock}]

### 店家資料修改 [PATCH]

+ Parameters

    + token: 'Ab123456' (required, string)
     - 管理人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: '100341234' (required, integer)
     - 店家編號
    + storename: '太平洋海底' (required, string)
     - 店家名稱
    + func: 'A01C03' (required, string)
     - 店家俱備功能項
    + address: '中正路1號' (optional, string)
     - 店家地址
    + agent: '雞排妹'  (optional, string)
     - 店家連絡人
    + phone: '0987654321,081231234' (required, string)
     - 店家連絡電話
    + lock: 'N' (optional, string)
     - 代表解凍

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : success
                }
            ]

+ Response 404 (application/json)

    + Headers

    + Body

            [
                {
                    "error" : invalid data
                }
            ]
