FORMAT: 1A
HOST: https://liuqapi.tdhome.tw/api

# 琉行杯借還系統功能 API
+ 網頁放置點 https://liuqcup.antallen.info
+ API 網址 https://liuqapi.tdhome.tw/api
#### 站台經營者帳號密碼管理
用於管理站台經營者帳號密碼
+ 管理者帳號密碼驗證
+ 管理者帳號資料列表
+ 新增管理者帳號
+ 凍結管理者帳號
+ 管理者帳號資料修改

#### 店家功能與分類管理 

+ 功能項目管理(稍晚)
+ 分類項目管理(稍晚)
+ 店家功能設定與修改
+ 店家分類設定與修改
+ 店家 QRcode 資料設定與修改(稍晚)

#### 店家資料管理功能要項
用於管理店家資料！
+ 店家資料列表
+ 新增店家資料
+ 凍結店家使用
+ 店家資料查詢
+ 店家資料修改
+ 店家管理員資料設定

#### 店家借還杯功能管理
用於店家面對遊客、管理處的相關功能
+ 店家登入功能
+ 店家取得QRcode資料
+ 店家借還杯功能
+ 店家借還杯確認記錄列表
+ 店家借還杯記錄確認功能
+ 店家收送杯功能
+ 店家收送杯記錄確認列表
+ 店家收送杯記錄確認功能


#### 遊客資料與記錄管理
+ 遊客註冊成會員功能
+ 遊客登入功能
+ 遊客基本資料列表
+ 遊客基本資料修改
+ 遊客借還杯資料查詢
+ 遊客借杯預約功能

#### 借還杯資料統計與查詢

+ 目前借還杯數量
+ 庫存顯示功能
  + 總管理處
  + 每家店內的庫存統計 (店內待借杯數量/店內待收杯數量)
+ 借還杯統計數量與列表
  + 依 全部 / 各店家顯示統計數量
  + 依時間長短顯示(每日/每周/每月)
+ 預約收送杯功能 (總管理處/店家預約功能)
  + 列表
  + 新增
+ 收送杯統計數量與列表
  + 依 全部 / 各店家顯示統計數量
  + 依時間長短顯示(每日/每周/每月)

#### 最新消息管理

+ 新增最新消息
+ 修改最新消息
+ 刪除最新消息
+ 查詢最新消息
#### 中獎名單

+ 中獎名單檔案列表
+ 上傳中獎名單
# Group 站台經營者帳號密碼管理

## 管理者帳號密碼驗證 [/manager/accounts/v1/auths{?account,authword}]

+ 用於管理者登入系統時使用！
+ 登入正確後，取得 token ，做為操作其它功能項目的依據
+ 需查驗是管理處的人員，還是一般店家人員
  + 回吐的資料，包含一些特徵值：
    + level: 管理處人員的等級之分
    + storeid: 表示其為店家的管理人員
    + storename: 帶出店家名稱
### 管理者帳號密碼驗證 [POST]

+ Parameters

    + account: admin (required, string)
    + authword: Aa123456789 (required, string)

