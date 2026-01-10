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
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

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
