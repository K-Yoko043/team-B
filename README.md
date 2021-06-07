# beginner-lesson1

## Dockerでの起動

- [Docker](https://www.docker.com/)
    - https://qiita.com/zaki-lknr/items/db99909ba1eb27803456
- [Makefile](http://gnuwin32.sourceforge.net/packages/make.htm)
    - https://bluebirdofoz.hatenablog.com/entry/2019/10/24/221517
    - https://qiita.com/taki-ikat/items/f501f44a8d44e3fd6987

### Setup

```sh
make init
```
http://127.0.0.1:8080/

ユーザID:user01
パスワード:1234

---
## ローカルで起動

### Composerのインストール

```bash
$ composer install
```

### npmのインストール

```bash
$ npm install
```

### データベースとユーザの作成

```bash
$ mysql -u root -p****
create database lesson;
grant all privileges on lesson.* to lesson@localhost identified by 'secret';
exit
```

### テーブルの作成

```bash
$ php artisan migrate --seed
```

### API認証

```bash
$ php artisan passport:install
```

### ビルド

```bash
$ npm run watch
```

```bash
$ php artisan serve
Laravel development server started: <http://127.0.0.1:8000>
```

![image](https://user-images.githubusercontent.com/52206492/98334880-3b9f6200-2047-11eb-9e03-c4e6dd34bd23.png)

ブラウザにて http://127.0.0.1:8000 へアクセス

---

## Lesson内容
- https://hackmd.io/-qeYqI_BTuqD22wd1sDJOg?both# team-B
