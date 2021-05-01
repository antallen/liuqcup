# 琉行杯資料庫表格清單
+ 資料表更新指令：php artisan migrate:<font color="red">refresh</font>
## Group 基礎表格清單

### 管理人員帳號表 accounts

+ 登入網站 總後台/後台 進行管理工作
+ 表格名稱 : accounts

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|adminid|char(100)|NOT NULL, UNIQUE|1.管理人員的帳號<br>2.總管理帳號 'admin'|
|adminname|char(255)|NOT NULL|管理人員的真實姓名|
|password|char(100)|NOT NULL|管理人員的密碼|
|salt|char(20)|NOT NULL, UNIQUE|加密用的 Hash Key|
|token|char(255)|NOT NULL, UNIQUE|1.管理人員的 Key<br>2.由管理帳號的 Hash code 編碼而成的|
|level|int|NOT NULL,ENUM(0,1,2),Default(2)|1.管理人員等級碼<br>2.等級分類：0 最高級，1 一般職員級，2 工讀生|
|phoneno|char(20)|NOT NULL, UNIQUE|管理人員連絡電話|
|email|char(100)|NOT NULL, UNIQUE|管理人員連絡用Email|
|lock|char(2)|NOT NULL,ENUM('Y','N'), Default('Y')|凍結帳號與否|
|created_at|timestamp|NOT NULL|建立帳號的時間戳記|
|updated_at|timestamp|NULL|更新帳號的時間戳記|

<HR>
<BR>

### 店家資料表 stores

+ 店家基本資料表
+ 與功能資料表連結
+ 表格名稱 : stores

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|storeid|char(100)|NOT NULL, UNIQUE|1.店家編號<br>2.總管理處設定成 '000000000'|
|storename|char(150)|NOT NULL|店家名稱|
|qrcodeid|char(20)|NOT NULL,UNIQUE|店家 QRcode 編碼（其實根本用不到）|
|phoneno|char(50)|NULL, json|店家連絡電話|
|email|char(100)|NULL, json|店家連絡用Email|
|lock|char(2)|NOT NULL,ENUM('Y','N'), Default('Y')|凍結帳號與否|
|created_at|timestamp|NULL|建立帳號的時間戳記|
|updated_at|timestamp|NULL|更新帳號的時間戳記|
|address|char(255)|NULL|店家地址|
|businessid|char(20)|NOT NULL,UNIQUE|店家統一編號|

### 店家管理人員資料表 storesagentids

+ 店家管理人員資料表
+ 與店家資料表連結
+ 資料表名稱 : storesagentids

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|agentid|PRI|char(50)|店家管理人員帳號|
|agentname|NULL|char(50)|店家管理人員姓名|
|agentphone|PRI|char(10)|店家管理人員手機號碼|
|storeid|char(100)|NOT NULL|1.店家編號<br>2.總管理處設定成 '000000000'<br>3.連結店家資料表用|
|salt|char(20)|NOT NULL, UNIQUE|加密用的 Hash Key|
|token|char(255)|NOT NULL, UNIQUE|1.店家管理人員的 Key<br>2.由店家編號的 Hash code 編碼而成的|
|password|char(100)|NOT NULL|店家管理人員密碼|
|lock|char(2)|NOT NULL,ENUM('Y','N'), Default('N')|凍結帳號與否|
|created_at|timestamp|NULL|建立帳號的時間戳記|
|updated_at|timestamp|NULL|更新帳號的時間戳記|

### 店家類別表 classes

+ 店家分類表格
+ 表格名稱：classes

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|classid|char(100)|NOT NULL, UNIQUE|類別編號|
|classname|char(255)|NOT NULL|類別名稱|

### 店家連結類別表 storesclass

+ 店家分類表格
+ 與店家資料表連結
+ 與分類表連結
+ 表格名稱 : storesclass

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|storeid|char(100)|NOT NULL|店家編號|
|classid|char(100)|NOT NULL|類別編號|
|created_at|timestamp|NULL|建立帳號的時間戳記|
|updated_at|timestamp|NULL|更新帳號的時間戳記|

### 店家服務功能表 functions

+ 服務功能表
+ 連結店家提供服務表
+ 表格名稱：functions

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|funcid|char(100)|NOT NULL, UNIQUE|功能編號|
|funcname|char(255)|NOT NULL|功能名稱|

