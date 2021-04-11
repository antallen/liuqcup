FORMAT: 1A
HOST: https://liuqcup.tdhome.tw

# 經營者管理總後台功能 API
站台經營者帳號密碼管理：用於管理站台經營者帳號密碼

店家資料管理功能要項：用於管理店家資料！
  + 店家資料列表
  + 新增店家資料
  + 凍結店家使用
  + 店家資料查詢
  + 店家資料修改

# Group 站台經營者帳號密碼管理

# Group 店家資料管理
店家資料參數列表：
  + storeid (integer): 店家編號
  + storename (string): 店家名稱
  + func (string): 店家俱備功能項
## 店家資料列表 [/manager/stores/v1/list{?token,page}]

### 店家資料列表 [GET]

+ Parameters

    + token: 'Ab123456' (required, string) -- 管理人員的 Key
     - key 是管理帳號的 Hash code 編碼而成的 
    + page: '1' (required, integer) -- 指定頁數，例如：第一頁、第二頁，以此類推

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

## 新增店家資料 [/manager/stores/v1/create{?token,storename,func}]
### 新增店家資料 [POST]

+ Parameters

    + token: 'Ab123456' (required, string) -- 管理人員的 Key
     - key 是管理帳號的 Hash code 編碼而成的 
    + storename: '好棒棒' (required, string) -- 店家名稱
    + func: 'A01B02C03' (required, string) -- 店家俱備功能項

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : 成功
                }
            ]

## 凍結店家使用 [/manager/stores/v1/frozen{?token,storeid,lock}]

### 凍結店家使用 [PATCH]

+ Parameters

    + token: 'Ab123456' (required, string) -- 管理人員的 Key
     - key 是管理帳號的 Hash code 編碼而成的
    + storeid: '100341234' (required, integer) -- 店家編號
    + lock: 'Y' (required, string) -- 代表需要凍結

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : 成功
                }
            ]

## 店家資料查詢 [/manager/stores/v1/query{?token,storeid}]

### 店家資料查詢 [GET]

+ Parameters

    + token: 'Ab123456' (required, string) -- 管理人員的 Key
     - key 是管理帳號的 Hash code 編碼而成的
    + storeid: '100341234' (required, integer) -- 店家編號

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

## 店家資料修改 [/manager/stores/v1/renew{?token,storeid,storename,func,address,agent,phone}]

### 店家資料修改 [PATCH]

+ Parameters

    + token: 'Ab123456' (required, string) -- 管理人員的 Key
     - key 是管理帳號的 Hash code 編碼而成的
    + storeid: '100341234' (required, integer) -- 店家編號
    + storename: '太平洋海底' (required, string) -- 店家名稱
    + func: 'A01C03' (required, string) -- 店家俱備功能項
    + address: '中正路1號' (required, string) -- 店家地址
    + agent: '雞排妹'  (required, string) -- 店家連絡人
    + phone: '0987654321,081231234' (required, string) -- 店家連絡電話

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : 成功
                }
            ]

