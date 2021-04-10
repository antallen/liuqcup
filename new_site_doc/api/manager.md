FORMAT: 1A
HOST: https://liuqcup.tdhome.tw

# 經營者管理總後台功能 API
店家資料管理：用於管理店家資料！
  + 店家列表
  + 店家新增
  + 店家凍結
  + 店家查詢

# Group 店家列表
取回的參數列表：
  + storeid (integer): 店家編號
  + storename (string): 店家名稱
  + render (boolean): 租用
## 取得店家資料列表 [/manager/stores/list{?token}]
必送出的參數


### 取得店家資料列表 [GET]

+ Parameters

    + token: `Ab123456` (required, string) - 管理人員的 Key

+ Response 200 (application/json)

  + Headers

            Location: /manager/stores/list

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


