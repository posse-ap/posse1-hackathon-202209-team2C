DROP SCHEMA IF EXISTS posse;
CREATE SCHEMA posse;
USE posse;

DROP TABLE IF EXISTS events;
CREATE TABLE events (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  name VARCHAR(10) NOT NULL,
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

INSERT INTO events SET name='縦モク', start_at='2021/08/01 21:00', end_at='2021/08/01 23:00';
INSERT INTO events SET name='横モク', start_at='2021/08/02 21:00', end_at='2021/08/02 23:00';
INSERT INTO events SET name='スペモク', start_at='2021/08/03 20:00', end_at='2021/08/03 22:00';
INSERT INTO events SET name='縦モク', start_at='2021/08/08 21:00', end_at='2021/08/08 23:00';
INSERT INTO events SET name='横モク', start_at='2021/08/09 21:00', end_at='2021/08/09 23:00';
INSERT INTO events SET name='スペモク', start_at='2021/08/10 20:00', end_at='2021/08/10 22:00';
INSERT INTO events SET name='縦モク', start_at='2021/08/15 21:00', end_at='2021/08/15 23:00';
INSERT INTO events SET name='横モク', start_at='2021/08/16 21:00', end_at='2021/08/16 23:00';
INSERT INTO events SET name='スペモク', start_at='2021/08/17 20:00', end_at='2021/08/17 22:00';
INSERT INTO events SET name='縦モク', start_at='2021/08/22 21:00', end_at='2021/08/22 23:00';
INSERT INTO events SET name='横モク', start_at='2021/08/23 21:00', end_at='2021/08/23 23:00';
INSERT INTO events SET name='スペモク', start_at='2021/08/24 20:00', end_at='2021/08/24 22:00';
INSERT INTO events SET name='遊び', start_at='2021/09/22 18:00', end_at='2021/09/22 22:00';
INSERT INTO events SET name='ハッカソン', start_at='2021/09/03 10:00', end_at='2021/09/03 22:00';
INSERT INTO events SET name='遊び', start_at='2021/09/06 18:00', end_at='2021/09/06 22:00';
INSERT INTO events SET name='遊び', start_at='2022/10/06 18:00', end_at='2022/10/06 22:00';
INSERT INTO events SET name='遊び', start_at='2022/09/07 09:00', end_at='2022/09/07 22:00';
INSERT INTO events SET name='縦モク', start_at='2022/10/07 09:00', end_at='2022/10/07 22:00';
INSERT INTO events SET name='横モク', start_at='2022/10/06 09:00', end_at='2022/10/06 22:00';
INSERT INTO events SET name='スぺモク', start_at='2022/10/09 09:00', end_at='2022/10/09 22:00';
INSERT INTO events SET name='ハッカソン', start_at='2022/10/20 09:00', end_at='2022/10/20 22:00';
INSERT INTO events SET name='遊び', start_at='2022/10/10 09:00', end_at='2022/10/10 22:00';

INSERT INTO event_attendance SET event_id=1, user_id=1;
INSERT INTO event_attendance SET event_id=1, user_id=2;
INSERT INTO event_attendance SET event_id=1, user_id=3;
INSERT INTO event_attendance SET event_id=2, user_id=1;
INSERT INTO event_attendance SET event_id=2, user_id=1;
INSERT INTO event_attendance SET event_id=3, user_id=2;
INSERT INTO event_attendance SET event_id=18, user_id=1;
INSERT INTO event_attendance SET event_id=19, user_id=2;
INSERT INTO event_attendance SET event_id=20, user_id=3;
INSERT INTO event_attendance SET event_id=21, user_id=2;
INSERT INTO event_attendance SET event_id=22, user_id=1;


INSERT INTO admin
  (admin_name, mail_address, password)
VALUES 
  ('小谷さん', 'kotani@gmail.com', 'kotani');

INSERT INTO users
  (user_name, mail_address, password)
  VALUES
  ('あやか', 'pome@gmail.com', 'ayaka'),
  ('みのり', 'minori@gmail.com', 'minori'),
  ('まりあ', 'maria@gmail.com', 'maria');