+ Response 200 (application/json)

  + Headers

  + Body

            [
                {
                    "token":"abcdefghi",
                    "level": 2
                    "storeid": "100101234",
                    "storename": "戀戀琉島"
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

    + token: Ab123456 (required, string) 
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

    + token: Ab123456 (required, string) 
      + 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + adminid: Hello001 (required, string)
      + 管理人員的帳號 
    + adminname: Peter (required, string)
      + 管理人員的真實姓名
    + password: Ab123456789 (required, string)
      + 管理人員的密碼
    + level: 2 (required, integer)
      + 管理人員等級碼
      + 等級分類：0 最高級，1 一般職員級
    + phoneno: 0987654321 (required, string)
      + 管理人員連絡電話
    + email: test@example.com (required, string)
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

## 凍結/解凍管理者帳號  [/manager/accounts/v1/frozens/frozen{?token,adminid,lock}]
+ token 為最高管理者的 token
+ 只有 level 值為 0 的管理者才可以凍結其他管理者帳號
+ Y : 凍結，N : 解凍
### 凍結管理者帳號 [PATCH]

+ Parameters

    + token: Ab123456 (required, string) 
      + 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + adminid: Hello001 (required, string)
      + 管理人員的帳號 
    + lock: Y (required, string)
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

## 管理者帳號資料修改  [/manager/accounts/v1/renews/renews{?token,adminid,adminname,password,phoneno,email,level,usertoken}]
+ token 為管理者的 token
+ 先利用 lists API 取出資料後，再修正更新內容！
+ 只有 level 值為 0 的管理者，可以修改自己以及別人的 level 值！
### 管理者帳號資料修改 [PATCH]

+ Parameters

    + token: Ab123456 (required, string) 
      + 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + adminid: Hello001 (required, string)
      + 管理人員的帳號 
    + adminname: Peter (required, string)
      + 管理人員的真實姓名
    + password: Ab123456789 (required, string)
      + 管理人員的密碼
    + phoneno: 0123456789 (required, string)
      + 管理人員的雷話
    + email: test@example.com (required, string)
      + 管理人員的 email 
    + level: 2 (optional, string)
      + 更新管理人員的等級
    + usertoken: adcdefgh (optional, string)
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

## 店家資料列表 [/manager/v1/stores/lists{?token,classes}]
+ 店家資料列表
  + 有帶入 token，表示是管理人員要管理店家資料
  + 沒有帶入 token，表示是前台要列店家資料
+ 管理人員需要登入帳密，取得 token 才可以讀取店家資料
### 店家資料列表 [GET]

+ Parameters

    + token: Ab123456 (optional, string) 
      + 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + classes: 1 (optional, integer)
      + 店家型態分類值！
      + 1：專賣店
      + 2：民宿
      + 3：商店
      + 沒有附上 classes 值，會列出全部店家的資料

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
                    以下管理帳號才可以看到是否有 lock ！
                    "lock": "Y",
                    "funid1": "還杯",
                    "funid2": "借杯"
                }
            ]

## 新增店家資料 [/manager/v1/stores/creates{?token,storename,phoneno,address}]
+ 只有管理處人員才可以新增店家資料
### 新增店家資料 [POST]

+ Parameters

    + token: Ab123456 (required, string)
      - 管理人員的 Key，由管理帳號的 Hash code 編碼而成的 
    + storename: 好棒棒 (required, string)
      - 店家名稱
    + phoneno: 0912345678 (required, string)
      - 店家電話
    + address: 中正路四號 (required, string)
      - 店家地址

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : success
                }
            ]

## 己凍結店家列表 [/manager/v1/stores/listfrozens{?token}]
+ 總管理處的人員才可以列表
### 己凍結店家列表 [GET]

+ Parameters

    + token: Ab123456 (required, string)
      - 管理人員的 Key，由管理帳號的 Hash code 編碼而成的

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

    + token: Ab123456 (required, string)
      - 管理人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: 100341234 (required, integer)
      - 店家編號
    + lock: Y (required, string)
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

    + token: Ab123456 (required, string)
      - 管理人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: 100341234 (optional, string)
      - 使用店家編號查詢
    + keyword: 太平洋 (optional, string)
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

    + token: Ab123456 (required, string)
     - 總管理處人員或是店家管理人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: 100341234 (required, integer)
     - 店家編號
    + storename: 太平洋海底 (optional, string)
     - 店家名稱
    + address: 中正路1號 (optional, string)
     - 店家地址
    + phone: 0987654321,081231234 (optional, string)
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

    + token: Ab123456 (required, string)
     - 總管理處人員的 Key，由管理帳號的 Hash code 編碼而成的
    + agentid: peter@hello.com (required, string)
      - 店家管理員編號
      - 使用 email 格式，避免重覆
    + agentname: Peter Wang (optional, string)
      - 店家管理員姓名
    + agentphone: 0912345678 (optional, string)
      - 店家管理員電話
    + storeid: 100334544 (required, string)
      - 店家編號
    + password: Helloworld (optional, string)
      - 店家管理員密碼
    + lock: Y (optional, string)
      - 店家管理員凍結與否
    + action: A01 (required, string)
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
+ 店家 QRcode 資料設定與修改<font color="green">(稍晚)</font>
+ 店家社交軟體管理功能
  + 店家社交軟體列表
  + 店家新增社交軟體連結
  + 店家編修社交軟體連結
  + 店家刪除社交軟體連結

## 店家功能設定與修改 [/manager/v1/funcs/config{?token,storeid,funcs}]
+ 只有有管理處的人才可以修改
### 店家功能設定與修改 [POST]

+ Parameters

    + token: 2!qwe@asd#zxf$ (required, string)
      + 總管理處人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: 10034532 (required, string)
      + 店家代號
    + funcs: A01B02C03 (required, string)
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
+ 只有管理處的人才可以修改

