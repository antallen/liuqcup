FORMAT: 1A
HOST: https://liuqapi.tdhome.tw/api

# 經營者管理總後台功能 API
+ 網頁放置點 https://liuqcup.antallen.info
+ API 網址 https://liuqapi.tdhome.tw/api
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
+ 用於管理者登入系統時使用！
+ 登入正確後，取得 token ，做為操作其它功能項目的依據
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

+ Response 403 (application/json)

  + Headers

  + Body

            [
                {
                    "error": "File Not Found or Token is wrong"
                }
            ]

## 管理者帳號資料列表 [/manager/accounts/v1/lists{?token}]
+ 有管理者的 token ，才可以操作！取得 token 的方式，請參考上一項目
+ level 值為 0 ，表示是最高權限，才可以管理其它管理人員資料！
+ 基本人員只可看到自己的資料，並且看不到 level 值以及 lock 值！
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
                    "password": ****,
                    "phoneno": "0987654321",
                    "email":"test@example.com"
                    "level": "0",
                    "lock": "Y",
                    "token":"asdqwe"
                }
            ]

+ Response 403 (application/json)

  + Headers

  + Body

            [
                {
                    "error": "File Not Found or Token is wrong"
                }
            ]

## 新增管理者帳號  [/manager/accounts/v1/creates{?token,adminid,adminname,password,level,phoneno,email}]
+ token 為最高管理者的 token
+ 只有 level 值為 0 的管理者才可以新增其他管理者帳號
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

+ Response 403 (application/json)

  + Headers

  + Body

            [
                {
                    "error": "Token is wrong"
                }
            ]

## 凍結/解凍管理者帳號  [/manager/accounts/v1/frozens/{frozen}{?token,adminid,lock}]
+ token 為最高管理者的 token
+ 只有 level 值為 0 的管理者才可以凍結其他管理者帳號
+ Y : 凍結，N : 解凍
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
                    "result": "success"
                }
            ]

+ Response 403 (application/json)

  + Headers

  + Body

            [
                {
                    "error": "Level or Token is wrong"
                }
            ]

## 管理者帳號資料修改  [/manager/accounts/v1/renews/{renews}{?token,adminid,adminname,password,phoneno,email,level,usertoken}]
+ token 為管理者的 token
+ 先利用 lists API 取出資料後，再修正更新內容！
+ 只有 level 值為 0 的管理者，可以修改自己以及別人的 level 值！
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
    + phoneno: '0123456789' (required, string)
      + 管理人員的雷話
    + email: 'test@example.com' (required, string)
      + 管理人員的 email 
    + level: '2' (optional, string)
      + 更新管理人員的等級
    + usertoken: "adcdefgh" (optional, string)
      + 人員的 token，用以辨識更新的帳號

+ Response 200 (application/json)

  + Headers

  + Body

            [
                {
                    "result": "update success"
                }
            ]

+ Response 403 (application/json)

  + Headers

  + Body

            [
                {
                    "error": "update is failed"
                }
            ]
# Group 店家資料管理
+ 店家資料參數列表：
  + storeid (integer): 店家編號
  + storename (string): 店家名稱
  + func (string): 店家俱備功能項
  + address (string): 店家地址
  + phones (string): 店家電話

## 店家資料列表 [/manager/v1/stores/lists{?token,classes}]
+ 店家資料列表
  + 有帶入 token，表示是管理人員要管理店家資料
  + 沒有帶入 token，表示是前台要列店家資料
+ 管理人員需要登入帳密，取得 token 才可以讀取店家資料
### 店家資料列表 [GET]

+ Parameters

    + token: 'Ab123456' (optional, string) 
      + 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + classes: '1' (required, integer)
      + 店家型態分類值！
      + 1：專賣店
      + 2：民宿
      + 3：商店

+ Response 200 (application/json)

  + Headers

  + Body

            [
                {
                    "storeid": "13354475",
                    "storename": "輪廓莊園",
                    "phoneno": null,
                    "address": "屏東縣琉球鄉杉福村復興路163號-5",
                    "classname": "民宿",
                    以下管理帳號才可以看到！
                    "lock": "Y",
                    "funid1": "還杯",
                    "funid2": "借杯"
                }
            ]

## 新增店家資料 [/manager/v1/stores/creates{?token,storename,func}]
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

## 凍結店家使用 [/manager/v1/stores/frozens{?token,storeid,lock}]

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

## 店家資料查詢 [/manager/v1/stores/querys{?token,storeid}]

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

## 店家資料修改 [/manager/v1/stores/renews{?token,storeid,storename,func,address,agent,phone,lock}]

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
