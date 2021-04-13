# 琉行杯資料庫表格清單

## Group 基礎表格清單

### 管理人員帳號表 accounts

+ 登入網站 總後台/後台 進行管理工作
+ 表格名稱 : accounts

|欄位名稱|資料類型規格|設定參數|說明|
|:-------|:-----------|:-------|:---|
|id|int|PRI|流水序號|
|adminid|char(100)|NOT NULL|管理人員的帳號|
|adminname|char(255)|NOT NULL|管理人員的真實姓名|
|password|char(100)|NOT NULL|管理人員的密碼|
|salt|char(20)|NOT NULL|加密用的 Hash Key|
|token|char(255)|NOT NULL|管理人員的 Key，由管理帳號的 Hash code 編碼而成的|
|level|int|NOT NULL|管理人員等級碼，等級分類：0 最高級，1 一般職員級|
|phoneno|char(20)|NOT NULL|管理人員連絡電話|
|email|char(100)|NOT NULL|管理人員連路用Email|
|lock|char(2)|NOT NULL|凍結帳號與否|
|timestamp|timstamp|NOT NULL|建立帳號的時間戳記|
<HR>