### 店家分類設定與修改 [POST]

+ Parameters

    + token: 2!qwe@asd#zxf$ (required, string)
      + 總管理處人員的 Key，由管理帳號的 Hash code 編碼而成的
    + storeid: 10034532 (required, string)
      + 店家代號
    + classes: A01 (required, string)
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

## 店家社交軟體列表 [/manager/v1/stores/social{?storeid,pages,classes}]
+ 列出店家的社群連結
+ 可列出指定的店家社群連結
+ 可列出全部的店家社群連結
+ 注意：
  + 有設定 storeid 時, classes 無作用，兩者均無，則列出全部！ 
  + 一個店家最多只顯示六筆資料
### 店家社交軟體列表 [GET]

+ Parameters

    + storeid: 10034532 (optional, string)
      + 店家代號
    + pages: 1 (optional, integer)
      + 頁數，每頁50筆記錄
    + classes: A01 (optional, string)
      + 功能項目：
        + A01：專賣
        + B02：民宿
        + C03：商店

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "13354477": [
                        {
                        "id": 1,
                        "storeid": "13354477",
                        "ssname": "facebook",
                        "sslink": "https://www.facebook.com/琉行杯-104228064446226/"
                        }
                }
            ]

## 店家新增社交軟體連結 [/manager/v1/stores/social{?token,storeid,action,data}]
+ 由店家自行管理自家的社交軟體連結
### 店家新增社交軟體連結 [POST]

+ Parameters

    + token: 2!qwe@asd#zxf$ (required, string)
      + 店家管理人員的 Key
    + storeid: 10034532 (required, string)
      + 店家代號
    + action: A01 (required, string)
      + 社交軟體代號
      + A01: facebook
      + B02: line
      + C03: instagram
      + D04: offical (店家官網)
      + E05: telegram
      + F06: youtube
    + data: http://123/123/123 (required, string)
      + 連結資料

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "facebook": "http://www.facebook.com",
                    "line": "http://line",
                    "IG": "http://......"
                }
            ]

## 店家編修社交軟體連結 [/manager/v1/stores/social/social{?token,storeid,action,data,id}]
+ 一次只可編修一種社交軟體連結資料
### 店家編修社交軟體連結 [PATCH]

+ Parameters

    + token: 2!qwe@asd#zxf$ (required, string)
      + 店家管理人員的 Key
    + storeid: 10034532 (required, string)
      + 店家代號
    + action: A01 (required, string)
      + 社交軟體代號
      + A01: facebook
      + B02: line
      + C03: instagram
      + D04: offical (店家官網)
      + E05: telegram
      + F06: youtube
    + data: http://123/123/123 (required, string)
      + 連結資料
    + id: 1 (required, integer) 
      + 社交軟體記錄的編號

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result": "success"
                }
            ]

## 店家刪除社交軟體連結 [/manager/v1/stores/social/social{?token,id,storeid}]

### 店家刪除社交軟體連結 [DELETE]

+ Parameters

    + token: 2!qwe@asd#zxf$ (required, string)
      + 店家管理人員的 Key
    + id: 1 (required, integer) 
      + 社交軟體記錄的編號
    + storeid: 10034532 (required, string)
      + 店家代號

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result": "success"
                }
            ]

# Group 店家借還杯功能管理

用於店家面對遊客、管理處的相關功能
+ 店家登入功能
  + 取得管理人員的 token
+ 店家取得QRcode資料
  + QRcode 功能：給遊客、總管理處方便收取杯用
+ 店家借還杯功能
  + 不是每個店家都有借還杯功能(需要注意)
  + 店家代收杯功能(不用管哪一家還，只還杯，就會寫入記錄) 
+ 店家借還杯確認記錄列表
+ 店家借還杯記錄確認功能
+ 店家收送杯功能
  + 不是每個店家都可以收送杯功能
+ 店家收送杯確認記錄列表
+ 店家收送杯記錄確認功能
+ 店家收送杯記錄列表

## 店家登入功能 [/rent/v1/stores/login{?agentid,agentauth}]
+ 店家管理人員登入
  + 登入後，取得 token 以利接下來的運用
### 店家登入功能 [GET]

+ Parameters

    + agentid: peter (required, string)
      - 店家管理者帳號
    + agentauth: ABC123 (required, string)
      - 店家管理者密碼

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "storeid": "100333222",
                    "storename": "ALoHa",
                    "token": "ABCD123",
                    "function": 1,2
                    "class": 1
                }
            ]