### 店家提供服務表 storesfunctions

+ 店家可提供服務功能表
+ 與店家資料表連結
+ 與服務功能表連結
+ 表格名稱 : storesfunctions

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|storeid|char(100)|NOT NULL, UNIQUE|店家編號|
|funcid|char(100)|NOT NULL, UNIQUE|功能編號|
|created_at|timestamp|NULL|建立帳號的時間戳記|
|updated_at|timestamp|NULL|更新帳號的時間戳記|

### 店家取送杯記錄表 storescupsrecords

+ 店家取杯、送杯記錄表
+ 與店家資料表連結
+ 按月份分表
+ 表格名稱 : storescupsrecords

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|unsignBigInteger|PRI|流水序號|
|storeid|char(100)|NOT NULL,INDEX|店家編號|
|pullcup|int|NOT NULL, Default(0)|取杯數量|
|pushcup|int|NOT NULL, Default(0)|送杯數量|
|date|dateTime|NOT NULL,now,PRI|收送時間戳記|
|adminid|char(100)|NOT NULL,INDEX|管理人員的帳號|
|check|char(2)|NOT NULL,ENUM('Y','N'), Default('N')|確認章簽|
|comment|char(255)|NULL|備註|
|created_at|timestamp|NULL|建立帳號的時間戳記|
|updated_at|timestamp|NULL|更新帳號的時間戳記|

<HR>
<BR>

### 遊客資料表 customers

+ 遊客資本資料表
+ 與借還杯資料表連結
+ 表格名稱： customers

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|cusid|char(20)|NOT NULL, UNIQUE|遊客編號|
|cusname|char(100)|NOT NULL|遊客姓名|
|cusphone|char(100)|NOT NULL, json|遊客手機、市話號碼|
|email|char(100)|NULL|遊客 Email 資料|
|lock|char(2)|NOT NULL,ENUM('Y','N'), Default('Y')|凍結帳號與否(黑名單)|
|created_at|timestamp|NULL|建立帳號的時間戳記|
|updated_at|timestamp|NULL|更新帳號的時間戳記|

### 遊客借還杯記錄表 rentlogs

+ 遊客借還杯資料表
+ 連結遊客資料表
+ 連結店家資料表
+ 按月份分表
+ 資料表名稱 : rentlogs

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|unsignBigInteger|PRI|流水序號|
|cusid|char(20)|NOT NULL,INDEX|遊客編號|
|storeid|char(100)|NOT NULL,INDEX|店家編號|
|rentid|char(2)|NOT NULL,ENUM('R','B'), Default('R')|1.借用：R<br>2.歸還：B|
|nums|int|NOT NULL,Default(0)|借還數量|
|comments|char(255)|NULL|註備說明|
|eventtimes|dateTime|NOT NULL,now,PRI|借還時間戳記|

<HR>
<BR>

## Group 關連資料清單

### 店家管理者外鍵約束

+ 多對一
  + storesagentids(storeid) -> stores(storeid)
    + stores 表格內資料刪除，一併刪除 storesagentids 資料

### 店家分類外鍵約束

+ 多對一
  + storesclass(storeid) -> stores(storeid)
    + stores 表格內資料刪除，一併刪除 storesclass 資料
  + storesclass(classid) -> classes(classid)
    + classes 表格內資料刪除，一併刪除 storesclass 資料

### 店家提供服務功能外鍵約束

+ 多對一
  + storesfunctions(storeid) -> stores(storeid)
    + stores 表格內資料刪除，一併刪除 storesfunctions 資料
  + storesfunctions(funcid) -> functions(funcid)
    + functions 表格內資料刪除，一併刪除 storesfunctions 資料
 
### 店家取送杯記錄外鍵約束
分表狀況下，無法使用 FK！！以下完全無效！
+ 多對一
  + storescupsrecords(storeid) -> stores(storeid)
    + stores 表格內資料刪除，storescupsrecords 表格內 storeid 設成 'NO ACTION'
  + storescupsrecords(adminid) -> accounts(adminid)
    + accounts 表格內資料刪除，storescupsrecords 表格內 adminid 設成 'NO ACTION'
