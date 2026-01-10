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