## 店家取得QRcode資料  [/rent/v1/stores/qrcode{?token,action}]
+ <font color="red">注意事項</font>
  - 不是每個店家都有借還杯的功能項目
  - QRcode 網址要帶上店家專屬 qrcodeid 資料
### 店家取得QRcode資料 [POST]

+ Parameters

    + token: ABC123 (required, string)
    + action: A01 (required, string)
      + A01: 借杯
      + B02: 還杯
      + C03: 收杯(總管理處向店家收杯)
      + D04: 送杯(店家向總管理處取杯)

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "qrcode" : "http://liuqcup.antallen.info/#/borrow_cup?qrcode=13354477
                }
            ]

## 店家借還杯功能 [/rent/v1/customers/rent{?token,storeid,qrcode,nums,cusphone,password,action}]
+ action 功能項說明
  - A01: 借杯(店家借杯給遊客)
  - B02: 還杯(遊客還杯給店家)
+ 若無遊客手機號碼，立即建立新的遊客帳號、密碼
+ 店家管理人打開 QRCode -> 遊客掃瞄 QRCode -> 遊客輸入資料 -> 遊客送出資料 -> 店家確認 -> 完成
+ 借還時，同步更新最新的店家杯量庫存表
### 店家借還杯功能 [POST]

+ Parameters

    + token: ABC123 (required, string)
      - 店家管理員 key 或是管理處人員 key
    + storeid: 100345654 (required, string)
      - 借還杯店家 ID
    + qrcode: 100345654 (optional, string)
      - 借還杯店家 Qrcode
    + nums: 3 (required, integer)
      - 出借杯數
    + cusphone: 0912345678 (required, integer)
      - 遊客電話
    + password: ABC123 (required, string)
      - 遊客自設密碼
    + action: A01 (required, string)
      - 借還杯功能代號
        - A01: 借杯
        - B02: 還杯

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : "success"
                }
            ]

## 店家借還杯確認記錄列表 [/rent/v1/customers/rent/list{?token,action}]
+ 店家列出遊客借還杯記錄，用於確認與否

### 店家借還杯確認記錄列表 [POST]

+ Parameter

    + token: ABC123 (optional, string)
      - 店家管理員 key 或是管理處人員 key
    + action: A01 (optional, string)
      - 借還杯功能代號
        - A01: 借杯
        - B02: 還杯

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "id": 9,
                    "cusid": "CUS20210517153534265",
                    "storeid": "13354477",
                    "rentid": "R",
                    "nums": 6,
                    "comments": null,
                    "eventtimes": "2021-05-26 21:20:22",
                    "checks": "N",
                    "cusphone": "0123456789",
                    "backtimes": null,
                    "backstoreid": null
                }
            ]

## 店家借還杯記錄確認功能 [/rent/v1/customers/rent/checks/check{?token,action,cusid,id,checks}]
+ 店家確認遊客借還杯記錄用
+ 店家可刪除誤按的遊客未確認之借杯記錄
+ 己確認的借杯記錄不可以刪除！
### 店家借還杯記錄確認功能 [PATCH]

+ Parameter

    + token: ABC123 (required, string)
      - 店家管理員 key 或是管理處人員 key
    + action: A01 (required, string)
      - 借還杯功能代號
        - A01: 借杯
        - B02: 還杯
    + cusid: CUS20210517153534265 (required, string)
      - 客戶編號
    + id: 9 (required,string)
      - 記錄資料的流水編號
    + checks: Y (required, string)
      - Y: 確認
      - N: 刪除

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : "success"
                }
            ]

## 店家收送杯功能 [/rent/v1/stores/rent/rent{?token,action,nums,adminid}]
+ action 功能項說明
  - C03: 收杯(總管理處向店家收杯 pullcup)
  - D04: 送杯(總管理處向店家送杯 pushcup)
+ 店家管理員打開 QRCode 網頁->總管理處人員掃瞄->總管理處人員輸入資料->送出完成
+ 店家可向總管理處預約杯量、要求收杯！（暫不實作）
### 店家收送杯功能 [PATCH]

+ Parameters

    + token: ABC123 (required, string)
      - 店家管理人員 key
    + action: C03 (required, string)
      - C03: 收杯
      - D04: 送杯
    + nums: 3 (required, integer)
      - 收送杯數量
    + adminid: peter (required, string)
      - 總管理人員帳號

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : "success"
                }
            ]

