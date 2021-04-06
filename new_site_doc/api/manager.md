# 經營者管理總後台功能 API
+ 店家資料管理：用於管理店家資料，功能有新增與凍結！

# Group 店家資料管理

## 店家資料查詢 [/manager/stores]

### 取得店家資料列表 [GET]
參數列表：
+ storeid (integer): 店家編號
+ storename (string): 店家名稱
+ render (boolean): 租用

範例：
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
