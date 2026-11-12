SET NAMES utf8mb4;

DROP TABLE IF EXISTS requests;
DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    position VARCHAR(100),
    department VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(30),
    role ENUM('admin', 'manager', 'employee') NOT NULL DEFAULT 'employee',
    is_approved TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    author_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    date_from DATE,
    date_to DATE,
    comment TEXT,
    status ENUM('new', 'approved', 'rejected') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (login, password, full_name, position, department, email, phone, role, is_approved, created_at) VALUES
('admin1', '$2y$10$fdCbX68uAnAi3XJm3spKduPo6GoSCeLu1pNEPRJerhtoIeGe2KlYu', 'Кузнецов Алексей Игоревич', 'Системный администратор', 'ИТ-отдел', 'a.kuznetsov@novatech.ru', '+7 (915) 210-45-17', 'admin', 1, '2026-09-01 09:00:00'),
('manager', '$2y$10$.AEjUVK5c3VhRdsf15yS.e69.gQNiLwk4hqGGyzjA4DzV1iMAhgYa', 'Смирнова Ольга Викторовна', 'Руководитель отдела продаж', 'Отдел продаж', 'o.smirnova@novatech.ru', '+7 (916) 334-12-08', 'manager', 1, '2026-09-01 09:00:00'),
('employee', '$2y$10$IiCELiAWlQYQwGSDyqP/n.xDdLYwJZ6IkA8J8AKNbahBO9xSbtkuO', 'Морозов Дмитрий Сергеевич', 'Менеджер по продажам', 'Отдел продаж', 'd.morozov@novatech.ru', '+7 (926) 455-71-33', 'employee', 1, '2026-09-01 09:00:00'),
('pavlova', '$2y$10$/8bUpRxKX3bb84sWQ06C1e3gutX5OPl3qo8UbA3aHXl5.jdPElIki', 'Павлова Анна Дмитриевна', 'Менеджер по работе с клиентами', 'Отдел продаж', 'a.pavlova@novatech.ru', '+7 (916) 902-63-17', 'employee', 1, '2026-09-01 09:00:00'),
('belovsp', '$2y$10$OIbGslJbZn6rjrfxZVo28uEufZ6htRAJ9kFknZBPlFXU.e/sQqqxS', 'Белов Сергей Петрович', 'Генеральный директор', 'Администрация', 's.belov@novatech.ru', '+7 (495) 120-00-01', 'manager', 1, '2026-09-01 09:00:00'),
('sokolov', '$2y$10$hTHUMe9vciVK6nSgrrSqbeYYSsP1WmDhjVrH0t1r3MOLDEOmCs8r.', 'Соколов Андрей Николаевич', 'Руководитель ИТ-отдела', 'ИТ-отдел', 'a.sokolov@novatech.ru', '+7 (915) 640-27-51', 'manager', 1, '2026-09-01 09:00:00'),
('fedorov', '$2y$10$D53bVK4/FRNGGSKdRewfDOyozS9KOaBJsIgvCzCBN7oQsiWCakcJG', 'Фёдоров Илья Олегович', 'Инженер технической поддержки', 'ИТ-отдел', 'i.fedorov@novatech.ru', '+7 (999) 301-44-85', 'employee', 1, '2026-09-01 09:00:00'),
('vasileva', '$2y$10$cfrzhDA9IaOwBt6L45pUE.UrYiK4YP2mALwMaH0gGAIIJBl7ENJym', 'Васильева Марина Андреевна', 'Специалист по кадрам', 'Отдел кадров', 'm.vasileva@novatech.ru', '+7 (903) 781-56-90', 'employee', 1, '2026-09-01 09:00:00'),
('kozlova', '$2y$10$YNFX7SnFMrYhMj06gq3Hr.tr6U5EEObggcO7IPJURoMvBr9LYvE7q', 'Козлова Татьяна Ивановна', 'Главный бухгалтер', 'Бухгалтерия', 't.kozlova@novatech.ru', '+7 (903) 447-80-26', 'employee', 1, '2026-09-01 09:00:00'),
('lebedeva', '$2y$10$yG9a2uTpvGi6tFfIRfj./.8zcNs4eQwX8vIdPwMxdyGc0rS0YpUEq', 'Лебедева Наталья Сергеевна', 'Бухгалтер', 'Бухгалтерия', 'n.lebedeva@novatech.ru', '+7 (926) 610-39-74', 'employee', 1, '2026-09-01 09:00:00'),
('orlovka', '$2y$10$2ireZXigNSe1F2S1tvWYfuSWfu43YW8OtYs1QXc4M0cLHHgXQCd5q', 'Орлов Кирилл Андреевич', 'Маркетолог', 'Маркетинг', 'k.orlov@novatech.ru', '+7 (985) 233-16-50', 'employee', 1, '2026-09-01 09:00:00'),
('zakharova', '$2y$10$RW.js66x.xhlAFVpUPyVOOEZZPD1KRxqfUcP7qVLvtZMqfvhbesRq', 'Захарова Виктория Евгеньевна', 'Менеджер по закупкам', 'Логистика', 'v.zakharova@novatech.ru', '+7 (977) 726-91-38', 'employee', 1, '2026-09-01 09:00:00'),
('grigorev', '$2y$10$A0.DHu0dSt5w02x2zHIpk.3W3APRf.eLCP0tNd1CTaol8CgQmn9.u', 'Григорьев Роман Ильич', 'Менеджер по продажам', 'Отдел продаж', 'r.grigorev@novatech.ru', '+7 (999) 812-40-11', 'employee', 0, '2026-10-04 17:20:00'),
('nikitina', '$2y$10$eIJxPUsNQpyqWqQqIOlPtOhLBQE68K6vnBRxx6mx9p2dxCHE0VLMS', 'Никитина Алина Олеговна', 'Бухгалтер', 'Бухгалтерия', 'a.nikitina@novatech.ru', '+7 (926) 455-19-02', 'employee', 0, '2026-10-05 09:10:00');