## 店家收送杯確認記錄列表 [/rent/v1/stores/rent/list{?token,action}]
+ action 功能項說明
  - C03: 收杯(總管理處向店家收杯 pullcup)
  - D04: 送杯(總管理處向店家送杯 pushcup)
### 店家收送杯確認記錄列表 [POST]

+ Parameters

    + token: ABC123 (required, string)
      - 店家管理人員 key
    + action: C03 (required, string)
      - C03: 收杯
      - D04: 送杯

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "id": 1,
                    "storeid": "13354477",
                    "pullcup": 0,
                    "pushcup": 5,
                    "date": "2021-05-24 22:19:44",
                    "adminid": "peter",
                    "check": "N",
                    "comment": null
                }
            ]

## 店家收送杯記錄確認功能 [/rent/v1/stores/checks/check{?token,action,storeid,id,check}]

### 店家收送杯記錄確認功能 [PATCH]

+ Parameters

    + token: ABC123 (required, string)
      - 店家管理人員 key
    + action: C03 (required, string)
      - C03: 收杯
      - D04: 送杯
    + storeid: 10033445 (required, string)
      - 店家代號
    + id: 1 (required, string)
      - 流水序號，請參考列表程式結果值
    + check: Y (required, string)
      - Y: 確認
      - N: 刪除記錄

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : "success"
                }
            ]

## 店家收送杯記錄列表 [/rent/v1/stores/rent/show{?token,storeid,pages,action}]
+ 管理處可以列出完整收送杯記錄
### 店家收送杯記錄列表 [GET]

+ Parameters

    + token: ABC123 (required, string)
      - 總管理處人員 key
      - 店家管理人員 key
    + pages: 1 (optional, integer)
      - 分頁筆數，每頁50筆記錄
    + storeid: ABC123 (optional, string)
      - 方便總管理處人員查看單一店家資料
      - 店家管理員查看時，不用送出此一參數！
    + action: A01 (required, string)
      - A01: 收杯
      - B02: 送杯

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "date": "2021-05-26 09:40:07",
                    "pullcup": 6,
                    "adminid": "peter",
                    "check": "Y"
                }
            ]


# Group 遊客資料與記錄管理

+ 遊客註冊成會員功能
  + 目前只開放管理人員可新增遊客
  + 遊客可借由借杯時，註冊成會員
  + 目前不開放遊客由網頁上註冊成會員
+ 遊客登入功能
  + 對遊客進行身份驗證使用
+ 遊客基本資料管理
  + 遊客基本資料列表（管理者才可以全部列表，其它為查詢功能）
  + 遊客基本資料凍結（列黑名單）
  + 遊客基本資料修改
+ 遊客借還杯資料查詢
  + 遊客查自己
  + 店家查自己借還記錄
  + 總管理處可以查所有店家
+ 遊客預約借杯功能<font color="green">(稍晚)</font>
+ 遊客未還杯記錄表

## 遊客登入驗證 [/manager/v1/customers/login/login{?cusphone,cusauth}]

### 遊客登入驗證 [PUT]

+ Parameters

    + cusphone: 0912345678 (required, string)
      - 遊客手機：任何有註冊的手機號碼即可
    + cusauth: ABC123 (required, string)
      - 遊客註冊時，所設定的密碼

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "cusid": "CDE123",
                    "cusphone": "0912345678,0987654321",
                    "token": "ABC123"
                }
            ]

## 遊客基本資料管理  [/manager/v1/customers/config{?token,cusname,cusphone,cuspassword,cusid,email,lock,action,pages}]
+ 新增時，帶入管理人員的 token 值，進行新增！
+ 修改時，可由遊客自行登入，進行修改！
+ 預留功能：
  + 讓遊客可以自行產生 qrcode ，給店家掃瞄用！

### 遊客基本資料管理 [POST]

