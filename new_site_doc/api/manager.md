FORMAT: 1A
HOST: https://liuqcup.tdhome.tw

# 經營者管理總後台功能 API
店家資料管理功能要項：用於管理店家資料！
  + 店家列表
  + 店家新增
  + 店家凍結
  + 店家查詢

# Group 店家資料管理
店家資料參數列表：
  + storeid (integer): 店家編號
  + storename (string): 店家名稱
  + render (boolean): 租用
# 取得店家資料列表 [/manager/stores/v1/list{?token}{?page}]

## 取得店家資料列表 [GET]

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
                    "render": Y
                },
                {
                    "storeid": 100221566,
                    "storename": 太平洋海底,
                    "render": N
                }
            ]

# 新增店家資料 [/manager/stores/v1/create{?token}{?storename}{?render}]
## 新增店家資料 [POST]

+ Parameters

    + token: 'Ab123456' (required, string) -- 管理人員的 Key
     - key 是管理帳號的 Hash code 編碼而成的 
    + storename: '好棒棒' (required, string) -- 店家名稱
    + render: 'Y' (required, string) -- 開放租用功能

+ Response 200 (application/json)

    + Headers

    + Body

            [
                {
                    "result" : 成功
                }
            ]
