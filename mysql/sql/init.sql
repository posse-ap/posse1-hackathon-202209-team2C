DROP SCHEMA IF EXISTS posse;
CREATE SCHEMA posse;
USE posse;

DROP TABLE IF EXISTS events;
CREATE TABLE events (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  name VARCHAR(10) NOT NULL,
  contents VARCHAR(50) NOT NULL,
  start_at DATETIME,
  end_at DATETIME,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME
);

DROP TABLE IF EXISTS event_attendance;
CREATE TABLE event_attendance (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  event_id INT NOT NULL,
  user_id INT,
  attendance BOOLEAN,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME
);


DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  user_name VARCHAR(10) NOT NULL,
  mail_address VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  admin_name VARCHAR(10) NOT NULL,
  mail_address VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO events SET name='縦モク', contents='先輩ともくもくします。', start_at='2021/08/01 21:00', end_at='2021/08/01 23:00';
INSERT INTO events SET name='横モク', contents='同期ともくもくします。', start_at='2021/08/02 21:00', end_at='2021/08/02 23:00';
INSERT INTO events SET name='スペモク', contents='メンターさんともくもくします。', start_at='2021/08/03 20:00', end_at='2021/08/03 22:00';
INSERT INTO events SET name='縦モク', contents='先輩ともくもくします。', start_at='2021/08/08 21:00', end_at='2021/08/08 23:00';
INSERT INTO events SET name='横モク', contents='同期ともくもくします。', start_at='2021/08/09 21:00', end_at='2021/08/09 23:00';
INSERT INTO events SET name='スペモク', contents='メンターさんともくもくします。', start_at='2021/08/10 20:00', end_at='2021/08/10 22:00';
INSERT INTO events SET name='縦モク', contents='先輩ともくもくします。', start_at='2021/08/15 21:00', end_at='2021/08/15 23:00';
INSERT INTO events SET name='横モク', contents='同期ともくもくします。', start_at='2021/08/16 21:00', end_at='2021/08/16 23:00';
INSERT INTO events SET name='スペモク', contents='メンターさんともくもくします。', start_at='2021/08/17 20:00', end_at='2021/08/17 22:00';
INSERT INTO events SET name='縦モク', contents='先輩ともくもくします。', start_at='2021/08/22 21:00', end_at='2021/08/22 23:00';
INSERT INTO events SET name='横モク', contents='同期ともくもくします。', start_at='2021/08/23 21:00', end_at='2021/08/23 23:00';
INSERT INTO events SET name='スペモク', contents='メンターさんともくもくします。', start_at='2021/08/24 20:00', end_at='2021/08/24 22:00';
INSERT INTO events SET name='遊び', contents='リアイベ！遊びます。', start_at='2021/09/22 18:00', end_at='2021/09/22 22:00';
INSERT INTO events SET name='ハッカソン', contents='お題に沿ったものをチーム開発します。', start_at='2021/09/03 10:00', end_at='2021/09/03 22:00';
INSERT INTO events SET name='遊び', contents='リアイベ！遊びます。', start_at='2021/09/06 18:00', end_at='2021/09/06 22:00';
INSERT INTO events SET name='遊び', contents='リアイベ！遊びます。', start_at='2021/10/06 18:00', end_at='2021/10/06 22:00';
INSERT INTO events SET name='遊び', contents='リアイベ！遊びます。', start_at='2022/09/09 09:00', end_at='2022/09/09 22:00';
INSERT INTO events SET name='縦モク', contents='先輩ともくもくします。', start_at='2022/09/10 09:00', end_at='2022/09/10 22:00';
INSERT INTO events SET name='横モク', contents='同期ともくもくします。', start_at='2022/09/10 09:00', end_at='2022/09/10 23:00';
INSERT INTO events SET name='縦モク', contents='先輩ともくもくします。', start_at='2022/09/11 09:00', end_at='2022/09/11 22:00';
INSERT INTO events SET name='縦モク', contents='先輩ともくもくします。', start_at='2022/09/12 09:00', end_at='2022/09/12 22:00';
INSERT INTO events SET name='縦モク', contents='先輩ともくもくします。', start_at='2022/10/10 09:00', end_at='2022/10/10 22:00';
INSERT INTO events SET name='横モク', contents='同期ともくもくします。', start_at='2022/10/06 09:00', end_at='2022/10/06 22:00';
INSERT INTO events SET name='スぺモク', contents='もくもくします。', start_at='2022/10/09 09:00', end_at='2022/10/09 22:00';
INSERT INTO events SET name='ハッカソン', contents='お題に沿ったものをチーム開発します。', start_at='2022/10/20 09:00', end_at='2022/10/20 22:00';
INSERT INTO events SET name='遊び', contents='リアイベ！遊びます。', start_at='2022/10/10 09:00', end_at='2022/10/10 22:00';

INSERT INTO event_attendance
  (event_id, user_id, attendance)
  VALUES
  (17, 1, true),
  (17, 2, false),
  (19, 2, true),
  (19, 3, true),
  (20, 2, false),
  (21, 1, true),
  (22, 1, true),
  (23, 2, true),
  (24, 3, false);


INSERT INTO admin
  (admin_name, mail_address, password)
VALUES 
  ('小谷さん', 'kotani@gmail.com', sha1('kotani'));

INSERT INTO users
  (user_name, mail_address, password)
  VALUES
  ('あやか', 'pome@gmail.com', sha1('ayaka')),
  ('みのり', 'minori@gmail.com', sha1('minori')),
  ('まりあ', 'maria@gmail.com', sha1('maria'));