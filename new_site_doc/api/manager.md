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

+ 店家管理員資料參數列表：
  + agentid (string): 店家管理員編號
  + agentname  (string): 店家管理員姓名
  + agentphone (string): 店家管理員電話
  + storeid (string): 店家編號
  + password (string): 店家管理員密碼
  + lock (string): 店家管理員凍結與否

+ 店家缺項目：
  + 社群網址欄位，如：FB 網址

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

## 新增店家資料 [/manager/v1/stores/creates{?token,storename,phoneno,address}]
+ 只有管理處人員才可以新增店家資料
### 新增店家資料 [POST]

+ Parameters

    + token: 'Ab123456' (required, string)
      - 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + storename: '好棒棒' (required, string)
      - 店家名稱
    + phoneno: '0912345678' (required, string)
      - 店家電話
    + address: '中正路四號' (required, string)
      - 店家地址

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

## 店家資料查詢 [/manager/v1/stores/querys/query{?token,storeid,keyword}]
+ 查詢各別店家詳細基本資料
### 店家資料查詢 [GET]

+ Parameters

    + token: 'Ab123456' (required, string)
      - 管理人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: '100341234' (optional, string)
      - 使用店家編號查詢
    + keyword: '太平洋' (optional, string)
      - 使用關鍵字查詢  

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "storeid": 100221566,
                    "storename": 太平洋海底,
                    "class": 民宿,
                    "address": 中正路1號,
                    "phone": {
                        0987654321,
                        081231234
                    }
                    
                }
            ]

## 店家基本資料修改 [/manager/v1/stores/store{?token,storeid,storename,address,phone}]

### 店家基本資料修改 [PATCH]

+ Parameters

    + token: 'Ab123456' (required, string)
     - 總管理處人員或是店家管理人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: '100341234' (required, integer)
     - 店家編號
    + storename: '太平洋海底' (optional, string)
     - 店家名稱
    + address: '中正路1號' (optional, string)
     - 店家地址
    + phone: '0987654321,081231234' (optional, string)
     - 店家連絡電話

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

## 店家管理員資料設定 [/manager/v1/stores/agent{?token,storeid,agentid,agentname,agentphone,password,lock,action}]
+ 由管理處人員進行各店家管理者設定
+ 各店家可自行修改自家的管理者帳號
+ 修改密碼時，一併更新 salt 以及 token
### 店家管理員資料設定 [POST]

+ Parameters

    + token: 'Ab123456' (required, string)
     - 總管理處人員的 Key，由管理帳號的 Hash code 編碼而成的
    + agentid: 'peter' (required, string)
      - 店家管理員編號
    + agentname: 'Peter Wang' (optional, string)
      - 店家管理員姓名
    + agentphone: '0912345678' (optional, string)
      - 店家管理員電話
    + storeid: '100334544' (required, string)
      - 店家編號
    + password: 'Helloworld' (optional, string)
      - 店家管理員密碼
    + lock: "Y" (optional, string)
      - 店家管理員凍結與否
    + action: "A01" (required, string)
      - 設定功能：
        - A01: 新增
        - B02: 修改
        - C03: 刪除
        - D04: 查詢
        - E05: 凍結 

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

# Group 店家功能與分類管理
+ 功能項目管理<font color="green">(稍晚)</font>
+ 分類項目管理<font color="green">(稍晚)</font>
+ 店家功能設定與修改
+ 店家分類設定與修改

## 店家功能設定與修改 [/manager/v1/funcs/config{?token,storeid,funcs}]
+ 只有有管理處的人才可以修改
### 店家功能設定與修改 [POST]

+ Parameters

    + token: "2!qwe@asd#zxf$" (required, string)
      + 總管理處人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: "10034532" (required, string)
      + 店家代號
    + funcs: "A01B02C03" (required, string)
      + 功能項目：
        + A01：還杯
        + B02：借杯
        + C03：使用琉行杯消費

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : success
                }
            ]

## 店家分類設定與修改 [/manager/v1/classes/config{?token,storeid,classes}]
+ 只有有管理處的人才可以修改
### 店家分類設定與修改 [POST]

+ Parameters

    + token: "2!qwe@asd#zxf$" (required, string)
      + 總管理處人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: "10034532" (required, string)
      + 店家代號
    + classes: "A01" (required, string)
      + 功能項目：
        + A01：專賣
        + B02：民宿
        + C03：商店

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : success
                }
            ]

# Group 店家借還杯功能管理
+ 店家 QRcode 資料設定與修改
+ 店家收杯功能 (店家收總管理處的杯子)
+ 店家取杯功能 (總管理處取走店家的杯子)
+ 店家代收杯功能 
# Group 遊客資料與記錄管理
+ 遊客基本資料管理
+ 遊客借杯記錄(店家出借給遊客)
+ 遊客還杯記錄(遊客還杯給店家)
  + 同一店歸還
  + 不同店歸還

## 遊客基本資料管理  [/manager/v1/customers/config{?token,cusname,cusphone,cuspassword,cusid,email,lock,action}]
+ 新增時，帶入管理人員的 token 值，進行新增！
+ 修改時，可由遊客自行登入，進行修改！
+ 預留功能：
  + 讓遊客可以自行產生 qrcode ，給店家掃瞄用！

### 遊客基本資料管理 [POST]

+ Parameters

    + token: 'Ab123456' (required, string)
     - 管理處人員的 Key
     - 店家人員的 Key
     - 遊客的 key
    + cusname: 'peter' (optional, string)
      - 遊客姓名
    + cusphone: '0912345678' (required, string)
      - 遊客電話
    + cuspassword: 'ABC123' (optional, string)
      - 遊客密碼
    + cusid: 'ABC12345678' (optional, string)
      - 遊客 ID
    + email: 'test@hello.com' (optional, string)
      - 遊客 Email
    + lock: "Y" (optional, string)
      - 遊客與否列黑名單，預設值為 N
    + action: "A01" (required, string)
      - 設定功能：
        - A01: 新增
          - 管理人員 (必要欄位：token,cusphone)
            - <font color="blue">遊客借杯時，新增資料由此寫入</font>
          - 遊客註冊 (必要欄位：cusphone, cusphone, cuspassword)<font color="red">(暫時不開放)</font>
        - B02: 修改<font color="green">(稍晚)</font>
        - C03: 凍結<font color="green">(稍晚)</font>
        - D04: 查詢<font color="green">(稍晚)</font>

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : "success"
                }
            ]
