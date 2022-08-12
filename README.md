# actibee
基於 CodeIgniter3 的表單產生、線上金流（採用綠界科技API）系統

[示範網站-測試環境](https://actibee.monken.tw/)

Actibee - 活動蜂，將校園社團作為主要服務對象，提供表單製作、金流處理、名冊管理等服務——使用者可以便捷地建立個性化表單，提供活動參加者線上報名與付款的功能，輕鬆管理報名資訊與收款狀態。

## 系統需求

1. PHP 7.4
2. Mysql 5.6

## 啟動

1. 於專案根目錄中執行以下指令
```
docker-compose up -d
```

2. 使用 Composer 將所需程式庫下載至專案中
```
docker-compose exec app composer install
```

3. 將專案根目錄中的 ``actibee.sql`` 匯入至資料庫

4. 專案預設連接埠為 `8080` 
    * `localhost:8080`

5. 將專案根目錄的 `.env.php` 複製一份為 `env.php` 並填入相關資訊以啟用系統中的寄信、付款功能