+ Parameters

    + token: Ab123456 (required, string)
     - 管理處人員的 Key
     - 店家人員的 Key
     - 遊客的 key
    + cusname: peter (optional, string)
      - 遊客姓名
    + cusphone: 0912345678 (required, string)
      - 遊客電話
    + cuspassword: ABC123 (optional, string)
      - 遊客密碼
    + cusid: ABC12345678 (optional, string)
      - 遊客 ID
    + email: test@hello.com (optional, string)
      - 遊客 Email
    + lock: Y (optional, string)
      - 遊客與否列黑名單，預設值為 N
    + pages: 1 (optional, integer)
      - 要求頁數，一頁以50筆資料為上限！
      - 只有管理人員才可以使用！
    + action: A01 (required, string)
      - 設定功能：
        - A01: 新增
          - 管理人員 (必要欄位：token,cusphone)
            - <font color="blue">遊客借杯時，新增資料由此寫入</font>
          - 遊客註冊 (必要欄位：cusphone, cuspassword)，先取得臨時 token ，再進行新增！)
        - B02: 修改更新資料
          - 遊客手機號碼修改時，會直接新增，變成多支手機
          - 遊客的ID不會修改
          - 遊客可修改項目：cusname,cusphone,cuspassword,email
          - 只有總管理處人員與遊客自己才可以修改遊客資料！店家不能修改遊客資料！
        - C03: 凍結/解凍
          - 總管理處人員以及店家管理員可凍結遊客！(將遊客列黑名單！)
          - Y：凍結
          - N：解凍
        - D04: 查詢
          - 遊客可以查自己
          - 總管理人員可以列出所有遊客資料
          - 店家管理人員只可查特定遊客資料

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : "success"
                }
            ]

## 遊客借還杯資料查詢 [/records/v1/customers/logs/log{?token,cusphone,cusid,storeid,post,pages}]
+ 遊客借還杯資料查詢
  + 遊客查自己
  + 店家查自己借還記錄
  + 總管理處可以查所有店家
+ rentid符號：
  + R：借杯
  + B：還杯
+ lock 符號：
  + Y：確認己借杯
  + N：未確認借杯
  + B：確認己還杯
+ comment: 註解
  + 異常：表示還杯不正常
### 遊客借還杯資料查詢 [GET]

+ Parameters

    + token: Ab123456 (required, string)
      - 管理處人員的 Key
      - 店家人員的 Key
      - 遊客的 key
    + cusphone: 0912345678 (optional, string)
      - 遊客的手機號碼
    + cusid: ABC123 (optional, string)
      - 遊客的ID號碼
    + storeid: ABC123 (optional, string)
      - 店家編號
    + post: A01 (optional, string)
      - 店家用辨別資料
      - A01: 本家借還
      - B02: 本家借，非本家還
      - C03: 非本家借，但本家還
    + pages: 1 (optional, integer)
      - 頁數：每頁 50 筆！

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "id": 2,
                    "cusid": "ABC123",
                    "storeid": "100300",
                    "rentid": "R",
                    "nums": 2,
                    "comments": null,
                    "eventtimes": "2021-06-08 02:49:32",
                    "checks": "Y",
                    "cusphone": "0912345678",
                    "backtimes": null,
                    "backstoreid": null
                }
            ]

## 遊客未還杯/異常記錄表 [/records/v1/stores/aberrantlist{?token,pages}]
+ 總管理處查詢全部未還杯或還杯異常的記錄
+ 店家管理員查詢自家店借出未還杯或還杯異常的記錄
### 遊客未還杯/異常記錄表 [GET]

+ Parameters

    + token: Ab123456 (required, string)
      - 管理處人員的 Key
      - 店家人員的 Key
    + pages: 1 (optional, integer)
      - 頁數：每頁 50 筆！

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "cusphone": "0123456789",
                    "nums": 0,
                    "eventtimes": "2021-05-23 15:09:52",
                    "comments": "欠杯"
                }
            ]

## 遊客註冊用亂數產生功能 [/manager/v1/customers/register{?auth}]
+ 送入一組 6 位英數值，取出一個 token 值，供註冊用！

### 遊客註冊用亂數產生功能 [GET]

+ Parameters

    + auth: ABC123 (required, string)
      - 字數：共 6 個字元！

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "token": "ABC123"
                }
            ]


# Group 借還杯資料統計與查詢

+ 即時顯示目前借還杯數量狀況
  + 顯示今日所有店家的借還杯的總和
+ 庫存顯示功能
  + 總管理處
  + 每家店內的庫存統計 (店內待借杯數量/店內待收杯數量)
+ 借還杯統計數量與列表
  + 依 全部 / 各店家顯示統計數量
  + 依時間長短顯示(每日/每周/每月)
+ 預約收送杯功能 (總管理處/店家預約功能)<font color="green">(稍晚)</font>
  + 列表
  + 新增
+ 收送杯記錄列表
  + 依 全部 / 各店家顯示記錄
  + 依時間長短顯示(每日/每周/每月)