INSERT INTO news (title, content, author_id, created_at) VALUES
('Запуск корпоративного портала', 'Коллеги, в компании начал работу корпоративный портал сотрудников. Здесь публикуются новости, есть справочник сотрудников и можно подать заявку на отпуск, отгул или справку.
Чтобы получить доступ, пройдите регистрацию — её одобрит администратор.', 1, '2026-09-21 09:30:00'),
('Итоги третьего квартала', 'По итогам третьего квартала выручка компании выросла на 18%. Спасибо всем сотрудникам за отличную работу!', 5, '2026-09-29 10:00:00'),
('Технические работы на почтовом сервере', 'В субботу с 10:00 до 14:00 будут проводиться работы на почтовом сервере. Возможны перебои в работе корпоративной почты.', 6, '2026-10-01 12:15:00'),
('Корпоративный турнир по боулингу', 'Приглашаем всех сотрудников на турнир по боулингу в последнюю пятницу месяца в 19:00. Заявки на участие принимает отдел кадров.', 2, '2026-10-02 15:40:00');

INSERT INTO requests (user_id, type, date_from, date_to, comment, status, created_at) VALUES
(3, 'Отпуск', '2026-10-26', '2026-11-08', 'Ежегодный отпуск по графику', 'new', '2026-10-04 11:05:00'),
(3, 'Справка с места работы', NULL, NULL, 'Справка для банка', 'approved', '2026-09-28 09:20:00'),
(4, 'Отгул', '2026-10-09', '2026-10-09', 'Семейные обстоятельства', 'new', '2026-10-05 08:50:00'),
(7, 'Командировка', '2026-10-14', '2026-10-16', 'Установка оборудования в филиале', 'new', '2026-10-03 14:30:00'),
(10, 'Отпуск', '2026-09-14', '2026-09-20', '', 'rejected', '2026-09-01 16:10:00'),
(11, 'Отгул', '2026-09-25', '2026-09-25', '', 'approved', '2026-09-22 10:45:00');
