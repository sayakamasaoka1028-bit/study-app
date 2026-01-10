# Study App

家族で利用できる在庫管理アプリです。  
Laravelで開発し、LINEログイン機能を実装しています。

## LINEログイン機能
LINEアカウントを利用してログインできます。

![LINEログイン画面](screenshots/line-login.png)

### LINE認可画面
![LINE認可画面](screenshots/line-consent.jpg)

### ログイン後画面
![Dashboard](screenshots/dashboard.png)

## 使用技術
- Laravel
- SQLite
- LINE Login API
- ngrok


## 主な機能
- LINEログインによるユーザー認証
- 家庭用品の在庫管理（追加・増減）
- 在庫状況の一覧表示

---

## 学び・トラブルシューティング（Postmortem）

### LINE通知機能 実装時の設計ミスと学び

LINE通知機能を実装する過程で、  
LINEログイン（Login API）と Messaging API を同時に扱い、  
さらに複数の LINE チャネルを作成してしまったことで、  
userId の管理に不整合が発生しました。

原因を切り分けた結果、以下の LINE の設計原則を正しく理解していなかったことが問題だと分かりました。

#### LINE連携における重要な原則
- userId は「チャネル × ユーザー」で一意に決まる  
  （同じ人でも、チャネルが違えば userId は別になる）
- LINEログイン と Messaging API（Bot）は別の仕組み
- 家族全員に通知したい場合は  
  - 1つの Bot / 1つのチャネル に統一  
  - DB に複数の line_user_id を保存  
  - multicast またはループ送信を行う

最終的に、チャネル構成と userId の保存ルールを整理し、  
設計を見直したうえで再実装する方針としました。

本件を通じて、  
「外部APIは機能だけでなく設計思想を理解して扱う重要性」  
を学ぶことができました。
# 家庭内在庫管理 × LINE通知アプリ

## 概要
家庭内の消耗品を管理し、  
**在庫がなくなった瞬間に LINE へ通知を送る** Laravel アプリです。

「気づいたら無くなっていた」を防ぐことを目的に、  
在庫が **1 → 0** になったタイミングで自動通知を行います。

---

## 主な機能
- ユーザー認証（Laravel Breeze）
- 在庫の登録・増減（CRUD）
- 在庫が 1 → 0 になった際の LINE 通知
- LINEボタン操作  
  - 🛒 買ってきます  
  - 🙅‍♀️ 買ってきません
- LINE Webhook によるユーザー紐づけ
- Service クラスによる外部API分離

---

## 使用技術
- Laravel
- PHP
- LINE Messaging API
- ngrok
- Tailwind CSS
- SQLite / MySQL
- Git / GitHub

---

## 処理フロー
1. ログイン後、在庫一覧を表示
2. 「−」ボタンで在庫を減らす
3. 在庫が 1 → 0 になった瞬間に LINE へ通知
4. LINEのボタンを押す
5. Webhook を受信し、結果画面へ遷移

---

## 工夫した点
- 在庫減少の **境界値（1 → 0）** のみで通知を送信
- LINE API の処理を Service クラスに分離
- ngrok を使った外部公開で実機動作を確認
- Git管理時に不要ファイルを除外し、リポジトリを整理

---
## スクリーンショット

![dashboard](public/images/dashboard.png)
![line-notification](public/images/line-notification.jpg)
![buy-result](public/images/buy-result.jpg)


---

## 学習目的
Laravel を用いた CRUD 実装だけでなく、  
**外部 API との連携・Webhook 処理・実運用を想定した設計**を学ぶことを目的としました。
```
在庫が 1→0 になった瞬間のみ通知する、Laravel×LINE連携アプリを自作しました。Webhook・ngrokで実機確認まで行っています。
※ 本アプリは学習目的のポートフォリオです。
※ LINE連携は Messaging API を利用（トークン等は .env 管理）
```
