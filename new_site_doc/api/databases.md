# 琉行杯資料庫表格清單

## Group 基礎表格清單

### 管理人員帳號表 accounts

+ 登入網站 總後台/後台 進行管理工作
+ 表格名稱 : accounts

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|adminid|char(100)|NOT NULL, UNIQUE|管理人員的帳號|
|adminname|char(255)|NOT NULL|管理人員的真實姓名|
|password|char(100)|NOT NULL|管理人員的密碼|
|salt|char(20)|NOT NULL, UNIQUE|加密用的 Hash Key|
|token|char(255)|NOT NULL, UNIQUE|1.管理人員的 Key<br>2.由管理帳號的 Hash code 編碼而成的|
|level|int|NOT NULL|1.管理人員等級碼<br>2.等級分類：0 最高級，1 一般職員級|
|phoneno|char(20)|NOT NULL, UNIQUE|管理人員連絡電話|
|email|char(100)|NOT NULL, UNIQUE|管理人員連路用Email|
|lock|char(2)|NOT NULL|凍結帳號與否|
|created_at|timstamp|NOT NULL|建立帳號的時間戳記|
<HR>
<BR>

### 店家資料表 stores

+ 店家基本資料表
+ 與功能資料表連結
+ 表格名稱 : stores

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|storeid|char(100)|NOT NULL, UNIQUE|店家編號|
|storeclassid|char(100)|NOT NULL, UNIQUE|店家類別編號|
|storename|char(255)|NOT NULL|店家名稱|
|businessid|char(20)|NOT NULL, UNIQUE|店家統一編號|
|funcid|char(100)|NOT NULL|1.店家倶備功能編號<br>2.編號為功能編碼複合形成|
|salt|char(20)|NOT NULL, UNIQUE|加密用的 Hash Key|
|token|char(255)|NOT NULL, UNIQUE|1.店家管理人員的 Key<br>2.由店家編號的 Hash code 編碼而成的|
|password|char(100)|NOT NULL|店家密碼|
|phoneno|char(255)|NOT NULL, json|店家連絡電話|
|email|char(255)|NOT NULL, json|店家連路用Email|
|lock|char(2)|NOT NULL|凍結帳號與否|
|created_at|timstamp|NULL|建立帳號的時間戳記|
|updated_at|timstamp|NULL|更新帳號的時間戳記|

### 店家類別表 storesclass

+ 店家分類表格
+ 與店家資料表連結
+ 表格名稱 : storesclass

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|classid|char(100)|NOT NULL, UNIQUE|類別編號|
|classname|char(255)|NOT NULL|類別名稱|

### 店家可担供服務功能表 functions

+ 店家可提供服務功能表
+ 與店家資料表連結
+ 表格名稱 : functions

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|funcid|char(100)|NOT NULL, UNIQUE|功能編號|
|funcname|char(255)|NOT NULL|功能名稱|

### 店家取送杯記錄表

+ 店家取杯、送杯記錄表
+ 與店家資料表連結
+ 表格名稱 : 

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|storeid|char(100)|NOT NULL|店家編號|
|pullcup|int|NOT NULL, Default(0)|取杯數量|
|pushcup|int|NOT NULL, Default(0)|送杯數量|
|date|timestamp|NOT NULL|收送時間戳記|
|adminid|char(100)|NOT NULL, UNIQUE|管理人員的帳號|
|check|char(2)|NOT NULL, Default(N)|確認章簽|
|comment|char(255)|NULL|備註|

<HR>
<BR>

### 遊客資料表

<HR>
<BR>

### 遊客借還杯記錄表

<HR>
<BR>

## Group 關連資料清單