## 即時顯示目前借還杯數量狀況 [/records/v1/stores/rentcuplist{?token,storeid}]
+ 即時顯示目前借還杯數狀況
+ 總管理處人員查詢全部總和
+ 店家管理人員查詢自家店家總和

### 即時顯示目前借還杯數量狀況 [GET]

+ Parameters

    + token: ABC123 (required,string)
      - 總管理處人員的 key
      - 店家管理人員的 key
    + storeid: 123456 (optional, string)
      - 店家編號
      - 沒有設定店家ID，表示要看所有統計！

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "今日借杯數": 0,
                    "今日還杯數": 0,
                    "今日異常筆數": 0
                }
            ]


## 借還杯統計數量與列表 [/records/v1/stores/rentcup/rentcup{?token,storeid,times}]
+ 總管理處看到全部的統計數字
  + 依店家分類
  + 依日期分類
+ 店家看到自己的統計數字
+ 目前的記錄，使用「今日」為主要項目
+ 即時統計，非過去歷史資料統計
### 借還杯統計數量與列表 [GET]

+ Parameters

    + token: ABC123 (required,string)
      - 總管理處人員的 key
      - 店家管理人員的 key
    + storeid: 123456 (optional, string)
      - 店家編號
      - 沒有設定店家ID，表示要看所有統計！
    + times: 1 (optional, string)
      - 1: 一天
      - 7: 一周
      - 30: 一個月

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "借杯數量": {
                        "2021-05-31": {
                        "13354477": null
                        }
                    },
                    "還杯數量": {
                        "2021-05-31": {
                        "13354477": null
                        }
                    },
                    "異常筆數": {
                        "2021-05-31": {
                        "13354477": 0
                        }
                    }
                }
            ]

## 庫存顯示功能 [/records/v1/stores/stocklist{?token,storeid}]
+ 顯示全部的庫存
+ 顯示目前店家的厙存
### 庫存顯示功能 [GET]

+ Parameters

    + token: ABC123 (required,string)
      - 總管理處人員的 key
      - 店家管理人員的 key
    + storeid: 123456 (optional, string)
      - 店家編號
      - 沒有設定店家ID，表示要看所有統計！

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "可借杯數": 0,
                    "待收杯數": 0
                }
            ]

## 收送杯記錄列表 [/records/v1/stores/pushlist/pushlist{?token,storeid,pages,times,action}]
+ 顯示收送杯記錄
+ 店家可顯示自家的收送杯記錄
### 收送杯記錄列表 [GET]

+ Parameters

    + token: ABC123 (required,string)
      - 總管理處人員的 key
      - 店家管理人員的 key
    + storeid: 123456 (optional, string)
      - 店家編號
      - 沒有設定店家ID，表示要看所有統計！
    + action: A01 (required, string)
      - 收送杯代號
        - 收杯：A01 => pullcup
        - 送杯：B02 => pushcup
    + times: 1 (optional, string)
      - 1: 一天
      - 7: 一周
      - 30: 一個月
    + pages: 1 (optional, integer)
      - 分頁取得資料
      - 每頁 50 筆資料

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "可借杯數": 0,
                    "待收杯數": 0
                }
            ]

# Group 最新消息管理
+ 最新消息列表
+ 新增最新消息
+ 修改最新消息
+ 刪除最新消息
+ 查詢最新消息

## 最新消息列表 [/news/v1/news/list{?pages}]
+ 前台專用
### 最新消息列表 [GET]

+ Parameters

    + pages: 1 (optional, integer)
      - 要求資料頁數，每頁50筆！
      - 未加時，取第一頁！

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "newsid": "NEWS1622869777814",
                    "newstitle": "Hello",
                    "newscontent": "今日無事",
                    "newsdate": "2021-06-05 05:09:37",
                    "filename": "hello.jpg",
                    "url": "http://127.0.0.1:8000/storage/news/NF73rakwlOZZstiGqHS1JSSHWKKiJFBwHW6iX6dD.gif"
                }
            ]

## 後台最新消息列表 [/news/v1/news/news{?token,pages}]
+ 後台專用
+ 只列出編號、時間、標題、以及檔案名稱
### 後台最新消息列表 [GET]

+ Parameters

    + token: ABC123 (required,string)
      - 總管理處人員的 key
    + pages: 1 (optional, integer)
      - 要求資料頁數，每頁50筆！
      - 未加時，取第一頁！

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "newsid": ABC123,
                    "newstitle": "今日無頭家",
                    "updated_at": 2021-06-02,
                    "filename": hello.jpg
                }
            ]

## 新增最新消息 [/news/v1/news/create{?token,newstitle,newscontent,filename,file}]
+ 總管理處人員才可以新增消息
### 新增最新消息 [POST]

+ Parameters

    + token: ABC123 (required,string)
      - 總管理處人員的 key
    + newstitle: Hello (required, string)
      - 最新消息標題
    + newscontent: 今日無事 (required, string)
      - 最新消息內容
    + filename: hello.jpg (optional, string)
      - 上傳的檔案名稱
    + file: hello.jpg (optional, string)
      - 上傳的檔案

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result": "success"
                }
            ]

## 查詢最新消息 [/news/v1/news/query{?token,keyword,newsid,pages}]
+ 總管理處人員才可以查詢最新消息
### 查詢最新消息 [POST]

+ Parameters

    + token: ABC123 (required,string)
      - 總管理處人員的 key
    + keyword: 半價 (optional, string)
      - 最新消息標題或內容的關鍵字
    + newsid: NEWS123 (optional, string)
      - 最新消息編號
    + pages: 1 (optional, integer)
      - 要求資料頁數，每頁50筆！
      - 未加時，取第一頁！

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "newsid": "NEWS1622869777814",
                    "newstitle": "Hello",
                    "newscontent": "今日無事",
                    "newsdate": "2021-06-05 05:09:37",
                    "filename": "hello.jpg"
                }
            ]

## 更新/修改最新消息 [/news/v1/news/update{?token,newstitle,newscontent,newsid,filename,file}]
+ 總管理處人員才可以更新最新消息
### 更新/修改最新消息 [POST]

+ Parameters

    + token: ABC123 (required,string)
      - 總管理處人員的 key
    + newstitle: 半價 (optional, string)
      - 更新最新消息標題
    + newscontent: 半價 (optional, string)
      - 更新最新消息內容
    + newsid: NEWS123 (required, string)
      - 最新消息編號
    + filename: hello.jpg (optional, string)
      - 上傳檔案的名稱
    + file: hello.jpg (optional, string)
      - 上傳的檔案

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result": "success"
                }
            ]

## 刪除最新消息 [/news/v1/news/news{?token,newsid}]
+ 刪除最新消息
### 刪除最新消息 [DELETE]

+ Parameters

    + token: ABC123 (required,string)
      - 總管理處人員的 key
    + newsid: NEWS123 (required, string)
      - 最新消息編號

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result": "success"
                }
            ]


# Group 中獎名單
+ 中獎名單檔案列表
+ 上傳中獎名單

## 上傳中獎名單 [/lottos/v1/news{?token,filename,file,month}]

### 上傳中獎名單 [POST]

+ Parameters

    + token: 1 (required, string)
      - 總管理處人員的 key
    + filename: 12月份中獎名單 (required, string)
      - 檔案格式為 pdf 檔案
    + file: hello.pdf (required, file)
      - 要上傳的檔案
    + month: 12 (optional, integer)
      - 設定月份資料
      - 月份： 1,2,3,4,5,6,7,8,9,10,11,12

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result": "success"
                }
            ]


## 中獎名單檔案列表 [/lottos/v1/news{?month}]
+ 可按時間順序列出全部檔案，也可以只顯示某個月份的檔案
+ 不分任何權限
### 中獎名單檔案列表 [GET]

+ Parameters

    + month: 1 (optional, integer)
      - 查詢月份中獎的檔案
      - 月份：1,2,3,4,5,6,7,8,9,10,11,12

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "fileid": "FILE1234",
                    "filename": "12.pdf",
                    "link": "http://127.0.0.1:8000/storage/1lASE3MFCgSgH7lxmBvOMeP0LZRvd4RLgafvoUyW.pdf"
                }
            ]

## 中獎名單檔案刪除 [/lottos/v1/news/news{?token,fileid}]
+ 刪除己過期的名單檔案，或是錯誤的檔案
### 中獎名單檔案刪除 [DELETE]

+ Parameters

    + token: 1 (required, string)
      - 總管理處人員的 key
    + fileid: FILE1234 (required, string)
      - 檔案編號

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result": "success"
                }
            ]


# Group 開發用帳密
+ 總後台最高權限： admin / AB123456
+ 總後台最低權限： peter / ABCD123456 --> 可用於收杯人員

+ 店家管理人員： peter@hello.com / ABC123

+ 遊客：  0912345678,0987654321 / ABC123

